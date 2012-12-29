<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class RSS extends ParseTag {
	public function TagRender($ctx) {
		return $ctx->blog->url.'rss';
	}
}