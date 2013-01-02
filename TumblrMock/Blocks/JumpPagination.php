<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

/**
 * "Rendered for each page greater than [the current page 
 *  minus one-half length] up to [current page plus one-half length]."
 * @author Hunt
 *
 */
class JumpPagination extends ParseBlock {
	public function render(Context &$ctx) {
		// dereference variable
		$ctx = $ctx;
		
		$ctx->extra['underpagination'] = true;
		$current = $ctx->parser->getPageNo();
		$max_pages = $ctx->blog->max_pages;
		$lengthd2 = $this->getLength() / 2;
		
		// lowest theoretical page# to be rendered
		$floort = ceil($current-$lengthd2);
		// highest
		$ceilt = floor($current+$lengthd2);
		
		// actual lowest is the higher of 1 or $floort
		$floor = 1 > $floort ? 1 : $floort;
		// highest is lower of $max_pages or $ceilt
		$ceil = $max_pages < $ceilt ? $max_pages : $ceilt;
		
		$output = '';
		for($page = $floor; $page <= $ceil; $page++) {
			$ctx->extra['paginationpage'] = $page;
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
		}
		return $output;
	}
	
	/**
	 * Mnemonic for determinining if given Context is under a JumpPagination
	 * @param Context $ctx
	 * @return bool
	 */
	public static function IsUnder(Context &$ctx) {
		if (isset($ctx->extra['underpagination']) &&
			$ctx->extra['underpagination'] == true) {
			return true;
		}
		return false;
	}
	
	public function getLength() {
		if (!isset($this->params['length'])) {
			return 0;
		}
		if (!is_numeric($this->params['length'])) {
			return 0;
		}
		return intval($this->params['length']);
	}
}