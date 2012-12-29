<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Link extends ParseBlock {
	function render($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'link') {
			$output = '';
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
			return $output;
		}
		return '';
	}
}