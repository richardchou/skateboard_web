<?php
	/* set cache to never expire */

	
	// if ( !isset($_SESSION['validuser']) OR !$_SESSION['validuser'] ) {
	// 	//header("location:login.php");
	// 	require 'login.php';
	// 	exit();
	// }
	
?>

<div class="content">

	<div style="margin:0px auto; width:600px;" >
		<h2> Previous Orders:</h2>
	</div>

	<div style="margin:0px auto; width:600px;" >
	

		<div style="color:#F00"><?php echo $msg?></div>
		
		<?php
		
			if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
				$cid = $_SESSION['custid'];
				$sql = "SELECT DISTINCT orderid FROM orders WHERE custid=$cid";
				//$res=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
				$res = $this->db->query($sql);
				$count = 0;
				//while($row= mysql_fetch_assoc($res)){
				foreach ($res->result() as $row) {
					$count++;
		?>
					<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background:#D1D1D1" width="100%">
		<?php
						//echo 'Logged in shopping cart';
						$lastorderid = $row->orderid;
						//$cid = $_SESSION['custid'];
						$sql_ord="SELECT * FROM orders WHERE custid='$cid' AND orderid='$lastorderid'";
						$sql_oi="SELECT * FROM orderitems WHERE custid='$cid' AND orderid='$lastorderid'";
						$sql_prod="SELECT * FROM products, orderitems WHERE orderitems.custid='$cid' AND orderitems.orderid='$lastorderid' AND products.pID=orderitems.pID";
						$sql_cust="SELECT * FROM customers, orders WHERE customers.custid='$cid' AND orders.orderid='$lastorderid'";
						
						$res_ord = $this->db->query($sql_ord);
						$res_oi = $this->db->query($sql_oi);
						$res_prod = $this->db->query($sql_prod);
						$res_cust = $this->db->query($sql_cust);

						
						//if($row_cust= mysql_fetch_assoc($res_cust) ){
						//$row_cust = $res_cust->result();
						if ( $res_cust->num_rows() > 0 ) {
							$row_cust = $res_cust->row();
							//print_r($row_cust);
							echo "<tr style='font-weight:bold'>
									<td colspan='2'>Name: $row_cust->firstname $row_cust->lastname</td>
									<td colspan='3'>Order Date: $row_cust->orderdate</td></tr>";
									
							echo "<tr style='font-weight:bold'>
									<td colspan='2'>Billing Address:</td><td colspan='3'>Shipping Address:</td></tr>
									<tr><td colspan='2'>$row_cust->billaddress</td><td colspan='3'>$row_cust->shipaddress</td></tr>";
									
						}
						else {
							echo 'error: '.$this->db->_error_message();
						}
						
						echo '<tr style="background:#fff;font-weight:bold">
								<td style="width:60px;">Item No.</td>
								<td style="width:200px;">Item Name</td>
								<td style="width:50px;">Qty.</td>
								<td>Price Each</td>
								<td>Total Cost</td></tr>';
						
						$i = 0;
						//while( $row_prod = mysql_fetch_assoc($res_prod) ) {
						foreach ( $res_prod->result_array() as $row_prod ) {
							$i++;
							$number = $row_prod['pPrice']*$row_prod['pQuantity'];
							$price = number_format($number, 2, '.', '');
							echo '<tr style="background:#fff;">
									<td>'.$i.'</td>
									<td>'.$row_prod['pName'].'</td>
									<td>'.$row_prod['pQuantity'].'</td>
									<td>$'.$row_prod['pPrice'].'</td>
									<td>$'.$price.'</td></tr>';
						
						}
						//if( $row_ord= mysql_fetch_assoc($res_ord) ){
						if ($res_ord->num_rows() > 0 ) {
							$row_ord = $res_ord->row();
							echo '<tr style="font-weight:bold"><td colspan="3">Order Total: &#36;'.$row_ord->totalprice.'</td>
							<td colspan="3" style="text-align:right;">Order ID: '.$lastorderid.'</td></tr>';
						}
						
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
				
		<tr><td colspan="5"><input type="button" value="Finish" onclick="window.location='<?php echo base_url();?>index.php'"> </td></tr>
		
		
	</div>


</div>