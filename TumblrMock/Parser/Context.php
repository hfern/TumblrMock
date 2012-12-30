<?php
namespace TumblrMock\Parser;
use TumblrMock\Parser\TemplateParser;
use TumblrMock\Blog;
use TumblrMock\Parser\TemplateTree;

/**
 * Context passed to ParseBlocks while rendering
 * @author Hunt
 */
class Context {
	/**
	 * Calling Stack. Combined with ParseBlock::position, can be used
	 * to locate node in tree.
	 * @var &TemplateTree
	 */
	public $tree;
	/**
	 * Blog object for help in rendering.
	 * @var &Blog
	 */
	public $blog;
	
	/**
	 * Parser object for back referencing
	 * @var TemplateParser
	 */
	public $parser;
	
	public $underpost = false;
	
	// Any data that needs to be passed-by-context
	public $extra = array();
	
	public function __construct(TemplateTree &$tree, Blog &$blog, TemplateParser &$parser) {
		$this->tree = &$tree;
		$this->blog = &$blog;
		$this->parser = &$parser;
	}
}