<div class="content">

	<div style="margin:0px auto; width:600px;" >
		<h2> Order Summary:</h2>
		
		<?php
			$name = $this->db->escape($_POST['name']);
			$bill = $this->db->escape($_POST['billaddress']);
			$ship = $this->db->escape($_POST['shipaddress']);
			$phone = $this->db->escape($_POST['phone']);
			$card = $this->db->escape($_POST['creditcard']);
			$totalprice = $this->db->escape($_POST['ordertotal']);
			$cid = $_SESSION['custid'];
			
			$sql = "INSERT INTO orders (custid, orderdate, totalprice, billaddress, shipaddress, creditcard)
					VALUES ($cid, CURDATE(), $totalprice, $bill, $ship, $card)";
					
			//$res=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
			
			//if( !($res = mysql_query($sql) ) ) {
			if( !($this->db->query($sql) ) ) {
				//die($sql."<br/><br/>".mysql_error());
				die($sql."<br/><br/>".$this->db->_error_message() );
			}
			else {
				$lastorderid = $this->db->insert_id(); //mysql_insert_id();
				$sql_last = "SELECT LAST_INSERT_ID() FROM orders WHERE custid = '$cid'";// AND orderdate='$date' ";
				//$res_last=mysql_query($sql_last) or die($sql_last."<br/><br/>".mysql_error());
				$res_last = $this->db->query($sql_last);
				//if($row_last=mysql_fetch_array($res_last)){
				if ($res_last->num_rows() > 0) {
					
					$sql_cart="SELECT * FROM shoppingcart WHERE custid='$cid'";
					//$res_cart = mysql_query($sql_cart) or die($sql_cart."<br/><br/>".mysql_error());
					$res_cart = $this->db->query($sql_cart);
					
					//while( $row_cart = mysql_fetch_assoc($res_cart) ) {
					foreach( $res_cart->result_array() as $row_cart) {
						$sql_items = "INSERT INTO orderitems (orderid, custid, pID, pQuantity, pPrice)
									VALUES($lastorderid, $row_cart[custid], $row_cart[pID], $row_cart[pquantity], $row_cart[pPrice])";
						//$res_items=mysql_query($sql_items) or die($sql_items."<br/><br/>".mysql_error());
						$res_items = $this->db->query($sql_items) or die( $sql_items.'<br/><br/>'.$this->db->_error_message() );
					}
					//echo 'testing';
	
				}
				
			}

		?>
	
	</div>

	<div style="margin:0px auto; width:600px;" >
	

		<div style="color:#00f000"><?php echo $msg?></div>
		<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
		<?php
		
			if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] AND isset($_SESSION['cart'])) {
				//echo 'Logged in shopping cart';
				$cid = $_SESSION['custid'];
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
					echo '<tr style="font-weight:bold"><td colspan="3">Order Total: &#36;'.$row_ord->totalprice.'</td><td colspan="3" style="text-align:right;">Order ID: '.$lastorderid.'</td></tr>';
				}
				
				unset($_SESSION['cart']);
				$this->store_model->delete_cart();
				//$this->store_model->write_cart();
				
			}
			else {
				echo "<tr><td><br/></td></tr>";
				echo "<tr style='background:#FFFFFF'><td>No Previous Orders.</td></tr>";
			}
		?>
				
		<tr><td colspan="5">
			<form>
				<input type="button" value="Finish" onclick="window.location='<?php echo base_url();?>index.php'"> 
			</form>
		</td></tr>
		
		</table>
	</div>


</div>