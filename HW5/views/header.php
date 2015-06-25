<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include ("sessions.php");

?>

