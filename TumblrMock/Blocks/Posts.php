<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Posts extends ParseBlock {
	public function render(Context &$ctx) {
		// dereference variable
		$ctx = $ctx;
		$ctx->underpost = true;
		$ctx->postindex = 0;
		$numposts = count($ctx->blog->posts);
		$output = '';
		foreach($ctx->blog->posts as $ctx->postindex => $ctx->activepost) {
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
		}
		return $output;
	}
}