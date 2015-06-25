<?php
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include ("sessions.php");
	
	if ( !isset($_SESSION['validuser']) OR !$_SESSION['validuser'] ) {
		//header("location:login.php");
		require 'login.php';
		exit();
	}
	
	include ("functions.php");
	
	//$_SESSION['orderready'] = false;
	
	
	$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');

	if(!$con) {
		die ('Error connecting to server: ' . mysql_error() );
	}
	mysql_select_db('store', $con);
	
?>

<!DOCTYPE html>

<html>

<head>
<title>Chou's Skateboard Shop</title>

<link rel="stylesheet" type="text/css" href="main.css">

<script src="validations.js"></script>

<script type="text/javascript">
	
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

		<form name="order" action="order.php" method="POST"  onSubmit="return validate(this, 'errmsg')">
			<input type="hidden" name="order"/>
			<div style="margin:0px auto; width:600px;" >
				<h2> Previous Orders:</h2>
			</div>
		</form>

		<div style="margin:0px auto; width:600px;" >
		

			<div style="color:#F00"><?php echo $msg?></div>
			
			<?php
			
				if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
					$cid = $_SESSION['custid'];
					$sql = "SELECT DISTINCT orderid FROM orders WHERE custid=$cid";
					$res=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
					$count = 0;
					while($row= mysql_fetch_assoc($res)){
						$count++;
			?>
						<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background:#D1D1D1" width="100%">
			<?php
						//echo 'Logged in shopping cart';
						$lastorderid = $row[orderid];
						//$cid = $_SESSION['custid'];
						$sql_ord="SELECT * FROM orders WHERE custid='$cid' AND orderid='$lastorderid'";
						$sql_oi="SELECT * FROM orderitems WHERE custid='$cid' AND orderid='$lastorderid'";
						$sql_prod="SELECT * FROM products, orderitems WHERE orderitems.custid='$cid' AND orderitems.orderid='$lastorderid' AND products.pID=orderitems.pID";
						$sql_cust="SELECT * FROM customers, orders WHERE customers.custid='$cid' AND orders.orderid='$lastorderid'";
						
						$res_ord=mysql_query($sql_ord) or die($sql_ord."<br/><br/>".mysql_error());
						$res_oi=mysql_query($sql_oi) or die($sql_oi."<br/><br/>".mysql_error());
						$res_prod=mysql_query($sql_prod) or die($sql_prod."<br/><br/>".mysql_error());
						$res_cust=mysql_query($sql_cust) or die($sql_cust."<br/><br/>".mysql_error());
						
						if($row_cust= mysql_fetch_assoc($res_cust) ){
							echo "<tr style='font-weight:bold'>
									<td colspan='2'>Name: $row_cust[firstname] $row_cust[lastname]</td>
									<td colspan='3'>Order Date: $row_cust[orderdate]</td></tr>";
									
							echo "<tr style='font-weight:bold'>
									<td colspan='2'>Billing Address:</td><td colspan='3'>Shipping Address:</td></tr>
									<tr><td colspan='2'>$row_cust[billaddress]</td><td colspan='3'>$row_cust[shipaddress]</td></tr>";
									
						}
						else {
							echo 'error: '.mysql_error();
						}
						
						echo '<tr style="background:#fff;font-weight:bold">
								<td style="width:60px;">Item No.</td>
								<td style="width:200px;">Item Name</td>
								<td style="width:50px;">Qty.</td>
								<td>Price Each</td>
								<td>Total Cost</td></tr>';
						
						$i = 0;
						while( $row_prod = mysql_fetch_assoc($res_prod) ) {
							$i++;
							$number = $row_prod[pPrice]*$row_prod[pQuantity];
							$price = number_format($number, 2, '.', '');
							echo '<tr style="background:#fff;">
									<td>'.$i.'</td>
									<td>'.$row_prod['pName'].'</td>
									<td>'.$row_prod['pQuantity'].'</td>
									<td>$'.$row_prod['pPrice'].'</td>
									<td>$'.$price.'</td></tr>';
						
						}
						if( $row_ord= mysql_fetch_assoc($res_ord) ){
							echo '<tr style="font-weight:bold"><td colspan="3">Order Total: &#36;'.$row_ord['totalprice'].'</td><td colspan="3" style="text-align:right;">Order ID: '.$lastorderid.'</td></tr>';
						}
						
						//unset($_SESSION['cart']);
						//delete_cart();
						//write_cart();
				?>
					</table>
					<br/><hr/><br/>
			<?php
					}
					//echo $count;
					if(!$row AND $count < 1) {	
						echo '<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background:#D1D1D1" width="100%">';
						echo "<tr><td><br/></td></tr>";
						echo "<tr style='background:#FFFFFF'><td>No Previous Orders.</td></tr>";
						echo '</table>';
						
					}
					
				}
				
				else {
					echo '<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background:#D1D1D1" width="100%">';
					echo "<tr><td><br/></td></tr>";
					echo "<tr style='background:#FFFFFF'><td>Error.</td></tr>";
					echo '</table>';
				}
			?>
					
			<tr><td colspan="5"><input type="button" value="Finish" onclick="window.location='home.php'"> </td></tr>
			
			
		</div>


	</div>

</div>

</body>
</html>

<?php
	mysql_close($con);
?>