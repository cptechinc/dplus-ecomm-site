<?php namespace Controllers\Abstracts;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
use Propel\Runtime\Collection\Collection;
// ProcessWire Classes, Modules
use ProcessWire\WireData;


/**
 * AbstractController
 * Template class to handle Lookup Requests
 */
abstract class AbstractAjaxLookupController extends AbstractController {
	const FIELDS_LOOKUP = ['q|text'];

	protected static function parseRenderPath(WireData $data) {
		$input = self::pw('input');
		$path = $input->urlSegment(count($input->urlSegments()));
		$path = rtrim(str_replace(self::pw('page')->url, '', $input->url()), '/');
		$path = preg_replace('/page\d+/', '', $path);
		$path = str_replace('lookup/', '', $path);
		$twigpath = "ajax/lookup/display.twig";
		
		if (self::getTwigLoader()->exists("ajax/lookup/$path/display.twig") === false) {
			return $twigpath;
		}
		$twigpath = "ajax/lookup/$path/display.twig";
		return $twigpath;
	}

	/**
	 * Render HTML Results
	 * @param  WireData 		$data
	 * @param  PropelModelPager $results
	 * @return string
	 */
	protected static function _render(WireData $data, $vars = []) {
		$twigpath = static::parseRenderPath($data);
		return self::getTwig()->render($twigpath, $vars);
	}

	/**
	 * Render HTML Results for Propel Model Pager
	 * @param  WireData 		$data
	 * @param  PropelModelPager $results
	 * @return string
	 */
	protected static function render(WireData $data, PropelModelPager $results, $extraVars = []) {
		$vars = array_merge($extraVars, ['results' => $results]);
		return self::_render($data, $vars);
	}

	/**
	 * Render HTML Results for Propel Collection
	 * @param  WireData    $data
	 * @param  Collection  $results
	 * @return string
	 */
	protected static function renderPropelCollection(WireData $data, Collection $results, $extraVars = []) {
		$vars = array_merge($extraVars, ['results' => $results]);
		return self::_render($data, $vars);
	}
}
