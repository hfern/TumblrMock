<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Source extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost && $ctx->activepost->type == 'quote') {
			return '';
		}
		if (!isset($ctx->activepost->extradata['source'])) {
			return '';
		}
		if ($ctx->activepost->extradata['source'] == '') {
			return '';
		}
		return $ctx->activepost->extradata['source'];
	}
}