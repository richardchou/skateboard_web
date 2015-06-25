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
	
	function validate (form, id) {
		var errmsg = "";
		var valid = true;
		
		//alert(valid + " " + errmsg.length + " " + form);
		if (!isValidEmail(form.email) ){
			errmsg += "Invalid e-mail. ";
		}
		
		if (!isValidStr(form.billaddress) ){
			errmsg += "Please enter a billing address. ";
		}
		
		if (!isValidStr(form.shipaddress) ){
			errmsg += "Please enter a shipping address. ";
		}
		
		if (!isValidStr(form.name) ){
			errmsg += "Please enter a name. ";
		}
		
		if (!isValidPhone(form.phone) && (form.phone.value.length > 0) ){
			errmsg += "Please enter a valid phone number. ";
		}
		
		if (!isValidCard(form.creditcard) ){
			errmsg += "Please enter a valid credit card (16 digits, no dashes). ";
		}
		
		if (errmsg.length > 0 ){
			valid = false;
			document.getElementById(id).innerHTML=errmsg;
		}
		
		//alert(valid + "\n " + errmsg + "\n " + form);
		return valid;	
	}
	
</script>

<div class="content">

	<form name="order" action="<?php echo base_url()?>index.php/home/order" method="POST" 
		onsubmit="return validate(this, 'errmsg')">

		<input type="hidden" name="ordertotal" value=<?php echo $this->store_model->get_order_total();?> />
		<div style="margin:0px auto; width:600px;" >
			<h2> Place Order </h2>
			
			<div style="margin-bottom:25px;padding:10px;width:350px;">
				
			
				<table border="0" cellpadding="2px">
					<tr><th colspan="2" style="text-align:left;">Billing Info: </th></tr>
					<tr><td>Your Name:</td><td><input type="text" name="name" /></td></tr>
					<tr><td>Billing Address:</td><td><input type="text" name="billaddress" /></td></tr>
					<tr><td>Shipping Address:</td><td><input type="text" name="shipaddress" /></td></tr>
					<tr><td>Email:</td><td><input type="text" name="email" /></td></tr>
					<tr><td>Phone (Optional):</td><td><input type="text" name="phone" /></td></tr>
					<tr><td>Credit Card Info:</td><td><input type="text" name="creditcard" maxlength="16" /></td></tr>
					<tr><td colspan="2"; style="font-weight:bold;color:#ff0000;">
						<hr/>Order Total: $<?php echo $this->store_model->get_order_total();?></td></tr>
					<tr>
						<td colspan="2";>
							<input type="submit" name="placeorder" value="Place Order" />
						</td>
					</tr>
				</table>
				
			</div>
		
			<div id="errmsg" style="color:red"> 
				<?php //if(isset($_POST['placeorder'])) { echo $errmsg; } ?>
			</div>

		
		</div>
	</form>

	<hr/>

	<form name="form1" method="POST">
		<input type="hidden" name="pid" />
		<input type="hidden" name="command" />

		<div style="margin:0px auto; width:600px;" >
		<p style="font-weight:bold;">Order Details:</p>
	    <!-- 
		<div style="padding-bottom:10px">
	    <input type="button" value="Continue Shopping" onclick="window.location='home.php'" />
	    </div> 
		-->
	    	<div style="color:#F00"><?php echo $msg?></div>
	    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
	    	<?php
			
				if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
					//echo 'Logged in shopping cart';
					$cid = $_SESSION['custid'];
					$sql="SELECT * FROM shoppingcart WHERE custid='$cid'";
					
					//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
					$result = $this->db->query($sql);
					
					if( !isset($_SESSION['cart']) ) {
						$i = 0;
						
						//while( $row = mysql_fetch_assoc($result) ) {
						foreach($result->result() as $row) {
							$_SESSION['cart'][$i]['productid'] = $row->pID;
							$_SESSION['cart'][$i]['qty'] = $row->pquantity;
							$i++;
						}
					}
					else {
						$this->store_model->delete_cart();
						$this->store_model->write_cart();
						
					}
				}
				
				if(is_array($_SESSION['cart'])){
					//echo count($_SESSION['cart']);
					echo '<tr bgcolor="#FFFFFF" style="font-weight:bold"><td style="width:60px;">Item No.</td><td style="width:200px;">Item Name</td><td style="width:50px;">Price</td><td>Qty</td><td>Cost</td><td>Options</td></tr>';
					$max=count($_SESSION['cart']);
					for($i=0;$i<$max;$i++){
						$pid=$_SESSION['cart'][$i]['productid'];
						$q=$_SESSION['cart'][$i]['qty'];
						$pname=$this->store_model->get_product_name($pid);
						if($q==0) continue;
				?>
						<tr bgcolor="#FFFFFF"><td><?php echo $i+1?></td><td><?php echo $pname?></td>
						<td>$ <?php echo $this->store_model->get_price($pid)?></td>
						<td><input type="text" name="product<?php echo $pid?>" value="<?php echo $q?>" maxlength="3" size="2" /></td>                    
						<td>$ <?php echo $this->store_model->get_price($pid)*$q?></td>
						<td><a href="javascript:del(<?php echo $pid?>)">Remove</a></td></tr>
				<?php					
					}
				?>
					<tr><td colspan="2"><b>Order Total: $<?php echo $this->store_model->get_order_total()?></b></td>
						<td colspan="4" align="right">
							<input type="button" value="Clear Cart" onclick="$this->store_model->clear_cart()">
							<input type="button" value="Update Cart" onclick="$this->store_model->update_cart()">
						</td>
					</tr>
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