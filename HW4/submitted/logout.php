<?php
	/* set cache never expire*/
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	session_unset();
	
	header("location:home.php");
?>

<div style="color:red;">
<?php 
	//echo "You have logged out." ;
?>
</div>