<?php
namespace TumblrMock;

/**
 * Note to a post
 * @author Hunt
 */
class Note {
	/**
	 * Unix TS
	 * @var int
	 */
	public $timestamp = 0;
	/**
	 * @var string
	 */
	public $blog_name = "";
	/**
	 * @var string
	 */
	public $blog_url = "";
	/**
	 * If $type==reblog
	 * @var int
	 */
	public $post_id = 0;
	/**
	 * @var string
	 */
	public $type = "";
	
	public $added_text = "";
	
	public function Deserialize($obj) {
		foreach ( get_object_vars($obj) as $var => $val) {
			if (property_exists($this, $var)) {
				$this->$var = $val;
			}
		}
	}
}