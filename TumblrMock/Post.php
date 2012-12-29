<?php
namespace TumblrMock;
use TumblrMock\Photo;
use TumblrMock\Note;

class Post {
	/**
	 * @var string
	 */
	public $blog_name;
	/**
	 * 
	 * @var integer
	 */
	public $id;
	/**
	 * @var string
	 */
	public $post_url;
	/**
	 * @var string
	 */
	public $slug;
	/**
	 * @var string
	 */
	public $type = "";
	/**
	 * @var \DateTime
	 */
	public $date = 0;
	/**
	 * Unix TS version of date
	 * @var int
	 */
	public $timestamp = 0;
	/**
	 * State of post -> usually "published"
	 * @var string
	 */
	public $state = "";
	/**
	 * should be `html`
	 * @var string
	 */
	public $format = "";
	/**
	 * If reblogging, this will be the key in the url
	 * @var string
	 */
	public $reblog_key = "";
	/**
	 * Array of strings representing all tags this post is filed under
	 * @var Array[]String
	 */
	public $tags = array();
	/**
	 * Shortened version of this url using tumblr minifier
	 * Example: http://tmblr.co/ZtR4SxaIF4Md
	 * @var string
	 */
	public $short_url = "";
	/**
	 * @todo doc this
	 * Not sure what this is.
	 * @var Array
	 */
	public $highlighted = array();
	/**
	 * @var int
	 */
	public $note_count = 0;
	/**
	 * @var string, contains html
	 */
	public $caption = "";
	/**
	 * @todo doc this
	 * @var int
	 */
	public $photoset_layout = 0;
	/**
	 * If photoset, then array of photos
	 * @var Array[]Photo
	 */
	public $photos = array();
	/**
	 * All notes under this post
	 * @var Array[]Note
	 */
	public $notes = array();
	
	public $source_url = "";
	public $source_title = "";
	
	/**
	 * If reblogged, id of tumblr post reblogged from
	 * @var int
	 */
	public $reblogged_from_id = 0;
	/**
	 * If reblogged, url of tumblr post reblogged from.
	 * Singular post thread
	 * @var string
	 */
	public $reblogged_from_url = "";
	/**
	 * If reblogged, name tumblr post reblogged from
	 * @var string
	 */
	public $reblogged_from_name = "";
	public $reblogged_from_title = "";
	
	public $reblogged_root_url = "";
	public $reblogged_root_name = "";
	public $reblogged_root_title = "";
	
	// if type=text
	public $title = "";
	public $body = "";
	
	public function Deserialize($rawpost) {
		$post = &$this;
		foreach ( get_object_vars($rawpost) as $var => $val) {
			if (!property_exists($post, $var)) {
				continue;
			}
			$type = gettype($val);
			if ($type != 'object') {
				$post->$var = $val;
			}
		}
		$post->date = new \DateTime($rawpost->date);
		
		$post->photos = array();
		if (isset($rawpost->photos)) foreach($rawpost->photos as $i=>$photo) {
			$pic = new Photo();
			$pic->Deserialize($photo);
			$post->photos[] = $pic;
		}
		
		$post->notes = array();
		if (isset($rawpost->notes)) foreach($rawpost->notes as $i=>$rawnote) {
			$note = new Note;
			$note->Deserialize($rawnote);
			$post->notes[] = $note;
		}
	}
}