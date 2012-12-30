<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Target extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost || $ctx->activepost->type != 'link') {
			return '';
		}
		// Target options are not provided in the API,
		// so just simulate the default target="_blank"
		return 'target="_blank"';
	}
}