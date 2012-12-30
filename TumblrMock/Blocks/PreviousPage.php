<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\ParseBlock;

class PreviousPage extends ParseBlock {
	function render($ctx) {
		if ($ctx->parser->getPageNo() <= 1) {
			// can't have a prev page if current page is 1
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}