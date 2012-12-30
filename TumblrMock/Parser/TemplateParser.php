<?php
namespace TumblrMock\Parser;

use TumblrMock\Blog;
use TumblrMock\Tags\TagNoExist;
use TumblrMock\Blocks\BlockNoExist;
use TumblrMock\Blocks\PassThru;
use TumblrMock\Parser\ParseTag;
use TumblrMock\Parser\ParseBlock;
use TumblrMock\Parser\TemplateTree;

/**
 * Turns Tumblr templates into custom TemplateTree
 * @author Hunt
 */
class TemplateParser {
	
	const ITERATION_MAX = 2000;
	const TAG_CLOSE = '/';
	
	const RGX_BLOCK = '~\{(\/)?block\:([a-zA-Z0-9\_]+)\}~';
	const BLOCK_FULL_STRING = 0;
	const BLOCK_TAG_NAME = 2;
	
	const RGX_TAG = //                                        | colon supported for color:Name
	'~\{(Plaintext|JS|JSPlaintext|URLEncoded|RGB|)([A-Za-z0-9\:]+)(\-([^\}]+))?(\})~';
		
	const TAG_FULL_STRING = 0;
	const TAG_FILTER = 1;
	const TAG_NAME = 2;
	const TAG_MOD = 4;
	
	const LOG_INFO = 1;
	const LOG_WARNING = 2;
	const LOG_ERROR = 3;
	
	// code for tag mismatch exception
	const EXC_TAG_MISMATCH = 10;
	// attempted to load a file that didn't exist
	const EXC_FILE_NOT_FOUND = 11;
	
	/**
	 * Highest directory that may be accessed and dir root
	 * @var string: path
	 */
	private $base_directory = '.';
	
	/**
	 * Number of loop iterations. Prevents any accidental errors like 
	 * infinite loops or recursive includes.
	 * @var int
	 */
	private $iterations = 0;
	
	/**
	 * The Root node
	 * @var TemplateTree
	 */
	private $stack;
	
	/**
	 * Block object
	 * Tags may reference this to extract information such as 
	 * number of pages
	 * @var Blog
	 */
	private $blog;
	
	/**
	 * Stack of Filepaths relative to parsing
	 */
	private $filestack = array();
	
	/**
	 * Associative array of file contents
	 * @var Array
	 */
	private $filetexts = array();
	
	/**
	 * The page number that's supposedly being rendered
	 * @var int
	 */
	private $pageNo = 1;
	
	public function ParseFile($filename) {
		$this->ParseBlocks($filename);
	}
	
