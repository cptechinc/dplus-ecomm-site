<?php namespace App;
// ProcessWire
use ProcessWire\Wire;
use ProcessWire\WireData;
use ProcessWire\WireException;
use ProcessWire\WireInput;
use ProcessWire\WireInputData;

/**
 * AbstractCrudManager
 * Template class for handling CRUD requests
 */
abstract class AbstractCrudManager extends WireData {
	const FIELD_ATTRIBUTES = [
		// 'qty'     => ['type' => 'number', 'label' => 'Qty', 'precision' => 2],
	];

	protected $fieldAttributes = [];

	protected static $instance;

	/** @return static */
	public static function instance() {
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	public function __construct() {
		$this->initFieldAttributes();
	}

/* =============================================================
	Field Configs
============================================================= */
	/**
	 * Intialize Field Attributes that need values set from Configs
	 * @return void
	 */
	public function initFieldAttributes() {
		if (empty($this->fieldAttributes) === false) {
			return true;
		}
		$this->fieldAttributes = static::FIELD_ATTRIBUTES;
		return true;
	}

	/**
	 * Return Field Attribute value
	 * @param  string $field Field Name
	 * @param  string $attr  Attribute Name
	 * @return mixed|bool
	 */
	public function fieldAttribute($field = '', $attr = '') {
		if (empty($field) || empty($attr)) {
			return false;
		}
		$this->initFieldAttributes();
		
		if (array_key_exists($field, $this->fieldAttributes) === false) {
			return false;
		}
		if (array_key_exists($attr, $this->fieldAttributes[$field]) === false) {
			return false;
		}
		return $this->fieldAttributes[$field][$attr];
	}

	/**
	 * Return Label for field
	 * @param  string $field
	 * @return string
	 */
	public function fieldLabel($field) {
		$label = $this->fieldAttribute($field, 'label');

		if ($label !== false) {
			return $label;
		}

		if (in_array($field, ['code', 'description'])) {
			return self::FIELD_ATTRIBUTES[$field]['label'];
		}
		return $field;
	}

/* =============================================================
	CRUD Processing
============================================================= */
	/**
	 * Process Data, Update Database
	 * @param  Wire $input Input Data
	 */
	public function process(Wire $data = null) {
		if (empty($data)) {
			$data = $this->input;
		}

		switch (get_class($data)) {
			case 'ProcessWire\\WireData';
				$input = $this->parseWireData($data);
				$this->processInput($input);
				break;
			case 'ProcessWire\\WireInput';
				$input = $this->parseWireInput($data);
				$this->processInput($input);
				break;
			case 'ProcessWire\\WireInputData';
				$this->processInput($data);
				break;
			default:
				throw new WireException("Invalid input object", 500);
				break;
		}
	}

	/**
	 * Return parsed WireData
	 * @param  WireData $data
	 * @return WireInputData
	 */
	protected function parseWireData(WireData $data) {
		$input = new WireInputData();
		$input->setArray($data->data);
		return $input;
	}
	
	/**
	 * Return parsed WireInput
	 * @param  WireData $data
	 * @return WireInputData
	 */
	protected function parseWireInput(WireInput $data) {
		$rm = strtolower($data->requestMethod());
		$input = $data->$rm;
		return $input;
	}

	/**
	 * Process Request
	 * @param  WireInputData $input
	 * @return void
	 */
	abstract protected function processInput(WireInputData $input);
}