<?php
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include ("sessions.php");
	include ("functions.php");
	
	
	if($_REQUEST['command']=='delete' && $_REQUEST['pid']>0){
		remove_product($_REQUEST['pid']);
		if ( count($_SESSION['cart']) < 1  ) {
			unset($_SESSION['cart']);
		}
	}
	else if($_REQUEST['command']=='clear'){
		unset($_SESSION['cart']);
		delete_cart();
	}
	else if($_REQUEST['command']=='update'){
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=intval($_REQUEST['product'.$pid]);
			if($q>0 && $q<=999){
				$_SESSION['cart'][$i]['qty']=$q;
			}
			else{
				$msg='Some proudcts not updated!, quantity must be a number between 1 and 999';
			}
		}
		write_cart();
	}
	
	
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

<script type="text/javascript">
	function del(pid){
		//if(confirm('Do you really mean to delete this item')){
			document.form1.pid.value=pid;
			document.form1.command.value='delete';
			document.form1.submit();
		//}
	}
	function clear_cart(){
		//if(confirm('This will empty your shopping cart, continue?')){
			document.form1.command.value='clear';
			document.form1.submit();
		//}
	}
	function update_cart(){
		document.form1.command.value='update';
		document.form1.submit();
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

<form name="form1" method="post">
<input type="hidden" name="pid" />
<input type="hidden" name="command" />
	<div style="margin:0px auto; width:600px;" >
	<h2> Shopping Cart </h2>
    <div style="padding-bottom:10px">
    <input type="button" value="Continue Shopping" onclick="window.location='home.php'" />
    </div>
    	<div style="color:#F00"><?php echo $msg?></div>
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?php
		
			if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
				//echo 'Logged in shopping cart';
				$cid = $_SESSION['custid'];
				$sql="SELECT * FROM shoppingcart WHERE custid='$cid'";
				
				$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
				
				
				if( !isset($_SESSION['cart']) ) {
					$i = 0;
					
					while( $row = mysql_fetch_assoc($result) ) {
						$_SESSION['cart'][$i]['productid'] = $row['pID'];
						$_SESSION['cart'][$i]['qty'] = $row['pquantity'];
						$i++;
					}
				}
				else {
					
					delete_cart();
					
					//$i = count($_SESSION['cart']);
					/*
					$i=0;
					
					while( $row = mysql_fetch_assoc($result) ) {
						$_SESSION['cart'][$i]['productid'] = $row['pID'];
						$_SESSION['cart'][$i]['qty'] = $row['pquantity'];
						$i++;
					}
					*/
					write_cart();
				}
			}
			
			if(is_array($_SESSION['cart'])){
				//echo count($_SESSION['cart']);
				echo '<tr bgcolor="#FFFFFF" style="font-weight:bold"><td style="width:60px;">Item No.</td><td style="width:200px;">Item Name</td><td style="width:50px;">Price</td><td>Qty</td><td>Cost</td><td>Options</td></tr>';
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
					$q=$_SESSION['cart'][$i]['qty'];
					$pname=get_product_name($pid);
					if($q==0) continue;
			?>
					<tr bgcolor="#FFFFFF"><td><?php echo $i+1?></td><td><?php echo $pname?></td>
					<td>$ <?php echo get_price($pid)?></td>
					<td><input type="text" name="product<?php echo $pid?>" value="<?php echo $q?>" maxlength="3" size="2" /></td>                    
					<td>$ <?php echo get_price($pid)*$q?></td>
					<td><a href="javascript:del(<?php echo $pid?>)">Remove</a></td></tr>
			<?php					
				}
			?>
				<tr><td colspan="2"><b>Order Total: $<?php echo get_order_total()?></b></td><td colspan="4" align="right"><input type="button" value="Clear Cart" onclick="clear_cart()"><input type="button" value="Update Cart" onclick="update_cart()"><input type="button" value="Place Order" onclick="window.location='billing.php'"></td></tr>
			<?php
			}
			else{
				echo "<tr bgColor='#FFFFFF'><td>There are no items in your shopping cart!</td>";
			}
			
		?>
        </table>
    </div>
</form>


</div>

</div>

</body>
</html>