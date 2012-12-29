<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Permalink extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost) {
			return $ctx->activepost->post_url;
		}
		return '';
	}
}