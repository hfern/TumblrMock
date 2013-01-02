<?php
require('../init.php');

$api = new TumblrMock\Mock\Api($ApiKey);
$blog = $api->BlogFromDomain('huntersapitesting.tumblr.com');

$parser = new TumblrMock\Parser\TemplateParser();
$parser->setBaseDirectory(__DIR__);
$parser->setBlog($blog);
// give some fake values to trigger pagination rendering
$parser->setPageNo(7);
$blog->max_pages = 43;

$parser->ParseFile('template.html');
echo $parser->CascadeRender();