<?php

	$_SESSION['validuser'] = false;
	//$_SESSION['start'] = time();
?>


<?php
	// if( isset($_POST['register']) ){
	// 	//$custid = $_POST['custid'];
	// 	$pw = $_POST['pw'];
	// 	$firstname = $_POST['firstname'];
	// 	$lastname = $_POST['lastname'];
	// 	$email = $_POST['email'];
	// 	$errmsg = '';
	// 	$sql = "INSERT INTO customers(password, firstname, lastname, email) VALUES (PASSWORD('$pw'), '$firstname', '$lastname', '$email' )";
	// 	$sqlcheck = "SELECT * FROM customers WHERE email='$email'";
		
	// 	$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');

	// 	if(!$con) {
	// 		die ('Error connecting to server: ' . mysql_error() );
	// 	}
	// 	mysql_select_db('store', $con);
		
	// 	$checkemail = mysql_query($sqlcheck);
		
	// 	if( $custexist = mysql_fetch_assoc($checkemail) ){
	// 		$errmsg = 'Email: [' . $email . '] has already been registered. Please choose a different e-mail. ';
	// 	}
	// 	else {
		
	// 		if(!($res = mysql_query($sql)) ) {
	// 			$errmsg = 'Error adding new user. ' . mysql_error();
	// 		}
	// 		else {
	// 			$errmsg = '<span style="color:#0b0;"> You have successfully been registered. </span>';
	// 		}
			
	// 	}
		
	// 	mysql_close($con);	
	// }
?>


<!-- ************************************* -->
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

<div class="content">

<form name="register" method="POST" action="<?php echo base_url();?>index.php/home/register" style="position:relative;top:10px;left:50px;padding-bottom:10px;">
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