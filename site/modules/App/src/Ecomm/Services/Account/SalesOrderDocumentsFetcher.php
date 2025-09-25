<?php namespace App\Ecomm\Services\Account;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;
use Dplus\Docm\Finders\SalesOrder as DOCM;

class SalesOrderDocumentsFetcher extends WireData {
    private $allowed;
    private $ordn;

    public function __construct($ordn) {
        $this->ordn = $ordn;
        $this->allowed = SoTable::instance()->isForCustid($ordn, $this->session->get('ecuser')->custid) === false;
    }

    public function fetch() {
        if ($this->allowed === false) {
            return new ObjectCollection();
        }
        return new ObjectCollection($this->fetchArray());
    }
    private function fetchArray() {
        $list = [
			DOCM::findLastSoAck($this->ordn),
			DOCM::findLastSoPack($this->ordn),
			DOCM::findLastArInvoice($this->ordn),
		];
        return array_filter($list);
    }


}