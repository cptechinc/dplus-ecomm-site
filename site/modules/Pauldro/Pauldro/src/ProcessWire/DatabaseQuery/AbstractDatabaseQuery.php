<?php namespace Pauldro\ProcessWire\DatabaseQuery;
// ProcessWire
use ProcessWire\DatabaseQuery;
use ProcessWire\WireDatabasePDO;
use ProcessWire\WireDatabaseQueryException;

/**
 * AbstractDatabaseQuery
 * NOTE: it's exactly the same as DatabaseQuery except Database can be redefined
 */
abstract class AbstractDatabaseQuery extends DatabaseQuery {
	/**
	 * Return Database
	 * @return WireDatabasePDO
	 */
	public function database() {
		return $this->wire('database');
	}

	/**
	 * Get a unique key to use for bind value
	 * 
	 * Note if you given a `key` option, it will only be used if it is determined unique,
	 * otherwise it’ll auto-generate one. When using your specified key, it is the only
	 * option that applies, unless it is not unique and the method has to auto-generate one.
	 * 
	 * @param array $options 
	 *  - `key` (string): Preferred bind key, or omit (blank) to auto-generate (digit only keys not accepted)
	 *  - `value` (string|int): Value to use as part of the generated key
	 *  - `prefix` (string): Prefix to override default
	 *  - `global` (bool): Require globally unique among all instances?
	 * @return string Returns bind key/name in format ":name" (with leading colon)
	 * @since 3.0.156
	 * 
	 */
	public function getUniqueBindKey(array $options = array()) {
		
		if(empty($options['key'])) {
			// auto-generate key
			$key = ':';
			$prefix = (isset($options['prefix']) ? $options['prefix'] : $this->bindOptions['prefix']);
			$suffix = isset($option['suffix']) && $options['suffix'] ? $options['suffix'] : $this->bindOptions['suffix'];
			$value = isset($options['value']) ? $options['value'] : null;
			$global = isset($options['global']) ? $options['global'] : $this->bindOptions['global'];
			
			if($global) $key .= $prefix . $this->instanceNum;
			
			if($value !== null) {
				if(is_int($value)) {
					$key .= "i";
				} else if(is_string($value)) {
					$key .= "s";
				} else if(is_array($value)) {
					$key .= "a";
				} else {
					$key .= "o";
				}
			} else if($prefix && !$global) {
				$key .= $prefix;
			} else {
				$key .= "v";
			}
			
			$n = 0;
			$k = $key;
			$key = $k . '0' . $suffix;
			
			while(isset($this->bindKeys[$key]) && ++$n) {
				$key = $k . $n . $suffix;
			}
			
		} else {
			// provided key, make sure it is valid and unique (this part is not typically used)
			$key = ltrim($options['key'], ':') . 'X';
			if(!ctype_alnum(str_replace('_', '', $key))) $key = $this->database()->escapeCol($key);
			if(empty($key) || ctype_digit($key[0]) || isset($this->bindKeys[":$key"])) {
				// if key is not valid, then auto-generate one instead
				unset($options['key']);
				$key = $this->getUniqueBindKey($options);
			} else {
				$key = ":$key";
			}
		}
		
		$this->bindKeys[$key] = $key;
		return $key;
	}

	/**
	 * Get SQL query with bind params populated for debugging purposes (not to be used as actual query)
	 * @return string
	 */
	public function getDebugQuery() {
		$sql = $this->getQuery();
		$suffix = $this->bindOptions['suffix'];
		$database = $this->database();
		foreach($this->bindValues as $bindKey => $bindValue) {
			if(is_string($bindValue)) $bindValue = $database->quote($bindValue);
			if($bindKey[strlen($bindKey)-1] === $suffix) {
				$sql = strtr($sql, array($bindKey => $bindValue));
			} else {
				$sql = preg_replace('/' . $bindKey . '\b/', $bindValue, $sql);
			}
		}
		return $sql;
	}

	/**
	 * Prepare and return a PDOStatement
	 * @return \PDOStatement
	 */
	public function prepare() {
		$query = $this->database()->prepare($this->getQuery()); 
		foreach($this->bindValues as $key => $value) {
			$type = isset($this->bindTypes[$key]) ? $this->bindTypes[$key] : $this->pdoParamType($value);
			$query->bindValue($key, $value, $type); 
		}
		return $query; 
	}

	/**
	 * Execute the query with the current database handle
	 * 
	 * @param array $options
	 *  - `throw` (bool): Throw exceptions? (default=true)
	 *  - `maxTries` (int): Max times to retry if connection lost during query. (default=3)
	 *  - `returnQuery` (bool): Return PDOStatement query? If false, returns bool result of execute. (default=true)
	 * @return \PDOStatement|bool
	 * @throws WireDatabaseQueryException|\PDOException
	 */
	public function execute(array $options = array()) {
		
		$defaults = array(
			'throw' => true, 
			'maxTries' => 3, 
			'returnQuery' => true,
		);
	
		$options = array_merge($defaults, $options);
		$numTries = 0;
		
		do {
			$retry = false;
			$exception = null;
			$result = false;
			$query = null;
			
			try {
				$query = $this->prepare();
				$result = $query->execute();
			} catch(\PDOException $e) {
				$msg = $e->getMessage();
				$code = (int) $e->getCode();
				$retry = $code === 2006 || stripos($msg, 'MySQL server has gone away') !== false;
				if($retry && $numTries < $options['maxTries']) {
					$this->database()->closeConnection(); // note: it reconnects automatically
					$numTries++;
				} else {
					$exception = $e;
					$retry = false;
				}
			}
		} while($retry);
		
		if($exception && $options['throw']) {
			if($this->wire()->config->allowExceptions) throw $exception; // throw original
			$message = (string) $exception->getMessage();
			$code = (int) $exception->getCode();
			// note: re-throw below complains about wrong arguments if the above two 
			// lines are called in the line below, so variables are intermediary
			throw new WireDatabaseQueryException($message, $code, $exception);
		}
		return $options['returnQuery'] ? $query : $result;
	}
}

