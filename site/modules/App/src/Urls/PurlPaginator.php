<?php namespace App\Urls;
// Purl URI Manipulation Library
use Purl\Url as Purl;
// ProcessWire
use ProcessWire\WireData;

/**
 * PurlPaginator
 * Handles Adding / Manipulating Pagination for Purl\Url objects
 */
class PurlPaginator extends WireData {
	/**
	 * Add Pagination to Url
	 * @param  Purl    $url
	 * @param  string  $segment Segment to place pagination segment after
	 * @param  int     $pagenbr Page Number
	 * @return Purl
	 */
	public static function paginate(Purl $url, $segment, $pagenbr = 1) {
		$path = $url->getPath();
		$path = rtrim($path, '/') . '/';

		$regex = "((page)\d{1,3})";

		if (strpos($path, 'page/') !== false || preg_match($regex, $path)) {
			$replace = ($pagenbr > 1) ? "page".$pagenbr : "";
			$path  = preg_replace($regex, $replace, $path);
			$doubleRegex = "/((page)\d{1,3})\/((page)\d{1,3})\//";
			$path  = preg_replace($doubleRegex, $replace, $path);
			$url->path = $path;
			return $url;
		}
		$insertafter = "/$segment/";
		$regex = "(($insertafter))";
		$replace = ($pagenbr > 1) ? $insertafter."page".$pagenbr."/" : $insertafter;
		$path  = preg_replace($regex, $replace, $path);
		$url->path = $path;
		return $url;
	}

	/**
	 * Paginate Url by adding pagination to the last segment
	 * @param  Purl   $url
	 * @param  int    $pagenbr  Page Number
	 * @return Purl
	 */
	public static function paginateSimple(Purl $url, $pagenbr = 1) {
		$path = $url->getPath();
		$path = rtrim($path, '/').'/';
		$segments = $url->path->getData();
		$segment = $segments[count($segments) - 1];
		return self::paginate($url, $segment, $pagenbr);
	}

	/**
	 * Return Page number based off offset and show on Page
	 * @param  int      $offset
	 * @param  int|null $showOnPage
	 * @return int
	 */
	public static function getPagenbrFromOffset(int $offset, int $showOnPage = null) {
		if (empty($showOnPage)) {
			$showOnPage = 1;
		}
		$pagenbr = ceil($offset / $showOnPage);
		return $pagenbr;
	}
}
