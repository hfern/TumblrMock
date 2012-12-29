<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;
/**
 * Alt for chats
 * @author Hunt
 */
class Alt extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost) {
			return '';
		}
		switch ($ctx->activepost->type) {
			/**
			 * "odd" or "even" for each line of this post.
			 * http://www.tumblr.com/docs/en/custom_themes#chat-posts
			 */
			case 'chat':
				return $ctx->extra['lineindex']%2 == 0 ? 'even' : 'odd';
				break;
			default:
				return '';
		}
		return '';
	}
}