<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Description extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->blog->description || $ctx->blog->description == '') {
			return '';
		}
		return $ctx->blog->description;
	}
}