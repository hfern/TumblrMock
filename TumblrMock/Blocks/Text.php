<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Text extends ParseBlock {
	function render($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'text') {
			$output = '';
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
			return $output;
		}
		return '';
	}
}