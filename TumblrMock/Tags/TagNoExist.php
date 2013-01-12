<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class TagNoExist extends ParseTag {
	/**
	 * Return the text of the tag, allows easier debugging
	 */
	public function TagRender($ctx) {
		// For tags that are not template-defined,
		// check the parser meta settings to see
		// if the tag is calling a meta value
		if (array_key_exists($this->tagname, $ctx->parser->MetaOptions)) {
			return $ctx->parser->MetaOptions[$this->tagname];
		}
		// if not then just return the full input
		return $this->fulltagtext;
	}
}