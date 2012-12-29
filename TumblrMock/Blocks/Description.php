<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Description extends ParseBlock {
	public function render(Context &$ctx) {
		if (!$ctx->blog->description || $ctx->blog->description == '') {
			return '';
		}
		$output = '';
		foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
		}
		return $output;
	}
}