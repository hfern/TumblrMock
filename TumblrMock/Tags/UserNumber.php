<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;
/**
 * For chats
 * @author Hunt
 */
class UserNumber extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost || $ctx->activepost->type != 'chat') {
			return '';
		}
		if (!isset($ctx->extra['underline'])) {
			return '';
		}
		return $ctx->extra['lineuid'];
	}
}