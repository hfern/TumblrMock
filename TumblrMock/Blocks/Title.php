<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

class Title extends ParseBlock {
	public function render(Context &$ctx) {
		if($ctx->underpost) {
			return $this->UnderPost($ctx);
		} else {
			return $this->UnderBlog($ctx);
		}
	}
	
	function UnderBlog($ctx) {
		$title = $ctx->blog->title;
		if($title == '') {
			return '';
		}
		
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
	
	function UnderPost($ctx) {
		$title = $ctx->activepost->title;
		if($title == '') {
			return '';
		}
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
}