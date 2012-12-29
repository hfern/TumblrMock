<?php
namespace TumblrMock;

/**
 * Represents the root level element of the ParseStack
 * @author Hunt
 *
 */
class Blog {
	/**
	 * Total maximum number of pages where the first is #1
	 * @var int
	 */
	public $max_pages = 0;
	// title of blog
	/**
	 * @var string
	 */
	public $title = '';
	public $post_ct = 0;
	// name of person?
	public $name = '';
	public $url = '';
	// unix timestamp
	public $time_updated = 0;
	public $description = '';
	/**
	 * true or false depending on whether asking is enabled for blog
	 * @var bool
	 */
	public $ask = false;
	/**
	 * Same as ask, but for anons too?
	 * @var bool
	 */
	public $ask_anon = false;
	/**
	 * Whether the blog shares likes
	 * @var bool
	 */
	public $share_likes = false;
	/**
	 * Only available if share_likes, default 0
	 * @var unknown_type
	 */
	public $likes = 0;
	/**
	 * Array of Post objects
	 * @var Array[]Post
	 */
	public $posts = array();
}