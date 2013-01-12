<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

/**
 * Invoked when a meta tag is found during parsing.
 * Example:
 * <meta name="color:Background" content="#eee"/>
 * @author Hunt
 *
 */
class MetaTag extends ParseTag {
	public function TagRender($ctx) {
		return $this->fulltagtext;
	}
	public function instantialize(&$parser) {
		// add meta options to parser meta index
		if (array_key_exists('name', $this->options)
		&&  array_key_exists('content', $this->options)) {
			$parser->MetaOptions[$this->options['name']]
				= $this->options['content'];
		}
	}
}