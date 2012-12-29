<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class TagNoExist extends ParseTag {
	/**
	 * Return the text of the tag, allows easier debugging
	 */
	public function TagRender($ctx) {
		return $this->fulltagtext;
	}
}