<?php 
	// reference: http://www.qualitycodes.com/tutorial.php?articleid=25&title=Tutorial-Building-a-shopping-cart-in-PHP
	
	function get_product_name($pid){
		$sql = "SELECT pName FROM products WHERE products.pID = $pid ";
		$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['pName'];
	}
	
	function get_price($pid){
		$sql = "SELECT pPrice, discount FROM products, sales WHERE products.pID = $pid";
		$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		
		if ( $discount = check_sale($pid) ){
			//$number = $row[pPrice]*(1-$row[discount]);
			$number = $row[pPrice]*(1-$discount);
			$saleprice = number_format($number, 2, '.', '');
			return $saleprice;
		}
		else {
			return $row['pPrice'];
		}
	}
	
	function check_sale($pid){
		$sql = "SELECT * FROM sales WHERE sales.pID = $pid";
		$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		if($row=mysql_fetch_array($result)) {
			//echo 'checksaletest: ' . $pid;
			return $row['discount'];
		}
		else {
			return 0;
		}
	}
	
	function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}
	
	
	function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			if(product_exists($pid)) return;
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
		}
		else{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
		
	}
	
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}
	
	function remove_product($pid){
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
		
			$cid = $_SESSION['custid'];
			
			$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');
			if(!$con) { die ('Error connecting to server: ' . mysql_error() ); }
			mysql_select_db('store', $con);		
			
			
			$sql = "DELETE FROM shoppingcart WHERE pID='$pid' AND custid='$cid'";
			$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
			//mysql_close($con);
			
			//echo 'loggedin remove<br/>';
			//echo 'deleting: '.$pid.'from user: '.$cid;
		}
	
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
				break;
			}
			
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
		
		return;
		
	}
	
	//writes to database to update shopping cart
	function write_cart() {
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {

			$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');
			if(!$con) { die ('Error connecting to server: ' . mysql_error() ); }
			mysql_select_db('store', $con);			
			
			$max=count($_SESSION['cart']);
			$cid = $_SESSION['custid'];
			//if( $cidexist = mysql_fetch_assoc($check) ){ //not needed
			
			for($i=0;$i<$max;$i++){
				$qty = $_SESSION['cart'][$i]['qty'];
				$pid = $_SESSION['cart'][$i]['productid'];
				
				$price = get_price($pid);
				$sqlpidcheck = "SELECT pID FROM shoppingcart WHERE custid='$cid' AND pID='$pid'";
				$checkpid = mysql_query($sqlpidcheck) or die($sqlpidcheck."<br/><br/>".mysql_error());
				

				if( $pidexist = mysql_fetch_assoc($checkpid) ){
					$sql = "UPDATE shoppingcart SET pquantity='$qty', pPrice='$price' WHERE custid='$cid' AND pID='$pid'";
					//echo 'UPDATE TABLE: check:'.$sqlpidcheck.' || update:'.$sql.'<br/>';
				}
				else{
					$sql = "INSERT INTO shoppingcart (pquantity, pID, custid, pPrice) VALUES ('$qty', '$pid', '$cid', '$price')";
					//echo 'NEW INSERT: check:'.$sqlpidcheck.' || insert:'.$sql.'<br/>';
				}
				
				$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
				//echo $sqlpidcheck.'<br/>'.$sql.'<br/>';
			}
			
			//} // not needed
			
			//mysql_close($con);
		}
		
		return;
	}
	
	//delete entire cart in DB
	function delete_cart() {
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
		
			$cid = $_SESSION['custid'];
			
			$con = mysql_connect('cs-server.usc.edu:7787', 'root', 'richard');
			if(!$con) { die ('Error connecting to server: ' . mysql_error() ); }
			mysql_select_db('store', $con);		
			
			
			$sql = "DELETE FROM shoppingcart WHERE custid='$cid'";
			$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
			//mysql_close($con);
			
			//echo 'Deleting cart for customer:'.$cid;
		}
		return;
	}
	
?>