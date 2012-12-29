<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Video extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'video') {
			$sizes = $ctx->activepost->extradata['player'];
			foreach($sizes as $_ => $size) {
				if ($size->width == $this->mod) {
					return $size->embed_code;
				}
			}
			return $sizes[0]->embed_code;
		}
		return '';
	}
}