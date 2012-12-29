<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\ParseBlock;
use TumblrMock\Parser\Context;

/**
 * Special block for when an unknown block is found
 * in the text
 * @author Hunt
 */
class BlockNoExist extends ParseBlock {
	public function render(Context &$ctx) {
		$str = '';
		foreach($this->children as $_ => $child) {
			$str.= $child->render($ctx);
		}
		return sprintf('{block:%s}%s{/block:%s}',
			$this->tagname,
			$str,
			$this->tagname
		);
	}
}