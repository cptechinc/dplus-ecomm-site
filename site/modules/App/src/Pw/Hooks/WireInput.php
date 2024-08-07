<?php namespace App\Pw\Hooks;
// Purl URI Manipulation Library
use Purl\Url as Purl;
use Purl\Query as PurlQuery;
// ProcessWire
use ProcessWire\WireInput as PwWireInput;
// App
use App\Urls\PurlPaginator;

/**
 * WireInput
 * Adds Hooks for WireInput
 */
class WireInput extends AbstractStaticHooksAdder {
/* =============================================================
	Hooks
============================================================= */
	public static function addHooks() {
		$m = self::pwModuleApp();

		$m->addHook('WireInput::paginateUrl', function($event) {
			$input    = $event->object;
			$pagenbr = $event->arguments(0);
			$options = $event->arguments(1);
			if (empty($options)) {
				$options = [];
			}
			$options['pagenbr'] = $pagenbr;
			$event->return = self::paginateUrl($input, $options);
		});

		$m->addHook('WireInput::sortUrl', function($event) {
			$input    = $event->object;
			$column = $event->arguments(0);
			$options = $event->arguments(1);
			if (empty($options)) {
				$options = [];
			}
			$event->return = self::sortUrl($input, $column, $options);
		});
	}
	
/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Paginate Url with options
	 * @param PwWireInput $input
	 * @param array       $options   Array of options:
	 * 	                   - `includeQueryString` (bool): Whether to include query string. (default=true)
	 * 	                   - `pagenbr`           (int) : Page Number. (default=1)
	 * @return string
	 */
	public static function paginateUrl(PwWireInput $input, $options = []) {
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
	public static function sortUrl(PwWireInput $input, $column, $options = []) {
		$options['includeQueryString'] = true;
		$url = new Purl(self::paginateUrl($input, $options));
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
}
