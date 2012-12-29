<?php
namespace TumblrMock\Tags;
use TumblrMock\Parser\ParseTag;

class Favicon extends ParseTag {
	public function TagRender($ctx) {
		// Tumblr doesn't give out a favicon in its api response
		// But it does have a default icon used on blogs where
		// the favicon has not been set.
		return 'http://assets.tumblr.com/images/default_avatar_16.gif';
	}
}