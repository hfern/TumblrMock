<?php
namespace TumblrMock\Mock;
/**
 * Utilities for accessing the tumblr API
 * Mostly used for CURL abstraction
 * @author Hunt
 */
final class HttpService {
	/**
	 * Returns contents of page from given url
	 * @param string $url
	 * @return string html
	 */
	public static function GetPage($url) {
		if (self::CurlInstalled()) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			return $content;
		}
		//@TODO Support file_get_contents and raw streams
	}
	
	/**
	 * Same as GetPage, but with optional caching
	 * @param string $url
	 * @param int $expiry
	 */
	public static function CacheGetPage($url, $expiry = 3600) {
		$cachefile =self::GetCacheFileName($url);
		$time = time() - $expiry;
		
		if (file_exists($cachefile) && filemtime($cachefile) > $time) {
			return file_get_contents($cachefile);
		}
		
		$page = self::GetPage($url);
		file_put_contents($cachefile, $page);
		return $page;
	}
	
	private static function GetCacheFileName($url) {
		$cachedirectory = dirname(__DIR__).'/Cache';
		$md5 = md5($url);
		return sprintf("%s/%s.cache", $cachedirectory, $md5);
	}
	
	private static function CurlInstalled() {
		return in_array("curl", get_loaded_extensions());
	}
}