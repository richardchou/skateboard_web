<?php
	/* set cache to never expire */
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	
	session_start();
	include ("sessions.php");
	include ("functions.php");
	
	if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		write_cart();
		header("location:shoppingcart.php");
		exit();
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
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
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
<h2> Home Page </h2>

<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>
	<div id="category">
	<?php
		$category = 'Skateboard Trucks';
		$categoryid = '2';
		
		$sql = "SELECT * FROM products WHERE products.pCategory = '$category' AND products.pID NOT IN(SELECT pID FROM sales)";
		
		echo "<h3>$category</h3>";
		
		$res = mysql_query($sql);
		
		while( $row = mysql_fetch_assoc($res) ) {
			echo '<table style="margin:5px;display:inline-table;border:1px solid black;width:350px;vertical-align:top;">';
			echo '<tr style="height:155px;">';
			echo "<td style='width:155px;'><img src='$row[imagelocation]' alt='$row[imagelocation]' style='border:1px solid black;'></td>";
			echo "<td>$row[pName]<br/>
					Price: &#36;$row[pPrice] <br/>
					<input type='button' name='addcart' value='Add to Cart' onClick='addtocart($row[pID])'></td>"; 
			echo '</tr> <tr>';
			echo "<td colspan='2'></td>";
			echo '</tr> <tr style="height:100px">';
			echo "<td colspan='2' style='vertical-align:top;'>$row[pDescrip]</td> ";
			echo '</tr>';
			echo '</table>';
		}
		
		
		
	?>
	
	</div>
	<br/>
	<hr/>
	
	<div id="salestable">
	
	
	<?php
		
		$sql = "SELECT * FROM products, sales, productcategory 
				WHERE products.pID = sales.pID 
				AND products.pCategory = '$category' 
				AND productcategory.pCategoryID='$categoryid'";
		
	
		/*
		$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');

		if(!$con) {
			die ('Error connecting to server: ' . mysql_error() );
		}
		mysql_select_db('store', $con);
		*/
		
		if ( $res = mysql_query($sql) ) {
		
			echo '<h4> Special Sales </h4>';
			while( $row = mysql_fetch_assoc($res) ) {
				$number = $row[pPrice]*(1-$row[discount]);
				$saleprice = number_format($number, 2, '.', '');
				echo '<table style="margin:5px;display:inline-table;border:1px solid black;width:350px;vertical-align:top;">';
				echo '<tr style="height:155px;">';
				echo "<td style='width:155px;'><img src='$row[imagelocation]' alt='$row[imagelocation]' style='border:1px solid black;'></td>";
				echo "<td>$row[pName]<br/>
						Sale Price: &#36;$saleprice <br/>
						Sale Ends: $row[enddate] <br/> <br/>
						<input type='button' name='addcart' value='Add to Cart' onClick='addtocart($row[pID])'></td>"; 
				echo '</tr> <tr>';
				echo "<td colspan='2'></td>";
				echo '</tr> <tr style="height:100px">';
				echo "<td colspan='2' style='vertical-align:top;'>$row[pDescrip]</td> ";
				echo '</tr>';
				echo '</table>';
			}
		}
		
		mysql_close($con);	
	?>
	</div>
	

</div>

</div>

</body>