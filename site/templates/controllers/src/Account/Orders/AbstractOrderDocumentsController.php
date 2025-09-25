<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
use ProcessWire\WireException;
use ProcessWire\WireHttp;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Logs\PwLogger;
// Dplus
use Dplus\Docm\Finders\SalesOrder as DOCM;
// App
use App\Ecomm\Services\Account\SalesOrderDocumentsFetcher as DocsFetcher;


/**
 * AbstractOrderDocumentsController
 * Template for handling the Order Documents page
 */
abstract class AbstractOrderDocumentsController extends AbstractOrderController {
	const SESSION_NS = 'sales-order';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Sales Order Documents';
	const PAGE_NAME     = 'order';
	const ERRORLOG_NAME = 'error-so-documents';
	const ERRORLOG_LOG_OPTIONS = ['showUser' => false, 'showURL' => true];
	const ALLOW_AJAX = true;

/* =============================================================
	1. Indexes
============================================================= */
	/**
	 * Process HTTP GET Request
	 * @param  WireData $data
	 * @return string|bool
	 */
	public static function index(WireData $data) {
		if (static::init($data) === false) {
			return false;
		}
		self::sanitizeParametersShort($data, ['ordn|int', 'download|bool']);
		self::pw('page')->title = "Order #$data->ordn Documents";
		if ($data->download) {
			static::download($data);
		}
		static::initHooks();
		return static::order($data);
	}

	/**
	 * Handle Download of document
	 * @param  WireData $data
	 * @return bool
	 */
	protected static function download(WireData $data) {
		self::sanitizeParametersShort($data, ['folder|text', 'filename|text']);

		if (empty($data->folder) || empty($data->filename)) {
			self::pw('session')->redirect(static::urlOrder($data->ordn), $http301=false);
			return false;
		}
		$file = DOCM::document($data->folder, $data->filename);

		if (empty($file)) {
			self::pw('session')->redirect(static::urlOrder($data->ordn), $http301=false);
			return false;
		}
		$http = new WireHttp();

		try {
			$http->sendFile(DOCM::filepath($file));
		} catch(WireException $e) {
			$user = self::getEcUser();
			$logdata = [$e->getMessage(), "ORDN=$data->ordn", "CUSTID=$user->custid", "USEREMAIL=$user->email"];
			PwLogger::log(self::ERRORLOG_NAME, $logdata, self::ERRORLOG_LOG_OPTIONS);
			self::pw('session')->redirect(static::urlOrder($data->ordn), $http301=false);
			return false;
		}
		return true;
	}

	/**
	 * Handle Display of Documents Page
	 * @param  WireData $data
	 * @return bool
	 */
	protected static function order(WireData $data) {
		$documents = static::fetchDocuments($data);
		return static::displayDocuments($data, $documents);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Order Documents
	 * @param  WireData $data
	 * @return ObjectCollection
	 */
	protected static function fetchDocuments(WireData $data) {
		$fetcher = new DocsFetcher($data->ordn);
		return $fetcher->fetch();
	}

/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return URL to List Page
	 * @return string
	 */
	protected static function listUrl() {
		return Orders::url();
	}

	/**
	 * Return URL to Order Page
	 * @param  string $ordn
	 * @return string
	 */
	public static function urlOrder($ordn) {
		return static::url() . "$ordn/";
	}
	
	/**
	 * Return URL to Order Page
	 * @param  string $ordn
	 * @return string
	 */
	public static function urlDocuments($ordn) {
		return static::urlOrder($ordn) . "documents/";
	}

	public static function urlDownload($ordn, $folder, $filename) {
		return static::urlDocuments($ordn) . '?' . http_build_query(['download' => 'true', 'folder' => $folder, 'filename' => $filename]);
	}
	
/* =============================================================
	5. Displays
============================================================= */
	protected static function displayDocuments(WireData $data, ObjectCollection $documents) {
		return static::renderDocuments($data, $documents);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function renderDocuments(WireData $data, ObjectCollection $documents) {
		return self::getTwig()->render('account/orders/order/documents/page.twig', ['documents' => $documents]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::ordersListUrl", function(HookEvent $event) {
			$event->return = static::listUrl();
		});

		$m->addHook("$selector::orderUrl", function(HookEvent $event) {
			$event->return = static::urlOrder($event->arguments(0));
		});

		$m->addHook("$selector::downloadDocumentUrl", function(HookEvent $event) {
			$event->return = static::urlDownload($event->arguments(0), $event->arguments(1), $event->arguments(2));
		});
	}
}