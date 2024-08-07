<?php namespace App\Pw\Hooks;
// Purl URI Manipulation Library
use Purl\Url as Purl;
// ProcessWire
use ProcessWire\Pages;

/**
 * ApiUrls
 * Provide Hooks, functions to get API URLs
 */
class ApiUrls extends AbstractStaticHooksAdder {
/* =============================================================
	Hooks
============================================================= */
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public static function addHooks() {
		$m = self::pwModuleApp();

		$m->addHook("Pages::searchLookupUrl", function($event) {
			$event->return = self::lookupUrl($event->arguments(0), $event->arguments(1));
		});

		$m->addHook("Pages::jsonApiUrl", function($event) {
			$event->return = self::jsonApiUrl($event->arguments(0), $event->arguments(1));
		});
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Pages
	 * @return Pages
	 */
	private static function pwPages() {
		return self::pw('pages');
	}

	/**
	 * Return URL to JSON API Endpoint
	 * @param  string $endpoint
	 * @param  array  $query
	 * @return string
	 */
	public static function jsonApiUrl($endpoint, $query = []) {
		$url = new Purl(self::pwPages()->get("template=ajax-json")->httpUrl);

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
	public static function lookupUrl($endpoint, $query = []) {
		$url = new Purl(self::pwPages()->get("template=ajax")->httpUrl);

		if ($endpoint) {
			$url->path->add(rtrim($endpoint, '/'));
		}
		if ($query) {
			$url->query->setData($query);
		}
		return $url->getUrl();
	}
}