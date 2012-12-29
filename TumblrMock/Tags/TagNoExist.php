<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class TagNoExist extends ParseTag {
	/**
	 * Return nothing
	 */
	public function TagRender($ctx) {
		return '';
	}
}