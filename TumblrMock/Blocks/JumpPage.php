<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock;

/**
 * Context of block:Pagination } ..? } block:CurrentPage
 * Solely for JumpPagination.
 * @author Hunt
 *
 */
class JumpPage extends ParseBlock {
	public function render(Context &$ctx) {
		// must be under JumpPagination
		if (!JumpPagination::IsUnder($ctx)) {
			return '';
		}
		// must NOT be current page number
		if ($ctx->extra['paginationpage'] == $ctx->parser->getPageNo()) {
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}