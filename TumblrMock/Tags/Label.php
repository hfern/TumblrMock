<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;
/**
 * Labels for chats
 * @author Hunt
 */
class Label extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost == false || $ctx->activepost->type != 'chat') {
			return '';
		}
		if (!isset($ctx->extra['activeline'])) {
			return '';
		}
		return $ctx->extra['activeline']->label;
	}
}
