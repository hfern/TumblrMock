<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class NextPage extends ParseTag {
	public function TagRender($ctx) {
		$prev = $ctx->parser->getPageNo()+1;
		return sprintf('%spage/%s', $ctx->blog->url, $prev);
	}
}