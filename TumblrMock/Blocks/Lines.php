<?php
namespace TumblrMock\Blocks;
use TumblrMock\Parser\Context;

use TumblrMock\Parser\ParseBlock;

/**
 * Used in the chat block. Similar in spirit to block:Posts.
 * @author Hunt
 *
 */
class Lines extends ParseBlock {
	public function render(Context &$ctx) {
		if ($ctx->underpost == false || $ctx->activepost->type != 'chat') {
			return '';
		}
		// dereference variable to prevent propagation upward
		$ctx = $ctx;
		$ctx->extra['underline'] = true;
		$ctx->extra['lineindex'] = 0;
		$ctx->extra['activeline'] = NULL;
		$dialogues = $ctx->activepost->extradata['dialogue'];
		$numposts = count($dialogues);
		
		// Tumblr doesn't not return UserId's for the chat
		// participants so render meeds to extract its own.
		// Tumblr's user iteration starts at 1
		$useriter = 1;
		$users = array();
		
		
		$output = '';
		foreach($dialogues as $ctx->extra['lineindex'] => $ctx->extra['activeline']) {
			// calculate current line user
			$name = $ctx->extra['activeline']->name;
			$uid;
			if (array_key_exists($name, $users)) {
				// user already has an id
				$uid = $users[$name];
			} else {
				// create a new user
				$users[$name] = $useriter;
				$uid = $useriter;
				$useriter++;
			}
			// add uid to context
			$ctx->extra['lineuid'] = $uid;
			
			foreach($this->children as $_ => $child) {
				$output .= $child->render($ctx);
			}
		}
		return $output;
	}
}