<?php
header('Content-Type: text/html;charset=utf-8;');
use TumblrMock\Blog;
use TumblrMock\Parser\Context;
use TumblrMock\Parser\ParseBlock as Node;
use TumblrMock\Parser\TemplateTree;
use TumblrMock\Parser\TemplateParser;

require('./../TumblrMock/AutoLoader.php');
TumblrMock\AutoLoader::Register();

// Tumblr's default api key
$ApiKey = 'fuiKNFp9vQFvjLNvx4sUwti4Yb5yGutBN4Xh10LXZhhRKjWlV4';

$api = new TumblrMock\Mock\Api($ApiKey);
$blog = $api->BlogFromDomain('spiralmaccaroni.tumblr.com');

$parser = new TemplateParser();
$parser->setBaseDirectory(__DIR__);
$parser->ParseFile('input.txt');
$stack = $parser->getStack();


$ctx = new Context($stack, $blog);
$output = $stack->tree->render($ctx);

echo $output;