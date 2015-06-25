<?php
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	
	include("sessions.php");
	
	if( (!$_SESSION['validuser']) || !isset($_SESSION['validuser']) ){
		require 'login.php';
		exit();
	}
	else {
		//echo $_SESSION['validuser'];
		//echo ' CID: ' . $_SESSION['custid'];
	}
	//$_SESSION['start'] = time();
?>


<?php
	if( isset($_POST['edit']) ){
		$custid = $_SESSION['custid'];
		$pw = $_POST['pw'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$errmsg = '';
		$first = true;
		//$sql = "INSERT INTO customers(password, firstname, lastname, email) VALUES (PASSWORD('$pw'), '$firstname', '$lastname', '$email' )";
		$sql = "UPDATE customers SET ";
		
		//test line
		//echo "CID:$custid, pw:$pw, fn:$firstname, ln:$lastname, em:$email";
		
		
		if ($pw != NULL) {
			if($first) {
				$first = false;
				$sql .= "password=PASSWORD('$pw') ";
			}
			else {
				$sql .= ",password=PASSWORD('$pw') ";
			}
		}
		
		if ($firstname != NULL) {
			if($first) {
				$first = false;
				$sql .= "firstname='$firstname' ";
			}
			else {
				$sql .= ",firstname='$firstname' ";
			}
		}
		
		if ($lastname != NULL) {
			if($first) {
				$first = false;
				$sql .= "lastname='$lastname' ";
			}
			else {
				$sql .= ",lastname='$lastname' ";
			}		
		}
		
		if ($email != NULL) {
			if($first) {
				$first = false;
				$sql .= "email='$email' ";
			}
			else {
				$sql .= ",email='$email' ";
			}		
		}
		
		$sql .= "WHERE custid='$custid'";
		
		$sqlcheck = "SELECT * FROM customers WHERE email='$email' ";
		
		$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');
		
		if(!$con) {
			die ('Error connecting to server: ' . mysql_error() );
		}
		mysql_select_db('store', $con);

		
		$check = mysql_query($sqlcheck);
		
		if( $emailexist = mysql_fetch_assoc($check) ){
			$errmsg = 'Email already registered';
		}
		else {
					
			if(!($res = mysql_query($sql)) ) {
				$errmsg = 'Error editing user. ' . mysql_error();
			}
			else {
				$errmsg = '<span style="color:#0b0;"> You have successfully edited your profile. </span>';
				$sqlname = "SELECT * FROM customers WHERE custid='$custid'";
				$resname = mysql_query($sqlname);
				$row = mysql_fetch_assoc($resname);
				$_SESSION['username'] = $row['firstname'] . ' ' . $row['lastname'];
			}
			
		}
		
		mysql_close($con);	
	}
?>


<!-- ************************************* -->

<!DOCTYPE html>

<html>

<head>
<title>Chou's Skateboard Shop</title>

<link rel="stylesheet" type="text/css" href="main.css">
<script src="validations.js"></script>

<script type="text/javascript">
	function validate (form, id) {
		var errmsg = "";
		var valid = true;
		var v1 = isValidEmail(form.email);
		var v2 = isValidStr(form.pw);
		var v3 = isValidStr(form.firstname);
		var v4 = isValidStr(form.lastname);
		
		if (!v1 && !v2 && !v3 && !v4) {
			errmsg = "Must fill out at least one field to edit profile.";
		}
		else {
		
			if (!v1 && (form.email.value.length > 0) ){
				errmsg += "Invalid e-mail format.";
			}
		
		}
		
		if (errmsg.length > 0 ){
			valid = false;
			document.getElementById(id).innerHTML=errmsg;
		}
		//alert(valid + " " + errmsg.length);
		return valid;	
	}
</script>

</head>

<body>

<div class="header">

	<div class ="title">
	<h1>
	Chou's Skateboard Shop
	</h1>
	</div>

	<div class ="loginlink">
	<table> 
		<tr>
	<?php
		if ( !isset($_SESSION['validuser']) OR !$_SESSION['validuser'] ) {
	?>
			<td><a href="login.php">Login </a></td>
			<td>|</td>
			<td><a href="register.php">Register </a></td>
	<?php		
		}
		else {
	?>
			<td>Welcome <?php echo $_SESSION['username'] ?> </td>
			<td>|</td>
			<td><a href="logout.php">Logout </a></td>
			<td>|</td>
			<td><a href="editprofile.php">Edit Profile </a></td>
			<td>|</td>
			<td><a href="vieworders.php">View Orders </a></td>
	<?php
		}
	?>
			<td>|</td>
			<td><a href="shoppingcart.php">Shopping Cart</a></td>
		</tr>
	</table>

	</div>

</div>

<div class="main">
<div class="leftmenu">
 <?php require 'menu.html'; ?>
<table>
	<tr>
		<td><a href="home.php">Home</a></td>
	</tr>
</table>

</div>

<div class="content">

<form name="edit" method="POST" action="editprofile.php" style="position:relative;top:10px;left:50px;padding-bottom:10px;">
	<h2>Edit Profile</h2>
	<table >
		<tr>
			<td>New E-mail (Login): </td>
			<td><input type="text" name="email"/> </td>
		</tr>
		<tr>
			<td>New First Name: </td>
			<td><input type="text" name="firstname"/> </td>
		</tr>
		<tr>
			<td>New Last Name: </td>
			<td><input type="text" name="lastname"/> </td>
		</tr>
		<tr>
			<td>New Password: </td>
			<td><input type="password" name="pw"/> </td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td><input type="submit" name="edit" value="Edit Profile" onClick="return validate(this.form, 'errmsg')"></td>
		</tr>
	</table>
	
	<br/>
	<div id="errmsg" style="position:relative;top:5px;left:50px;color:red"> 
	<?php 
		if(isset($_POST['edit'])) {
			echo $errmsg; 
		}
	?>
	</div>
	
</form>
</div>

</div>

</body>
</html>