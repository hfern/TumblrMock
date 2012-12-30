<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock;

/**
 * Distinct from ContentSource. This is only for quotes.
 * @author Hunt
 *
 */
class Source extends ParseBlock {
	function render($ctx) {
		if (!$ctx->underpost && $ctx->activepost->type == 'quote') {
			return '';
		}
		if (!isset($ctx->activepost->extradata['source'])) {
			return '';
		}
		if ($ctx->activepost->extradata['source'] == '') {
			return '';
		}
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}