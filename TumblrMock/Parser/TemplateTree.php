<?php
namespace TumblrMock\Parser;

class TemplateTree {
	public $directions = array();
	public $tree = array();
	
	protected $lastdirections = array();
	
	private function getLevelArray() {
		$level = $this->$tree;
		foreach($this->directions as $_ => $index) {
			$level = $level[$index];
		}
		return $level;
	}
	
	function append(ParseBlock $nd) {
		$this->lastdirections = $this->directions;
		$ptr = &$this->tree;
		foreach($this->directions as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		$this->directions[] = count($ptr->children);
		$nd->directions = $this->directions;
		$ptr->children[] = $nd;
	}
	
	function hasChildren() {
		$ptr = &$this->tree;
		foreach($this->directions as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		return count($ptr->children) > 0;
	}
	
	/**
	 * Returns the older sibling of the active node
	 * @return ParseBlock
	 */
	function getOlderSibling() {
		$ptr = &$this->tree;
		foreach($this->directions as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		$cur = $ptr->children;
		return $cur[count($cur)-1];
	}
	
	function climb() {
		return array_pop($this->directions);
	}
	
	function lastNode() {
		$ptr = &$this->tree;
		foreach($this->lastdirections as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		return $ptr;
	}
	
	function current() {
		$ptr = &$this->tree;
		foreach($this->directions as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		return $ptr;
	}
	function parent() {
		$short_directions = $this->directions;
		array_pop($short_directions);
		$ptr = &$this->tree;
		foreach($short_directions as $_ => $index) {
			$ptr = &$ptr->children[$index];
		}
		return $ptr;
	}
}