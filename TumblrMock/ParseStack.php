<?php
namespace TumblrMock;
use TumblrMock\Block;
use TumblrMock\Blog;
/**
 * Holds the series of block-level tags
 * that the parse tree is currently at.
 * The root level (level 0) elements is always
 * the Document containing all source information
 * @author Hunt
 */
class ParseStack {
	/**
	 * Holds the actual parse stack
	 * @var array
	 */
	private $Stack = array();
	
	/**
	 * Push a block-level element onto the parse stack.
	 * @param Object $Block
	 */
	public function Push(Block &$Block) {
		if ($this->IsRootSet()) {
			$Stack[] = &$Block;
		} else {
			$err = 'Root Document not set: Cannot push `%s` block onto stack.';
			throw new Exception(sprintf($err, get_class($Block)));
		}
	}
	/**
	 * Returns whether the root Blog is set
	 * @return bool
	 */
	public function IsBlogSet() {
		return count($this->Stack) > 0 ? true : false;
	}
	/**
	 * Set the root Blog. Will throw error if root already
	 * set.
	 * @param Blog $Doc
	 * @return bool new root set
	 */
	public function SetBlog(Blog &$Doc) {
		if ($this->IsBlogSet()) {
			throw new Exception('Doc Root already set: Cannot set new Root Document!');
			return false;
		}
		$this->Stack[] = $Doc;
		return true;
	}
	/**
	 * Searches, top to bottom, the parse stack
	 * for a block of x class.
	 * If input is an object, search for class of object
	 * @return Block on success, False on failure
	 */
	public function NearestBlockByClass( $element ) {
		
	}
}