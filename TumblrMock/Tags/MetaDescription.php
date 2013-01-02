<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class MetaDescription extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->blog->description || $ctx->blog->description == '') {
			return '';
		}
		return htmlspecialchars($ctx->blog->description);
	}
}