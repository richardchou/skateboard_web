<?php
class Store_model extends CI_Model {

	// function Store_model {

	// 	parent::Model();
	// }

	public function __construct()
	{
		$this->load->database();
	}


	// Functions to help with shopping cart
	// reference: http://www.qualitycodes.com/tutorial.php?articleid=25&title=Tutorial-Building-a-shopping-cart-in-PHP
	public function get_product_name($pid){
		$sql = "SELECT pName FROM products WHERE products.pID = $pid ";
		//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		$result = $this->db->query($sql);
		//$row=mysql_fetch_array($result);
		if( $result->num_rows > 0 ) {
			//return $row['pName'];
			$row = $result->row();
			return $row->pName;
		}
		
		return '';
	}
	
	public function get_price($pid){
		$sql = "SELECT pPrice, discount FROM products, sales WHERE products.pID = $pid";
		//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		//$row=mysql_fetch_array($result);
		$result = $this->db->query($sql);
		$row = $result->row();
		if( $result->num_rows > 0 ) {
			if ( $discount = $this->store_model->check_sale($pid) ){
				$number = $row->pPrice*(1-$discount);
				$saleprice = number_format($number, 2, '.', '');
				return $saleprice;
			}
			else {
				return $row->pPrice;
			}
		}
	}
	
	public function check_sale($pid){
		$sql = "SELECT * FROM sales WHERE sales.pID = $pid";
		//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
		$result = $this->db->query($sql);
		//if($row=mysql_fetch_array($result)) {
		if( $result->num_rows > 0 ) {
			//echo 'checksaletest: ' . $pid;
			$row = $result->row();
			return $row->discount;
		}
		else {
			return 0;
		}
	}
	
	public function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price = $this->store_model->get_price($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}
	
	
	public function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			if($this->store_model->product_exists($pid)) return;
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
	
	public function product_exists($pid){
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
	
	public function remove_product($pid){
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
		
			$cid = $_SESSION['custid'];
			
			$sql = "DELETE FROM shoppingcart WHERE pID='$pid' AND custid='$cid'";
			//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
			$result = $this->db->query($sql);
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
	public function write_cart() {
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] AND isset($_SESSION['cart']) ) {

			$max=count($_SESSION['cart']);
			$cid = $_SESSION['custid'];
			//if( $cidexist = mysql_fetch_assoc($check) ){ //not needed
			
			for($i=0;$i<$max;$i++){
				$qty = $_SESSION['cart'][$i]['qty'];
				$pid = $_SESSION['cart'][$i]['productid'];
				
				$price = $this->store_model->get_price($pid);
				$sqlpidcheck = "SELECT pID FROM shoppingcart WHERE custid='$cid' AND pID='$pid'";
				//$checkpid = mysql_query($sqlpidcheck) or die($sqlpidcheck."<br/><br/>".mysql_error());
				$checkpid = $this->db->query($sqlpidcheck);
				

				//if( $pidexist = mysql_fetch_assoc($checkpid) ){
				if( $checkpid->num_rows > 0 ) {
					$sql = "UPDATE shoppingcart SET pquantity='$qty', pPrice='$price' WHERE custid='$cid' AND pID='$pid'";
					//echo 'UPDATE TABLE: check:'.$sqlpidcheck.' || update:'.$sql.'<br/>';
				}
				else{
					$sql = "INSERT INTO shoppingcart (pquantity, pID, custid, pPrice) VALUES ('$qty', '$pid', '$cid', '$price')";
					//echo 'NEW INSERT: check:'.$sqlpidcheck.' || insert:'.$sql.'<br/>';
				}
				
				//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
				$result = $this->db->query($sql);

			}
			
		}
		
		return;
	}
	
	//delete entire cart in Database for specific custid
	public function delete_cart() {
		if ( isset($_SESSION['validuser']) AND $_SESSION['validuser'] ) {
		
			$cid = $_SESSION['custid'];
			
			
			$sql = "DELETE FROM shoppingcart WHERE custid='$cid'";
			//$result=mysql_query($sql) or die($sql."<br/><br/>".mysql_error());
			$result = $this->db->query($sql);
			
			//echo 'Deleting cart for customer:'.$cid;
		}
		return;
	}


}