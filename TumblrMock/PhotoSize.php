<?php
namespace TumblrMock;

/**
 * Used for photos to give alternative size options
 * @author Hunt
 */
class PhotoSize {
	/**
	 * @var int
	 */
	public $width = 0;
	/**
	 * @var int
	 */
	public $height = 0;
	/**
	 * Absolute url to photo on tumblr's servers
	 * @var string
	 */
	public $url = "";
	
	public function Deserialize($obj) {
		$this->width = $obj->width;
		$this->height = $obj->height;
		$this->url = $obj->url;
	}
}