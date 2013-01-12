<?php
namespace TumblrMock\Parser;
use TumblrMock\Parser\Meta;
use TumblrMock\Parser\TemplateParser;

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
	/**
	 * Custom function.
	 * Syntax options parsed from the tag $mod
	 * Implemented later to allow more flexibility in $mods.
	 * Example: {SomeTag- @opt1=3482 @opt2=string}
	 * @var Array: Associative
	 */
	public $options = array();
	
	/**
		
	 */
	public $fulltagtext = '';
	
	final public function InitTag(TemplateParser &$parser, $filter, $mod, $fulltagtext) {
		$this->filter = $filter;
		$this->mod = $mod;
		$this->ParseOptions();
		$this->fulltagtext = $fulltagtext;
		$this->instantialize($parser);
	}
	
	/**
	 * Use this function to tranform tag or attributes
	 * at parse time
	 */
	public function instantialize(TemplateParser &$parser) {
		return;
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
	
	protected function ParseOptions() {
		$rgx = '~\s*([a-zA-Z0-9]+)\=\"([^"]+)"~';
		$matched = preg_match_all($rgx, $this->mod, $mat);
		if($matched === 0) {
			$this->options = array();
			return; // no matches
		}
		for($i = 0; $i < $matched; $i++) {
			$this->options[$mat[1][$i]] = $mat[2][$i];
		}
		return;
	}
}