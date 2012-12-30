<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Import extends ParseTag {
	
	private $pass1 = true;
	
	public function TagRender($ctx) {
		if ($this->pass1) {
			// skip the first parser pass
			$this->pass1 = false;
			return '';
		}
		$output = '';
		foreach($this->children as $_ => $child) {
			$output .= $child->render($ctx);
		}
		return $output;
	}
	public function instantialize($parser) {
		//var_dump($this->mod); die;
		$parser->getStack()->append($this);
		$parser->ParseFile($this->mod);
		$parser->getStack()->climb();
		return;
	}
}