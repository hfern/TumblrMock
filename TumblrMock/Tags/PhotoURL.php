<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class PhotoURL extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost) {
			//var_dump($this->mod, $ctx->activepost->photos); die();
			$sizes = $ctx->activepost->photos[0]->alt_sizes;
			foreach($sizes as $_ => $size) {
				if ($size->width == $this->mod) {
					return $size->url;
				}
			}
			return $ctx->activepost->photos[0]->original_size->url;
			//return $ctx->activepost->photos[0]->alt_sizes;
		}
		return '';
	}
}