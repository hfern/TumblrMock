<?php
namespace TumblrMock\Blocks;
use TumblrMock\Blocks\Pagination;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock;

class PreviousPage extends ParseBlock {
	public function render($ctx) {
		if (!self::Has($ctx)) {
			// can't have a prev page if current page is 1
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
	
	/**
	 * Whether this blog render will have a Previous Page
	 * Determines if this block will render as well as it's parent,
	 * block:Pagination
	 * @link Pagination
	 * @var Context $ctx
	 * @return bool
	 */
	public static function Has(Context &$ctx) {
		return $ctx->parser->getPageNo() > 1;
	}
}