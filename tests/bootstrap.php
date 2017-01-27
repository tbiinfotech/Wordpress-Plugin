<?php
# bootstrap.php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$_tests_dir = dirname( __FILE__ );
if ( !$_tests_dir ) $_tests_dir = '/tmp/wordpress-tests-lib';

require_once dirname( __FILE__ ) . '/includes/package-functions.php';

function _manually_load_plugin() {
	require_once  dirname(__DIR__).'/greenmarimba-package-grouping.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require_once $_tests_dir . '/bootstrap.php';
