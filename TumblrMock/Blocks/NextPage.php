<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\ParseBlock;

class NextPage extends ParseBlock {
	function render($ctx) {
		$ctx->blog->max_pages;
		if ($ctx->parser->getPageNo() >= $ctx->blog->max_pages) {
			// can't have next page if cur => max
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}