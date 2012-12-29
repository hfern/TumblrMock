<?php
namespace TumblrMock\Parser;
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
	
	public $underpost = false;
	
	public function __construct(TemplateTree &$tree, Blog &$blog) {
		$this->tree = &$tree;
		$this->blog = &$blog;
	}
	
	public function Render() {
		
	}
}