	/**
	 * parses down the string
	 * Does not setup tree and is usually called 
	 * @param string $text
	 * @param string $filename
	 */
	public function ParseBlocks($filename) {
		// enter into requested file
		$filename = $this->LoadFile($filename);
		$this->stack->tree->meta->setBeginsAt(0, 0);
		$this->stack->tree->meta->setFileName($filename);
		
		$text = $this->filetexts[$filename];
		$length = strlen($text);
		$offset = 0;
		
		while ($offset < $length && $this->iterations <= self::ITERATION_MAX) {
			$this->iterations++;
			// find nearest match past $offset
			$matched = preg_match(self::RGX_BLOCK, $text, $match, 0, $offset);
			
			// no next match: no more matches
			if (!$matched) {
				// exit while loop
				break;
			}
			
			$matchedString = $match[self::BLOCK_FULL_STRING];
			// locate match position in text
			$matchAt = strpos($text, $matchedString, $offset);
			$matchBlockName = $match[self::BLOCK_TAG_NAME];
			
			// advance the offset marker to prevent match duplication
			$offset = $matchAt + strlen($matchedString);
			
			// check if precending fwd slash found
			$isOpeningTag = $match[1] != TemplateParser::TAG_CLOSE;
			
			if ($isOpeningTag) {
				// Opening Tag
				$hasOlderSibling = $this->stack->hasChildren();
				
				$sibtext = $hasOlderSibling ? 'true' : 'false';
				$logtxt = "@openingTag: Has older sibling? $sibtext";
				$this->Log(self::LOG_INFO, $logtxt);
				
				if ($hasOlderSibling) {
					// get text from end of older brother to beggining of new node
					$older = $this->stack->getOlderSibling();
					$older_char_end = $older->meta->pos_end_end;
					$between_text = substr(substr($text, 0, $matchAt), $older_char_end);
					$nodes = self::ParseForTags($between_text, $older_char_end+1);
					foreach($nodes as $_ => $nd) {
						$nd->meta->setFileName($this->CurrentFile());
						$this->stack->append($nd);
						$this->stack->climb();
					}
				} else {
					// first child
					// get text from beggining of parent to beggining of new node
					$parent_m = $this->stack->current()->meta;
					$between_text = substr(substr($text, 0, $matchAt), $parent_m->pos_begin_end);
					$nodes = self::ParseForTags($between_text, $parent_m->pos_begin_end+1);
					foreach($nodes as $_ => $nd) {
						$nd->meta->setFileName($this->CurrentFile());
						$this->stack->append($nd);
						$this->stack->climb();
					}
				}
				// put text between previous tag and this
				
				// create new node, append to current_node pointer, 
				// descend & select new node into current_node
				$node = $this->NewBlock($matchBlockName);
				$node->meta->setFileName($this->CurrentFile());
				$node->meta->setBeginsAt($matchAt, $offset);
				$this->stack->append($node);
			} else {
				// Closing Tag
				$this->Log(self::LOG_INFO, "At Close tag!<br/>\n");
				$current_node = $this->stack->current();
				
				if ($current_node->tagname == $matchBlockName) {
					$current_node->meta->setEndsAt($matchAt, $offset);
					
					if (count($current_node->children) > 0) {
						// has children:
						// text between end of last child and end of current tag
						$children = $current_node->children;
						$last_child = $children[count($children)-1];
						$between_text = substr($text, 0, $current_node->meta->pos_end_begin);
						$between_text = substr($between_text, $last_child->meta->pos_end_end);
						$pos_offset = $last_child->meta->pos_end_end;
						$nodes = self::ParseForTags($between_text, $pos_offset+1);
						foreach($nodes as $_ => $nd) {
							$nd->meta->setFileName($this->CurrentFile());
							$this->stack->append($nd);
							$this->stack->climb();
						}
					} else {
						// no children:
						// text between start tag and end tag
						$between_text = substr($text, 0, $current_node->meta->pos_end_begin);
						$between_text = substr($between_text, $current_node->meta->pos_begin_end);
						$offset_pos = $current_node->meta->pos_begin_end;
						$nodes = self::ParseForTags($between_text, $offset_pos+1);
						foreach($nodes as $_ => $nd) {
							$nd->meta->setFileName($this->CurrentFile());
							$this->stack->append($nd);
							$this->stack->climb();
						}
					}
					
					$this->stack->climb();
					$this->Log(self::LOG_INFO,
						"<span style='color:green;'>Tag Close: OK</span><br/>\n"
						);
					
				} else {
					var_dump($current_node);
					$errs =  'Error: Tag mismatch! Attempted to close %s (@ Line %s, Col.%s) '
							.'while %s (@ Line %s, Col.%s) still open!';
					$fmtd = sprintf($errs, $matchBlockName, 
						$this->linen($matchAt), $this->coln($matchAt),
						$current_node->tagname, 
						$this->linen($current_node->meta->pos_begin_begin), 
						$this->coln($current_node->meta->pos_begin_begin));
					throw new Exception($fmtd, self::EXC_TAG_MISMATCH);
				}
			
			} // END closing tag
			
			$this->Log(self::LOG_INFO, "<hr/>\n");
		}// END WHILE
		
		// Get text from end of last tag to EOF
		$endText = substr($text, $offset);
		$nodes = self::ParseForTags($endText, $offset);
		foreach($nodes as $_ => $nd) {
			$nd->meta->setFileName($this->CurrentFile());
			$this->stack->append($nd);
			$this->stack->climb();
		}
		
		// pop out of currently selected file
		$this->PopFileStack();
	}
	
