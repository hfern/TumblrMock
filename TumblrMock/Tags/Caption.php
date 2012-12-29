<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Caption extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost && $ctx->activepost->caption != '') {
			return $ctx->activepost->caption;
		}
		return '';
	}
}