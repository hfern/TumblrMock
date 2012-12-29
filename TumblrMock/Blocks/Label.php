<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock;

/**
 * Currently used in context of block:Posts(chat) > block:Lines
 * @author Hunt
 *
 */
class Label extends ParseBlock {
	public function render(Context &$ctx) {
		if ($ctx->underpost == false || $ctx->activepost->type != 'chat') {
			return '';
		}
		if (!isset($ctx->extra['activeline'])) {
			return '';
		}
		
		$label = $ctx->extra['activeline']->label;
		
		if (!$label || $label == '') {
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}