	/**
	 * Parses only tags from text, returns array filled with /ParseBlock/s
	 * @param string $text (part of file)
	 * @param int $pos_offset offset of character positions to add to node meta
	 * @return Array[]ParseBlock
	 */
	public function ParseForTags($text, $pos_offset = 0) {
		// nothing between nodes
		if ($text == '') {
			return array();
		}
		$Nodes = array();
		
		$length = strlen($text);
		$offset = 0;
		
		while ($offset < $length && $this->iterations <= self::ITERATION_MAX) {
			$this->iterations++;
			// find nearest match past $offset
			$matched = preg_match(self::RGX_TAG, $text, $match, 0, $offset);
			
			if( !$matched ) {
				break;
			}
			
			$fulltext = $match[self::TAG_FULL_STRING];
			$filter = $match[self::TAG_FILTER];
			$tagname = $match[self::TAG_NAME];
			$mod = $match[self::TAG_MOD];
			
			$LastOffset = $offset;
			$FirstMatch = strpos($text, $fulltext, $offset);
			$EndOfMatch = $FirstMatch+strlen($fulltext);
			
			// Add text between last node and new tag
			$txt = substr($text, 0, $FirstMatch);
			$txt = substr($txt, $LastOffset);
			if ($txt != '') {
				$text_n = new PassThru(PassThru::TAG_NAME);
				$text_n->body = $txt;
				$text_n_beg = $LastOffset+$pos_offset;
				$text_n->meta->setBeginsAt($text_n_beg, $text_n_beg);
				$text_n_end = $text_n_beg+strlen($txt);
				$text_n->meta->setEndsAt($text_n_end, $text_n_end);
				$text_n->meta->setFileName($this->CurrentFile());
				$Nodes[] = $text_n;
			}
			
			$offset = $EndOfMatch;
			
			// Create the actual tag
			$Tag = $this->NewTag($tagname, $filter, $mod, $fulltext);
			$Tag->meta->setFileName($this->CurrentFile());
			$Tag->meta->setBeginsAt($FirstMatch+$pos_offset, $FirstMatch+$pos_offset);
			$Tag->meta->setEndsAt($EndOfMatch+$pos_offset, $EndOfMatch+$pos_offset);
			
			$Nodes[] = $Tag;
		}
		
		// end of last tag to EOS
		$txt = substr($text, $offset);
		$text_n = new PassThru(PassThru::TAG_NAME);
		$text_n->body = $txt;
		$text_n->meta->setBeginsAt($offset+$pos_offset, $offset+$pos_offset);
		$text_n_end = $offset+$pos_offset+strlen($txt);
		$text_n->meta->setEndsAt($text_n_end, $text_n_end);
		$text_n->meta->setFileName($this->CurrentFile());
		$Nodes[] = $text_n;
		
		return $Nodes;
	}
	
	private function NewBlock($Blockname) {
		if($this->BlockIsValid($Blockname)) {
			$name = $this->getFullBlockName($Blockname);
			return new $name($Blockname);
		}
		return new BlockNoExist($Blockname);
	}
	
	/**
	 * Set reference Blog object that this tree
	 * will be parsed to
	 * @param Blog $blog
	 */
	public function setBlog(Blog &$blog) {
		$this->blog = $blog;
	}
	
	/**
	 * Renders the entire tree into an output document
	 * @return string:html output
	 */
	public function CascadeRender() {
		$ctx = new Context($this->stack, $this->blog, $this);
		return $this->stack->tree->render($ctx);
	}
	
	/**
	 * Check if a node is valid and existant
	 */
	private function BlockIsValid($Blockname) {
		return class_exists($this->getFullBlockName($Blockname), true);
	}
	
	private function getFullBlockName($Blockname) {
		return sprintf('TumblrMock\\Blocks\\%s', $Blockname);
	}
	
