<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Title extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost) {
			return $ctx->activepost->title;
		} else {
			return $ctx->blog->title;
		}
	}
}