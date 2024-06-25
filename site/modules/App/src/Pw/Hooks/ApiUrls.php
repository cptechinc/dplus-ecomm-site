<?php namespace App\Pw\Hooks;
// Purl URI Manipulation Library
use Purl\Url as Purl;
// ProcessWire
use ProcessWire\WireData;

/**
 * ApiUrls
 * Provide Hooks, functions to get API URLs
 * 
 * @static self $instance
 */
class ApiUrls extends WireData {
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
	 * Return URL to JSON API Endpoint
	 * @param  string $endpoint
	 * @param  array  $query
	 * @return string
	 */
	public function jsonApiUrl($endpoint, $query = []) {
		$url = new Purl($this->wire('pages')->get("template=ajax-json")->httpUrl);
		if ($endpoint) {
			$url->path->add(rtrim($endpoint, '/'));
		}
		if ($query) {
			$url->query->setData($query);
		}
		return $url->getUrl();
	}

	/**
	 * Return URL to Lookup
	 * @param  string $endpoint
	 * @param  array  $query
	 * @return void
	 */
	public function lookupUrl($endpoint, $query = []) {
		$url = new Purl($this->wire('pages')->get("template=ajax")->httpUrl);

		if ($endpoint) {
			$url->path->add(rtrim($endpoint, '/'));
		}
		if ($query) {
			$url->query->setData($query);
		}
		return $url->getUrl();
	}

/* =============================================================
	Hooks
============================================================= */
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public function addHooks() {
		$this->addHook("Page::searchLookupUrl", function($event) {
			$event->return = $this->lookupUrl($event->arguments(0), $event->arguments(1));
		});

		$this->addHook("Page::jsonApiUrl", function($event) {
			$event->return = $this->jsonApiUrl($event->arguments(0), $event->arguments(1));
		});
	}
}