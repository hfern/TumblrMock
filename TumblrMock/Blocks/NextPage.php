<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock;

class NextPage extends ParseBlock {
	public function render($ctx) {
		$ctx->blog->max_pages;
		if (!self::Has($ctx)) {
			// can't have next page if cur => max
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
	/**
	 * Whether this blog render will have a Next Page
	 * Determines if this block will render as well as it's parent,
	 * block:Pagination
	 * @link Pagination
	 * @var Context $ctx
	 * @return bool
	 */
	public static function Has(Context &$ctx) {
		return $ctx->blog->max_pages > $ctx->parser->getPageNo();
	}
	
}