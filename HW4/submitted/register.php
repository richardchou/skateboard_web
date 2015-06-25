<?php
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include("sessions.php");
	
	$_SESSION['validuser'] = false;
	//$_SESSION['start'] = time();
?>


<?php
	if( isset($_POST['register']) ){
		//$custid = $_POST['custid'];
		$pw = $_POST['pw'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$errmsg = '';
		$sql = "INSERT INTO customers(password, firstname, lastname, email) VALUES (PASSWORD('$pw'), '$firstname', '$lastname', '$email' )";
		$sqlcheck = "SELECT * FROM customers WHERE email='$email'";
		
		$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');

		if(!$con) {
			die ('Error connecting to server: ' . mysql_error() );
		}
		mysql_select_db('store', $con);
		
		$checkemail = mysql_query($sqlcheck);
		
		if( $custexist = mysql_fetch_assoc($checkemail) ){
			$errmsg = 'Email: [' . $email . '] has already been registered. Please choose a different e-mail. ';
		}
		else {
		
			if(!($res = mysql_query($sql)) ) {
				$errmsg = 'Error adding new user. ' . mysql_error();
			}
			else {
				$errmsg = '<span style="color:#0b0;"> You have successfully been registered. </span>';
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
		if (!isValidEmail(form.email) ){
			errmsg += "Invalid e-mail. ";
		}
		
		if (!isValidStr(form.pw) ){
			errmsg += "Please enter a password. ";
		}
		
		if (!isValidStr(form.firstname) ){
			errmsg += "Please enter a first name. ";
		}
		
		if (!isValidStr(form.lastname) ){
			errmsg += "Please enter a last name. ";
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

<?php
	require 'menu.html';
?>

</div>

<div class="content">

<form name="register" method="POST" action="register.php" style="position:relative;top:10px;left:50px;padding-bottom:10px;">
	<h2>Registration</h2>
	<table >
		<tr>
			<td>E-mail (Login): </td>
			<td><input type="text" name="email"/> </td>
		</tr>
		<tr>
			<td>First Name: </td>
			<td><input type="text" name="firstname"/> </td>
		</tr>
		<tr>
			<td>Last Name: </td>
			<td><input type="text" name="lastname"/> </td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="pw"/> </td>
		</tr>
		<tr>
			<td><br/><input type="submit" name="register" value="Register" onClick="return validate(this.form, 'errmsg')"></td>
		</tr>
	</table>
	
	<br/>
	<div id="errmsg" style="position:relative;top:5px;left:50px;color:red"> 
	<?php 
		if(isset($_POST['register'])) {
			echo $errmsg; 
		}
	?>
	</div>
	
</form>
</div>

</div>

</body>
</html>