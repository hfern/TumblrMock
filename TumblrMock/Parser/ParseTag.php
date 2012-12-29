<?php
namespace TumblrMock\Parser;
use TumblrMock\Parser\Meta;

/**
 * Like blocks but for actual tags.
 * Treated like a ParseBlock except parsed
 * in a seperate pass.
 * 
 * It doesn't have children, either, so
 * in self::render DO NOT cycle children unless
 * it is a special tag.
 * 
 * @author Hunt
 */
class ParseTag extends ParseBlock {
	/**
	 * Any prefix on the variable that transforms it
	 * @var string
	 */
	public $filter = '';
	/**
	 * The sequence of numbers/letters/_ immedaitely the 
	 * tag name
	 * Examples:{PhotoWidth-100}
	 * 				$mod = 100
	 * 			{PhotoWidth-100sq}
	 * 				$mod = "100sq"
	 * 			{Import-css.css}
	 * 				$mod = "css.css"
	 * 			{Import-some/path/to/a/file.css}
	 * 				$mod = "some/path/to/a/file.css"
	 * 
	 * Naturally, $mod may NOT contain "}"
	 * @var mixed
	 */
	public $mod = '';
	public $options = array();
	
	final public function InitTag($filter, $mod) {
		$this->filter = $filter;
		$this->mod = $mod;
		$this->instantialize();
	}
	
	public function instantialize() {
		
	}
	
	/**
	 * ParseTag::render is reserved to allow automatic
	 * filtration. Use ParseTag::TagRender for customizing output
	 */
	final public function render(Context &$ctx) {
		//@TODO add filter support
		return $this->TagRender($ctx);
	}
	
	public function TagRender(Context &$ctx) {
		return '';
	}
}