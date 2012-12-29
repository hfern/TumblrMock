<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Caption extends ParseBlock {
	function render($ctx) {
		if ($ctx->underpost && $ctx->activepost->caption != '') {
			$output = '';
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
			return $output;
		}
		return '';
	}
}