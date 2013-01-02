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
		// Stores any params in the block
		$extra = '';
		/**
		 * Reconstruct param string from the params
		 * set in the object (if any)
		 */
		foreach ($this->params as $param => $val) {
			$extra .= sprintf(' %s="%s"', $param, $val);
		}
		return sprintf('{block:%s%s}%s{/block:%s}',
			$this->tagname,
			$extra,
			$str,
			$this->tagname
		);
	}
}