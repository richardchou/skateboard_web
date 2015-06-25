<!DOCTYPE html>

<html>

<head>
<title>Chou's Skateboard Shop</title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>main.css">
<script src="<?php echo base_url()?>validations.js"></script>
<script src="<?php echo base_url()?>jquery-1.10.2.min.js"></script>


<!--script type="text/javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	}
</script-->

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
			<td><a href="<?php echo base_url();?>index.php/home/login">Login </a></td>
			<td>|</td>
			<td><a href="<?php echo base_url();?>index.php/home/register">Register </a></td>
	<?php		
		}
		else {
	?>
			<td>Welcome <?php echo $_SESSION['username'] ?> </td>
			<td>|</td>
			<td><a href="<?php echo base_url();?>index.php/home/logout">Logout </a></td>
			<td>|</td>
			<td><a href="<?php echo base_url();?>index.php/home/editprofile">Edit Profile </a></td>
			<td>|</td>
			<td><a href="<?php echo base_url();?>index.php/home/vieworders">View Orders </a></td>
	<?php
		}
	?>
			<td>|</td>
			<td><a href="<?php echo base_url();?>index.php/home/shoppingcart" >Shopping Cart</a></td>
		</tr>
	</table>

	</div>

</div>

<!-- start of main -->
<div class="main"> 

<div class="leftmenu">

<?php
	require 'menu.php';
?>

</div>