<?php
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include ('sessions.php');
	include ('functions.php');
	
	
	if( isset($_POST['login']) ){
		//$custid = $_POST['custid'];
		$pw = $_POST['pw'];
		$email = $_POST['email'];
		$errmsg = '';
		$sql = "SELECT * FROM customers WHERE email='$email' AND password=PASSWORD('$pw')";
		
		$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');

		if(!$con) {
			die ('Error connecting to server: ' . mysql_error() );
		}
		mysql_select_db('store', $con);
		
		$res = mysql_query($sql);
		
		if(!($row = mysql_fetch_assoc($res) ) ) {
			//bad login
			$errmsg = 'Invalid login.';
			
		}
		else {
			$_SESSION['validuser'] = true;
			$_SESSION['username'] = $row['firstname'] . ' ' . $row['lastname'];
			$_SESSION['custid'] = $row['custid'];
			write_cart();
			if (isset($_SESSION['orderready']) AND $_SESSION['orderready']){
				header("Location: billing.php");
			}
			else{
				header("Location: home.php");
			}
			die();
			//echo '<div style="color:#009911">Successfully logged in.</div>';
			//require 'home.php';
		}
		
		//mysql_close($con);	
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
		if (!isValidStr(form.email) ){
			errmsg = "Please complete all fields before logging in.";
		}
		
		if (!isValidStr(form.pw) ){
			errmsg = "Please complete all fields before logging in. ";
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

<form name="login" method="POST" action="login.php" style="position:relative;top:10px;left:50px;padding-bottom:10px;">
	<h2>Customer Login</h2>
	<table >
		<tr>
			<td>E-mail (Login): </td>
			<td><input type="text" name="email"/> </td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="pw"/> </td>
		</tr>
		<tr>
			<td><br/><input type="submit" name="login" value="Login" onClick="return validate(this.form, 'errmsg')"></td>
		</tr>
	</table>
	
	
	<br/>
	<div id="errmsg" style="position:relative;top:5px;left:50px;color:red"> 
	<?php 
		if(isset($_POST['login'])) {
			echo $errmsg; 
		}
	?>
	</div>
	
</form>
</div>

</div>

</body>
</html>