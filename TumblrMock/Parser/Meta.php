<?php
namespace TumblrMock\Parser;

/**
 * Keeps parser meta information for a given node
 * Allows pinpointing nodes back to their file/root
 * @author Hunt
 */
class Meta {
	/**
	 * File path that this node is located in
	 * @var unknown_type
	 */
	public $filename;
	
	/**
	 * Character offsets of the tag names
	 * @var int
	 */
	public $pos_begin_begin = 0;
	public $pos_begin_end = 0;
	
	public $pos_end_begin;
	public $pos_end_end;
	
	public $tag_open = true;
	
	public function setBeginsAt($pos_begin, $pos_end) {
		$this->pos_begin_begin = $pos_begin;
		$this->pos_begin_end = $pos_end;
	}
	
	public function setEndsAt($pos_begin, $pos_end) {
		$this->pos_end_begin = $pos_begin;
		$this->pos_end_end = $pos_end;
	}
	
	public function setFileName($fname) {
		$this->filename = $fname;
	}
	
}