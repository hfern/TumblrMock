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
		if(!array_key_exists('file', $this->options)) {
			return;
		}
		$parser->getStack()->append($this); // jump into a new node structure
		$parser->ParseFile($this->options['file']);
		$parser->getStack()->climb(); // climb back out of the file root
		return;
	}
}