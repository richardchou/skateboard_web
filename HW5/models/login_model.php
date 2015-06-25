<?php
class Login_model extends CI_Model {
	/*
	public function __construct()
	{
		$this->load->database();
	}
	*/

	public function login() {

		if( isset($_POST['login']) ){
			//$custid = $_POST['custid'];
			$pw = $this->db->escape($_POST['pw']);
			$email = $this->db->escape($_POST['email']);
			$errmsg = '';
			$sql = "SELECT * FROM customers WHERE email=$email AND password=PASSWORD($pw)";
			
			//$res = mysql_query($sql);
			$res = $this->db->query($sql);


			if( $res->num_rows <= 0 ) {
			//if(!($row = mysql_fetch_assoc($res) ) ) {
				//bad login
				$errmsg = 'Invalid login.';
				//return $errmsg;
				
			}
			else {
				$row = $res->row();
				$_SESSION['validuser'] = true;
				$_SESSION['username'] = $row->firstname . ' ' . $row->lastname;
				$_SESSION['custid'] = $row->custid;
				

				if (isset($_SESSION['orderready']) AND $_SESSION['orderready']){
					$location = base_url().'index.php/home/billing';
					header("location:$location");
					//require $location;
					//header("Location: billing.php");
				}
				else{
					$location = base_url().'index.php';
					header("location:$location");
					//require $location;
					//header("Location: home.php");
				}

				$this->store_model->write_cart();
				//die();

			}

			return $errmsg;
			
		}

	}


	public function editprofile() {

		if( isset($_POST['edit']) ){
			$errmsg = '';

			$custid = $_SESSION['custid'];
			$pw = $_POST['pw'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$email = $_POST['email'];
			
			$first = true;
			$sql = "UPDATE customers SET ";
				
			//test line
			//echo "CID:$custid, pw:$pw, fn:$firstname, ln:$lastname, em:$email";
			
			if ($pw != NULL) {
				$pw = $this->db->escape($pw);
				if($first) {
					$first = false;
					$sql .= "password=PASSWORD($pw) ";
				}
				else {
					$sql .= ",password=PASSWORD($pw) ";
				}
			}
			
			if ($firstname != NULL) {
				$firstname = $this->db->escape($firstname);
				if($first) {
					$first = false;
					$sql .= "firstname=$firstname ";
				}
				else {
					$sql .= ",firstname=$firstname ";
				}
			}
			
			if ($lastname != NULL) {
				$lastname = $this->db->escape($lastname);
				if($first) {
					$first = false;
					$sql .= "lastname=$lastname ";
				}
				else {
					$sql .= ",lastname=$lastname ";
				}		
			}
			
			if ($email != NULL) {
				$email = $this->db->escape($email);
				if($first) {
					$first = false;
					$sql .= "email=$email ";
				}
				else {
					$sql .= ",email=$email ";
				}		
			}
			
			$sql .= "WHERE custid='$custid'";
			
			$sqlcheck = "SELECT * FROM customers WHERE email='$email' ";

			
			//$check = mysql_query($sqlcheck);
			$check = $this->db->query($sqlcheck);
			
			//if( $emailexist = mysql_fetch_assoc($check) ){
			if ($check->num_rows > 0) {
				$errmsg = 'Email already registered';
			}
			else {
				//$res = 	$this->db->query($sql);
				//if(!($res = mysql_query($sql)) ) {
				if ( !($res=$this->db->query($sql) ) ) {
					$errmsg = 'Error editing user. ' . $this->db->_error_message() . ' sql: '.$sql;
				}
				else {
					$errmsg = '<span style="color:#0b0;"> You have successfully edited your profile. </span>';
					$sqlname = "SELECT * FROM customers WHERE custid='$custid'";
					//$resname = mysql_query($sqlname);
					$resname = $this->db->query($sqlname);
					//$row = mysql_fetch_assoc($resname);
					if ($resname->num_rows > 0){
						$row = $resname->row();
						$_SESSION['username'] = $row->firstname. ' ' . $row->lastname;
					}
					
				}
				
			}

			return $errmsg;
			
		}

		
	}

	public function register() {

		if( isset($_POST['register']) ){
		//$custid = $_POST['custid'];
			$pw = $this->db->escape($_POST['pw']);
			$firstname = $this->db->escape($_POST['firstname']);
			$lastname = $this->db->escape($_POST['lastname']);
			$email = $this->db->escape($_POST['email']);
			$errmsg = '';
			$sql = "INSERT INTO customers(password, firstname, lastname, email) VALUES (PASSWORD($pw), $firstname, $lastname, $email )";
			$sqlcheck = "SELECT * FROM customers WHERE email=$email";
			
			//$checkemail = mysql_query($sqlcheck);
			$checkemail = $this->db->query($sqlcheck);
		
			if( $checkemail->num_rows() > 0 ){
				$errmsg = 'Email: [' . $email . '] has already been registered. Please choose a different e-mail. ';
			}
			else {
			
				//if(!($res = mysql_query($sql)) ) {
				if( !($res = $this->db->query($sql) ) ) {
					$errmsg = 'Error adding new user. ' . $this->db->_error_message();
				}
				else {
					$errmsg = '<span style="color:#0b0;"> You have successfully been registered. </span>';
				}
				
			}
			
			return $errmsg;
		}

		return '';
	}


}