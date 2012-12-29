<?php
namespace TumblrMock;
use TumblrMock\PhotoSize;

class Photo {
	public $caption = "";
	/**
	 * Array of "PhotoSize"s
	 * @var Array[]PhotoSize
	 */
	public $alt_sizes = array();
	/**
	 * @var PhotoSize
	 */
	public $original_size;
	
	public function Deserialize ($obj){
		$this->caption = $obj->caption;
		
		$size = new PhotoSize;
		$size->Deserialize($obj->original_size);
		$this->original_size = $size;
		
		foreach($obj->alt_sizes as $i => $sz) {
			$size = new PhotoSize;
			$size->Deserialize($sz);
			$this->alt_sizes[] = $size;
		}
	}
}