<?php namespace App\Ecomm\Util;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireHttp;

/**
 * CgiRequest
 * Sends CGI Requests
 */
class CgiRequest extends WireData {
	/**
	 * Sends HTTP GET Request to CGI BIN file
	 * @param  string $cgi       CGI BIN filename
	 * @param  string $sessionID SessionID for CGI Request
	 * @return bool
	 */
	public static function send($cgi, $sessionID) {
		$http = new WireHttp();
		return $http->get("127.0.0.1/cgi-bin/$cgi?fname=$sessionID");
	}

	/**
	 * Writes an array one datem per line into the dplus directory
	 * @param  array  $data      Array of Lines for the request
	 * @param  string $filename What to name File
	 * @return void
	 */
	public static function writeFile($data, $filename) {
		$content = implode("\n", $data);
		$filename = "/usr/capsys/ecomm/" . $filename;
		return boolval(file_put_contents($filename, $content));
	}
}