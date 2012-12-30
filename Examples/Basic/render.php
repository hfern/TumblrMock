<?php
require('../init.php');

$api = new TumblrMock\Mock\Api($ApiKey);
$blog = $api->BlogFromDomain('huntersapitesting.tumblr.com');

$parser = new TumblrMock\Parser\TemplateParser();
$parser->setBaseDirectory(__DIR__);
$parser->setBlog($blog);
$parser->setPageNo(2);
$parser->ParseFile('template.html');
echo $parser->CascadeRender();