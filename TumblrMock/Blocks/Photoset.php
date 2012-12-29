<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Photoset extends ParseBlock {
	function render($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'photoset') {
			$output = '';
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
			return $output;
		}
		return '';
	}
}