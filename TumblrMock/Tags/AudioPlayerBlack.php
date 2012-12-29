<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;
/**
 * Tumblr's api doesn't give any player color options
 * @author Hunt
 */
class AudioPlayerBlack extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'audio') {
			return  $ctx->activepost->extradata['player'];
		}
		return '';
	}
}