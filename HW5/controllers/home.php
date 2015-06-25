<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('store_model');
	}

	public function index()
	{

		$data['title'] = "Home Page";
		
		
		$this->load->view('header');
		$this->load->model('login_model');
		$this->load->model('store_model');
		$this->load->view('navigation');
		$this->addtocart();
		$this->load->view('home', $data);
		$this->load->view('footer');
	}

	public function shoppingcart() {

		$data['title'] = "Shopping Cart";
		
		$this->load->view('header');
		$this->load->model('login_model');
		$this->load->model('store_model');
		$data['msg'] = $this->shoppingcartactions();
		
		$this->load->view('navigation');
		$this->load->view('shoppingcart', $data);
		$this->load->view('footer');

	}

	public function login(){

		
		if (!(isset($_SESSION['validuser'])) || $_SESSION['validuser'] == false) {
			$this->load->view('header');
			$this->load->model('login_model');
			$this->load->model('store_model');
			$data['errmsg'] = $this->login_model->login();
			$data['title'] = "Customer Login";
			$this->load->view('navigation');
			$this->load->view('login', $data);
			$this->load->view('footer');
		}
		else {
			$this->index();

		}


	}

	public function register(){

		

		
		$this->load->view('header');
		$this->load->model('login_model');
		$this->load->model('store_model');
		
		$data['title'] = "New Customer Registration";
		$data['errmsg'] = $this->login_model->register();

		$this->load->view('navigation');
		$this->load->view('register', $data);
		$this->load->view('footer');

	}

	public function logout() {
		
		$data['title'] = "Home Page";

		$this->load->model('store_model');
		$this->load->view('logout');

		session_cache_expire(0);
		$cache_expire = session_cache_expire();
		
		session_start();
		session_unset();
		$location = base_url().'index.php';
		header("location:$location");

	}

	public function editprofile() {

		$this->load->view('header');
		$this->load->model('store_model');
		$this->load->model('login_model');
		$this->load->view('navigation');

		if( !isset($_SESSION['validuser']) || (!$_SESSION['validuser']) ){
			$data['title'] = "Customer Login";
			$data['errmsg'] = $this->login_model->login();
			$this->load->view('login', $data);

		}
		else {
			$data['title'] = "Edit Profile";
			$data['errmsg'] = $this->login_model->editprofile();
			$this->load->view('editprofile', $data);
		}

		$this->load->view('footer');

	}

	public function billing(){

		$this->load->view('header');
		$this->load->model('store_model');
		$this->load->model('login_model');
		$this->load->view('navigation');
		$_SESSION['orderready'] = true;
		if( !isset($_SESSION['validuser']) || (!$_SESSION['validuser']) ){
			$data['title'] = "Customer Login";
			$data['errmsg'] = $this->login_model->login();
			$this->load->view('login', $data);

		}
		else {

			$data['title'] = "Place Order";
			$data['msg'] = $this->shoppingcartactions();

			$this->load->view('billing', $data);
		}

		$this->load->view('footer');
	}

	public function order() {

		$this->load->view('header');
		$this->load->model('store_model');
		$this->load->model('login_model');
		$this->load->view('navigation');
		$_SESSION['orderready'] = false;
		if( !isset($_SESSION['validuser']) || (!$_SESSION['validuser']) ){
			$data['title'] = "Customer Login";
			$data['errmsg'] = $this->login_model->login();
			$this->load->view('login', $data);

		}
		else {

			$data['title'] = "Order Summary:";
			$data['msg'] = "You have successfully placed your order";
			$this->load->view('order', $data);
		}

	}

	public function vieworders() {

		$this->load->view('header');
		$this->load->model('store_model');
		$this->load->model('login_model');
		$this->load->view('navigation');
		$_SESSION['orderready'] = false;
		if( !isset($_SESSION['validuser']) || (!$_SESSION['validuser']) ){
			$data['title'] = "Customer Login";
			$data['errmsg'] = $this->login_model->login();
			$this->load->view('login', $data);

		}
		else {

			$data['title'] = "Previous Orders:";
			$data['msg'] = "";
			$this->load->view('vieworders', $data);
		}


	}

	public function category($id) {
		//$category = 'Skateboard Deck';
		//$categoryid = '1';
		$data['categoryid'] = $id;

		$this->load->view('header');
		$this->load->model('store_model');
		$this->load->view('navigation');
		$this->addtocart();
		$this->load->view('category', $data);
		$this->load->view('footer');
	}


	function addtocart() {

		if( isset($_REQUEST['command']) && isset($_REQUEST['productid']) ) {

			if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){

				if(!isset($_SESSION['cart'])) {

					$_SESSION['cart'] = array();
				}
				$pid=$_REQUEST['productid'];
				$this->store_model->addtocart($pid,1);
				$this->store_model->write_cart();
				$location = base_url().'index.php/home/shoppingcart';
				header("location:$location");
				exit();
			}
	
		}
	}

	function shoppingcartactions() {

		$msg = '';

		if( isset($_REQUEST['command']) ) {

			if($_REQUEST['command']=='delete' && $_REQUEST['pid']>0){
				$this->store_model->remove_product($_REQUEST['pid']);
				if ( count($_SESSION['cart']) < 1  ) {
					unset($_SESSION['cart']);
				}
			}
			else if($_REQUEST['command']=='clear'){
				unset($_SESSION['cart']);
				$this->store_model->delete_cart();
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
						$msg ='Some products not updated!, quantity must be a number between 1 and 999';
					}
				}

				$this->store_model->write_cart();

			}
		
		}

		return $msg;

	}




}

