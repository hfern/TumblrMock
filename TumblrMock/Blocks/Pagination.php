<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\ParseBlock;

/**
 * "Rendered if there is a 'previous' or 'next' page."
 * @author Hunt
 */
class Pagination extends ParseBlock {
	public function render($ctx) {
		if (!( PreviousPage::Has($ctx) || NextPage::Has($ctx) )) {
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}