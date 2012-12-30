<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

/**
 * {URL} has two contexts: Link Posts and Chat posts
 * @author Hunt
 * @TODO implement rest of contexts
 */
class Name extends ParseTag {
	public function TagRender($ctx) {
		if (!$ctx->underpost) {
			return '';
		}
		switch($ctx->activepost->type) {
			case 'link':
					return $ctx->activepost->title;
					break;
			case 'chat':
				if (!isset($ctx->extra['underline'])) {
					return '';
				}
				$name = $ctx->extra['activeline']->name;
				return $name;
				break;
		}
		
		return '';
	}
}