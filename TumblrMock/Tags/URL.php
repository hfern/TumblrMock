<?php
namespace TumblrMock\Tags;
use TumblrMock\Blocks\JumpPagination;

use TumblrMock\Parser\ParseTag;

/**
 * {URL} has three contexts: Pages, Link Posts, and Jump Pagination
 * @author Hunt
 * @TODO implement rest of contexts
 */
class URL extends ParseTag {
	public function TagRender($ctx) {
		if ($ctx->underpost && $ctx->activepost->type == 'link') {
			return $this->LinkPostsContext($ctx);
		}
		if (JumpPagination::IsUnder($ctx)) {
			return $this->JumpPaginationContext($ctx);
		}
		return '';
	}
	
	private function PagesContext () {
		
	}
	
	private function LinkPostsContext ($ctx) {
		return $ctx->activepost->extradata['url'];
	}
	
	private function JumpPaginationContext ($ctx) {
		if (!isset($ctx->extra['paginationpage'])) {
			return '';
		}
		$page = $ctx->extra['paginationpage'];
		return sprintf('%spage/%s', $ctx->blog->url, $page);
	}
}