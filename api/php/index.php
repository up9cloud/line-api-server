<?php

error_reporting(E_ALL);
$GEN_DIR = __DIR__ . '/../../gen-php';

/*
 * load thrift classes
 */

//spl_autoload_register(function ($class) {
//	$THRIFT_ROOT = __DIR__ . '/../../lib/php/lib';
//	$class = str_replace('\\', '/', $class);
//	require_once $THRIFT_ROOT . '/' . $class . '.php';
//});

require_once __DIR__ . '/../../lib/php/lib/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/../../lib/php/lib');
$loader->register();

/*
 * load line protocol and curl client
 */

require_once $GEN_DIR . '/TalkService.php';
require_once $GEN_DIR . '/Types.php';
require_once __DIR__ . '/LineCurlClient.php';



try {

	$api = new Line\LineApi();
	$result=$api->login($email, $password);
	
} catch (TException $tx) {
	print 'TException: ' . $tx->getMessage() . "\n";
}