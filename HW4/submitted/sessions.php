<?php
	$inactive = 300; // inactive timer for 5 minutes
	if(isset($_SESSION['start']) ) {
		$session_life = time() - $_SESSION['start'];
		if($session_life > $inactive) {
			
			if ($_SESSION['validuser'] ) {
				echo '<div style="color:red">You have been logged out due to inactivity </div>';
				unset($_SESSION['custid']);
			}
			$_SESSION['validuser'] = false;
			
			
			
			
		}
	}
	
	$_SESSION['start'] = time();
	
	if (!isset($_SESSION['validuser'])) {
		$_SESSION['validuser'] = false;
	}
?>