<?php
namespace TumblrMock\Tags;
use TumblrMock\Blocks\JumpPagination;
use TumblrMock\Parser\ParseTag;
/**
 * Labels for chats
 * @author Hunt
 */
class PageNumber extends ParseTag {
	public function TagRender($ctx) {
		if (!JumpPagination::IsUnder($ctx)){
			return '';
		}
		return sprintf('%s', $ctx->extra['paginationpage']);
	}
}
