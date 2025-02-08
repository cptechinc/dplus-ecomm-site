<?php namespace App\Ecomm\Services\Dpay;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// Dpay
use Dpay\Db\Tables\PaymentLinks as PaymentLinksTable;
use Dpay\Db\Tables\PaymentLinks\Record as PaymentLinkRecord;
use Dpay\Db\Tables\PaymentLinks\FilterData;
// Dplus
use Dplus\Database\Tables\SalesHistory as ShTable;
// App
use App\Configs\Configs\App as ConfigApp;
use App\Configs\Configs\Dpay as ConfigDpay;
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;

/**
 * PaymentLinks
 * Provides PaymentLinks Services
 * 
 * @property PaymentLinksTable $table
 */
class PaymentLinks extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = PaymentLinksTable::instance();
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return IF Order has a payment link
	 * @param  int $ordn
	 * @return bool
	 */
	public function existsByOrdn($ordn) {
		return $this->table->existsByOrdernbr($ordn);
	}

	/**
	 * Return payment link record
	 * @param  int $ordn
	 * @return PaymentLinkRecord
	 */
	public function paymentLinkByOrdn($ordn) {
		return $this->table->recordByOrdernbr($ordn);
	}

	/**
	 * Return list of payment links filtered
	 * @param  FilterData $data
	 * @return array(PaymentLinkRecord)
	 */
	public function find(FilterData $data) {
		return $this->table->findPaged($data);
	}

	/**
	 * Return the number of payment links
	 * @param  FilterData $data
	 * @return int
	 */
	public function count(FilterData $data) {
		return $this->table->count($data);
	}

/* =============================================================
	CRUD Processing
============================================================= */
	/**
	 * Process Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processInput(WireInputData $input) {
		switch ($input->text('action')) {
			case 'create-paymentlink-single-order':
				return $this->processCreateSingleOrderLink($input);
				break;
		}
	}

	/**
	 * Create Payment Link for single order
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processCreateSingleOrderLink(WireInputData $input) {
		$data = new WireData();
		$data->ordn = $input->int('ordn');

		/** @var ConfigDpay */
		$config = $this->config->dpay;
		/** @var ConfigApp */
		$configApp = $this->config->app;

		if ($configApp->useDpay === false || $config->dpay->allowCreatePaymentLinks === false) {
			return false;
		}

		if (ShTable::instance()->exists($data->ordn) === false) {
			return false;
		}
		$this->requestCreateSingleOrderLink($data);
		return $this->existsByOrdn($data->ordn);
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Request Create PaymentLink
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestCreateSingleOrderLink(WireData $data) {
		$rqst =  ['CREATEPAYMENTLINK', "ORDERNBR=$data->ordn"];
		return $this->writeRqstUpdateDplus($rqst);
	}
}