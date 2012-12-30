<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Body extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost) {
			return '';
		}
		return $ctx->activepost->body;
	}
}