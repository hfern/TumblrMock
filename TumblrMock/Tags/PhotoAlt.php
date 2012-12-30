<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class PhotoAlt extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost) {
			return $ctx->activepost->caption;
		}
		// PhotoAlts are not provided by tumblr
		return 'The Photo Alt';
	}
}