<?php
namespace TumblrMock\Parser;
use TumblrMock\Parser\Meta;
use TumblrMock\Parser\Context;

/**
 * Provides an inheritable base block for other
 * tumblr template blocks
 * @author Hunt
 */
class ParseBlock {
	
	const RGX_PARAMS = '~([a-zA-Z0-9]+)\="([a-zA-Z0-9\/\.]+)"~';
	
	/**
	 * A reference back to the tree
	 * @var TemplateTree
	 */
	protected $tree;
	/**
	 * Node position within the tree
	 * denoted using tree direction arrays
	 * Ex: [0, 2, 1, 0]
	 * @var Array
	 */
	protected $position = array();
	/**
	 * Parser meta information for this node
	 * @var Meta
	 */
	public $meta;
	
	public $tagname = '';
	
	public $children = array();
	
	/**
	 * Assoc array of any params passed to the block
	 * @var Array
	 */
	public $params = array();
	
	public function __construct($name='') {
		$this->tagname = $name;
		$this->meta = new Meta();
	}
	
	public function render(Context &$ctx) {
		$str = '';
		foreach($this->children as $_ => $child) {
			$str.= $child->render($ctx);
		}
		return $str;
	}
	
	/**
	 * Sets the block's parameters
	 * @param string $paramstring
	 */
	public function setParams($paramstring) {
		if (trim($paramstring) == '') {
			return;
		}
		preg_match_all(self::RGX_PARAMS, $paramstring, $matches);
		foreach($matches[0] as $index => $_) {
			$this->params[$matches[1][$index]] = $matches[2][$index];
		}
		return;
	}
}