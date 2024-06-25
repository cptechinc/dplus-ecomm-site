<?php namespace App\Pw\Hooks;
// Purl URI Manipulation Library
use Purl\Url as Purl;
use Purl\Query as PurlQuery;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInput as PwWireInput;
// App
use App\Urls\PurlPaginator;

/**
 * WireInput
 * Adds Hooks for WireInput
 * 
 * @static self $instance
 */
class WireInput extends WireData {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/** @return self */
	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Paginate Url with options
	 * @param PwWireInput $input
	 * @param array       $options   Array of options:
	 * 	                   - `includeQueryString` (bool): Whether to include query string. (default=true)
	 * 	                   - `pagenbr`           (int) : Page Number. (default=1)
	 * @return string
	 */
	public function paginateUrl(PwWireInput $input, $options = []) {
		$defaults = [
			'includeQueryString' => true,
			'pagenbr'            => 1,
			'removeFromQueryString' => [],
			'addToQueryString'      => [],
		];
		$options = array_merge($defaults, $options);
		$url = new Purl($input->url());

		if ($options['includeQueryString'] === true) {
			/** @var PurlQuery */
			$url->query = $input->queryString();

			if (empty($options['removeFromQueryString']) === false) {
				foreach ($options['removeFromQueryString'] as $key) {
					if ($url->query->has($key)) {
						$url->query->remove($key);
					}
				}
			}

			if (empty($options['addToQueryString']) === false) {
				foreach ($options['addToQueryString'] as $key => $value) {
					$url->query->set($key, $value);
				}
			}
		}
		PurlPaginator::paginateSimple($url, $options['pagenbr']);
		return $url->getUrl();
	}

	/**
	 * Add Sorting to URL
	 * @param  PwWireInput $input
	 * @param  string      $column
	 * @param  array       $options
	 * @return string
	 */
	public function sortUrl(PwWireInput $input, $column, $options = []) {
		$options['includeQueryString'] = true;
		$url = new Purl($this->paginateUrl($input, $options));
		$direction = 'ASC';
		
		if ($url->query->has('orderBy') === false) {
			$url->query->set('orderBy', $column);
			$url->query->set('sortDir', $direction);
			return $url->getUrl();
		}

		if ($url->query->has('sortDir') && $url->query->get('sortDir') == 'ASC') {
			$direction = 'DESC';
		}

		if ($url->query->get('orderBy') != $column) {
			$direction = 'ASC';
		}

		$url->query->set('orderBy', $column);
		$url->query->set('sortDir', $direction);
		return $url->getUrl();
	}

/* =============================================================
	Hooks
============================================================= */
	public function addHooks() {
		$this->addHook('WireInput::paginateUrl', function($event) {
			$input    = $event->object;
			$pagenbr = $event->arguments(0);
			$options = $event->arguments(1);
			if (empty($options)) {
				$options = [];
			}
			$options['pagenbr'] = $pagenbr;
			$event->return = $this->paginateUrl($input, $options);
		});

		$this->addHook('WireInput::sortUrl', function($event) {
			$input    = $event->object;
			$column = $event->arguments(0);
			$options = $event->arguments(1);
			if (empty($options)) {
				$options = [];
			}
			$event->return = $this->sortUrl($input, $column, $options);
		});

		$this->addHook('WireInput::reloadMenuPermissionsUrl', function($event) {
			/** @var PwWireInput */
			$input    = $event->object;
			$url = new Purl($input->url(['withQueryString' => true]));
			$url->query->set('reloadMenuPermissions', 'true');
			$event->return = $url->getUrl();
		});
	}
}
