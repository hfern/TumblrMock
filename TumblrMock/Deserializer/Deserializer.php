<?php
namespace TumblrMock\Deserializer;
use TumblrMock\Post;
use TumblrMock\PhotoSize;
use TumblrMock\Photo;
use TumblrMock\Note;
use TumblrMock\Blog;

/**
 * Fills a Blog from JSON data
 * @author Hunt
 */
class Deserializer {
	/**
	 * Deserialized json object
	 * @var Object
	 */
	private $blob;
	
	/**
	 * @var Blog
	 */
	private $blog;
	
	public function __construct(){
		$this->blog = new Blog();
	}
	/**
	 * Takes a JSON string and returns a Blog instance
	 * Throws error on failure
	 * @param string $jsondata
	 */
	public function LoadJSON($jsondata){
		$this->blob = json_decode($jsondata, false, 1024);
		$blob = &$this->blob;
		$blog = &$this->blog;
		
		if ($blob->meta->status != 200) {
			throw new Exception('Blog parsing failed: non-200 status code');
		}
		
		$this->FillMeta();
		$this->FillPosts();
	}
	
	private function FillMeta() {
		$raw = &$this->blob->response->blog;
		$blog = &$this->blog;
		
		$blog->title = $raw->title;
		$blog->post_ct = $raw->posts;
		$blog->name = $raw->name;
		$blog->url = $raw->url;
		$blog->time_updated = $raw->updated;
		$blog->description = $raw->description;
		$blog->ask = $raw->ask;
		$blog->ask_anon = $raw->ask_anon;
		$blog->share_likes = $raw->share_likes;
		$blog->likes = $raw->likes;
		
		$max_pages = $blog->post_ct / count($this->blob->response->posts);
		$blog->max_pages = ceil($max_pages);
	}
	
	private function FillPosts() {
		foreach($this->blob->response->posts as &$rawpost) {
			$post = new Post();
			$post->Deserialize($rawpost);
			$this->blog->posts[] = $post;
		}
	}
	
	/**
	 * @return Blog
	 */
	public function GetBlog() {
		return $this->blog;
	}
}