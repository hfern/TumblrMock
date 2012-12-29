<?php
require('../init.php');

$api = new TumblrMock\Mock\Api($ApiKey);
$blog = $api->BlogFromDomain('huntersapitesting.tumblr.com');

$parser = new TumblrMock\Parser\TemplateParser();
$parser->setBaseDirectory(__DIR__);
$parser->ParseFile('template.html');
$stack = $parser->getStack();


$ctx = new TumblrMock\Parser\Context($stack, $blog);
$output = $stack->tree->render($ctx);

echo $output;