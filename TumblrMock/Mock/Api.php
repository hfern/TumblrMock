<?php
namespace TumblrMock\Mock;
use TumblrMock\Deserializer\Deserializer;

class Api {
	private $ApiKey = false;
	private $cache_replies = true;
	
	private $op_notes_info = true;
	private $op_reblog_info = true;
	
	private $Api_Url = 'http://api.tumblr.com/v2/blog/%s/%s?%s';
	
	/**
	 * Get a new Api Instance
	 * @param string $ApiKey
	 * @param bool $cachereplies
	 */
	public function __construct($ApiKey, $cachereplies = true) {
		$this->ApiKey = $ApiKey;
		$this->cache_replies = $cachereplies;
	}
	
	/**
	 * Return a Blog object from a tumblr domain
	 * Can be used for mockups
	 * @param string $domain
	 * @param bool $cache whether to get/set in the cache
	 */
	public function BlogFromDomain($domain, $cache = true) {
		if ($cache && $this->CacheValid($domain)) {
			return $this->GetCached($domain);
		}
		$params   = array();
		$params[] = "api_key=".$this->ApiKey;
		$params[] = "notes_info=".($this->op_notes_info?'true':'false');
		$params[] = "reblog_info=".($this->op_reblog_info?'true':'false');
		
		$url = $this->UrlFromParts($domain, 'posts', implode('&', $params));
		$json = "";
		if ($this->cache_replies) {
			$json = HttpService::CacheGetPage($url);
		} else {
			$json = HttpService::GetPage($url);
		}
		$DeSer = new Deserializer;
		$DeSer->LoadJson($json);
		$blog = $DeSer->GetBlog();
		if ($cache) {
			$this->SetCache($domain, $blog);
		}
		return $blog;
	}
	
	private function GetCacheFname($domain) {
		$cachedirectory = dirname(__DIR__).'/Cache';
		return sprintf('%s/srlzd_%s.phpserialized', 
						$cachedirectory, 
						md5($domain));
	}
	
	private function CacheValid($domain, $t = 3600) {
		$fn = $this->GetCacheFname($domain);
		$fexists = file_exists($fn);
		return $fexists && filemtime($fn) >= time()-$t;
	}
	
	private function SetCache($domain, $blog) {
		$fn = $this->GetCacheFname($domain);
		file_put_contents($fn, serialize($blog));
	}
	
	private function GetCached($domain) {
		$fn = $this->GetCacheFname($domain);
		return unserialize(file_get_contents($fn));
	}
	
	private function UrlFromParts($domain, $path, $querystring) {
		return sprintf($this->Api_Url, $domain, $path, $querystring);
	}
	
}