<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\ParseBlock;

/**
 * Special block that is used for blocks of plaintext
 * Simply prints body of text, no tags
 * @author Hunt
 */
class PassThru extends ParseBlock {
	
	const TAG_NAME = '*textnode*';
	
	public $body = '';
	
	public function render() {
		return $this->body;
	}
}