	/**
	 * Create a new tag. Unlike self::NewBlock,
	 * this requires more parameters so that on 
	 * instantiation special tags may transform themselves
	 * @param string $tagname
	 * @param string $filter
	 * @param string $mod
	 * @param string $fulltagtext the full tag text including { and }
	 * @return ParseTag
	 */
	private function NewTag($Tagname, $filter, $mod, $fulltagtext) {
		$tag;
		if ($this->TagIsValid($Tagname)) {
			$tagclass = $this->getFullTagName($Tagname);
			$tag = new $tagclass($Tagname);
		} else {
			$tag = new TagNoExist($Tagname);
		}
		$tag->InitTag($this, $filter, $mod, $fulltagtext);
		return $tag;
	}
	
	private function TagIsValid($Tagname) {
		return class_exists($this->getFullTagName($Tagname), true);
	}
	
	private function getFullTagName($Tagname) {
		return sprintf('TumblrMock\\Tags\\%s', $Tagname);
	}
	
	public function GetCurrentFile() {
		if (count($this->filestack) == 0) {
			return NULL;
		} else {
			$ct = count($this->filestack);
			return $this->filestack[$ct-1];
		}
	}
	
	public function PushFileStack($filename) {
		$this->filestack[] = $filename;
	}
	
	public function PopFileStack() {
		return array_pop($this->filestack);
	}
	
	public function __construct() {
		$this->stack = new TemplateTree();
		$this->stack->tree = new ParseBlock('*docroot*');
	}
	
	private function Log($LogLevel, $Text) {
		//echo $Text;
	}
	
	/**
	 * Gets current, active file being parsed
	 * @return string
	 */
	private function CurrentFile() {
		return $this->filestack[count($this->filestack)-1];
	}
	
	/**
	 * Gets column number of given offset in a given file.
	 * Count starts at 1.
	 * @param int $offset
	 * @param string $filename
	 * @return int
	 */
	private function coln ($offset, $filename = NULL) {
		if ($filename = NULL) {
			$filename = $this->CurrentFile();
		}
		$txt = substr($this->filetexts[$filename], 0, $offset);
		$pos = strrpos($txt, "\n")+1;
		return strlen(substr($txt, $pos))+1;
	}
	
	/**
	 * Get line number of given offset. Count starts at 1.
	 * @param int $offset
	 * @param string $filename
	 * @return int
	 */
	private function linen ($offset, $filename = NULL) {
		if ($filename = NULL) {
			$filename = $this->CurrentFile();
		}
		$txt = substr($this->filetexts[$filename], 0, $offset);
		return substr_count($txt, "\n") + 1;
	}
	
	/**
	 * Loads a file's contents in and performs security checks
	 * against loading top-files. Adds file's contents to 
	 * $this->filetexts and pushes fname onto filestack
	 * Throws optional fatal error if fn not found.
	 * @param string $filename
	 * @return string: actual path
	 */
	private function LoadFile($filename) {
		$filename = realpath($this->base_directory.'/'.$filename);
		
		if ($filename && strpos($filename, $this->base_directory) !== 0) {
			//@TODO codify this error
			throw new Exception('Atempted to load file outside'
							.' of acceptable range.');	
		}
		
		if (!$filename || !file_exists($filename)) {
			//TODO clean up this exception
			throw new Exception("File:", $code, $previous);
		}
		$realpath = realpath($filename);
		$this->filetexts[$realpath] = file_get_contents($realpath);
		
		$this->PushFileStack($realpath);
		return $realpath;
	}
	
	/**
	 * Sets active and max directory. Attempts to parse files
	 * above this will fail
	 * @param string $dir
	 */
	public function setBaseDirectory($dir) {
		$path = realpath($dir);
		if ($path == false) {
			//@TODO codify exception
			throw new Exception('Directory does not exist!');
		}
		$this->base_directory = $path;
	}
	
	/**
	 * @return int
	 */
	public function getPageNo() {
		return $this->pageNo;
	}
	
	/**
	 * Set the page number that's supposedly being parsed
	 * @param int $pageNo
	 */
	public function setPageNo($pageNo) {
		$this->pageNo = $pageNo;
	}
	
	/**
	 * Returns the render stack
	 * @return TemplateTree
	 */
	public function getStack() {
		return $this->stack;
	}
	
}