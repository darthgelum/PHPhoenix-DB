<?php
if(!defined('INIT')) {	
	define('ROOT',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	$loader = require_once(ROOT.'/vendor/autoload.php');
	$loader->add('PHPhoenix', ROOT.'/vendor/phphoenix/core/classes/');
	$loader->add('PHPhoenix', ROOT.'/vendor/phphoenix/db/classes/');
	$loader->add('PHPhoenix',ROOT.'/vendor/phphoenix/orm/classes/');
	define('INIT', true);
}
	