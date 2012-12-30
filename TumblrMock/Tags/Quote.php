<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Quote extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost || $ctx->activepost->type != 'quote') {
			return '';
		}
		// PhotoAlts are not provided by tumblr
		return $ctx->activepost->extradata['text'];
	}
}