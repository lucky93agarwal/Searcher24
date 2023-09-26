<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
	public $pesanerror = array("pesan" => "");
	  //Session 
	public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function dashboard()
	{
		$this->load->view('admin/_header');
		$this->load->view('admin/admin_dashboard');
		$this->load->view('admin/_footer');
	}

	public function index()
	{
		if ($this->session->userdata('login') == NULL) {
            $this->load->view('admin/login', $this->pesanerror);
        } else {
			redirect(base_url("admin/dashboard"));
        }
		
	}

	public function logout(){

		$this->session->sess_destroy();
		redirect(base_url("admin"));
	}


	public function getcompanydata(){
		
		$query = $this->db->query("SELECT * FROM `company` ");

		$d = $query->result_array();


		echo json_encode($d);
	}

	
	public function saveLocation(){
		$parameters = $this->input->get();
		// $segs = $this->uri->segment_array();
		// $jsonArray = json_decode(file_get_contents('php://input'),true); 
		
		$w_id = $parameters['w_id'];
		$lon = $parameters['lon'];
		$lat = $parameters['lat'];
		$speed = $parameters['speed'];
		$bearing = $parameters['bearing'];
		$altitude = $parameters['altitude'];
		$accuracy = $parameters['accuracy'];
		$batt = $parameters['batt'];
		$charge = $parameters['charge'];
		$mock = $parameters['mock'];
		$alarm = $parameters['alarm'];

		$data = array(
			'comapny_id'=>'1',
			'manager_id'=>'1',
			'w_id'=>'1',
			'lat'=>$lat,
			'lon'=>$lon,
			'speed'=>$speed,
			'bearing'=>$bearing,
			'altitude'=>$altitude,
			'accuracy'=>$accuracy,
			'batt'=>$batt,
			'charge'=>$charge,
			'mock'=>$mock,
			'alarm'=>$alarm,
		);
	
		$this->db->insert('location',$data);

		echo json_encode(true);

	}

	public function auth(){
        $username = $_POST['mobile'];
		$password = $_POST['pass'];
		$usertype = $_POST['user_type'];
	//	$company_name = $_POST['company_name'];

	
	 
		// echo $company;
		// $username = $this->input->post("mobile");
		// $password = $this->input->post("pass");
		// $company = $this->input->post("company_name");
		// $usertype = $this->input->post("user_type");
		if($usertype == "Select User Type"){
			$this->pesanerror = array(
				"pesan" => "Please Select User type."
			);
			$this->load->view('admin/login', $this->pesanerror);
		}else

		if($usertype == 1){

			
			$query = $this->db->query("SELECT * FROM `company` WHERE `c_mobile` = '$username' AND `c_password` = '$password' ");

			if($query->num_rows() >0){
				$d = $query->result_array();

				if($d[0]['block'] == '1'){
					$this->pesanerror = array(
						"pesan" => "Your account has been blocked please contact Searcher24 team."
					);
					$this->load->view('admin/login', $this->pesanerror);
				}else if($d[0]['maintenance'] == '1'){
					$this->pesanerror = array(
						"pesan" => "Maintenance is going on at this time please wait for sometime."
					);
					$this->load->view('admin/login', $this->pesanerror);
				}else {

					$this->session->set_userdata('login', "true");

					$this->session->set_userdata('id', $d[0]['id']);
					$this->session->set_userdata('c_name', $d[0]['c_name']);
					$this->session->set_userdata('c_logo', $d[0]['c_logo']);
					$this->session->set_userdata('c_county', $d[0]['c_county']);
					$this->session->set_userdata('c_state', $d[0]['c_state']);
					$this->session->set_userdata('c_street', $d[0]['c_street']);
					$this->session->set_userdata('c_pincode', $d[0]['c_pincode']);
					$this->session->set_userdata('c_fulladdress', $d[0]['c_fulladdress']);


					$this->session->set_userdata('c_mobile', $d[0]['c_mobile']);
					$this->session->set_userdata('c_email', $d[0]['c_email']);
					$this->session->set_userdata('c_about', $d[0]['c_about']);
					$this->session->set_userdata('c_owner_fname', $d[0]['c_owner_fname']);
					$this->session->set_userdata('c_owner_mname', $d[0]['c_owner_mname']);
					$this->session->set_userdata('c_owner_lname', $d[0]['c_owner_lname']);
					$this->session->set_userdata('c_owner_fullname', $d[0]['c_owner_fullname']);



					$this->session->set_userdata('c_owner_img', $d[0]['c_owner_img']);
					$this->session->set_userdata('c_owner_mobile', $d[0]['c_owner_mobile']);
					$this->session->set_userdata('c_owner_dob', $d[0]['c_owner_dob']);
					$this->session->set_userdata('c_owner_about', $d[0]['c_owner_about']);
					$this->session->set_userdata('c_owner_email', $d[0]['c_owner_email']);
					$this->session->set_userdata('c_password', $d[0]['c_password']);
					$this->session->set_userdata('created_at', $d[0]['created_at']);

					$this->session->set_userdata('block', $d[0]['block']);
					$this->session->set_userdata('maintenance', $d[0]['maintenance']);


					redirect(base_url("admin/dashboard"));

				}

				


			//	redirect(base_url("admin/dashboard"));
			}else {
				$this->pesanerror = array(
                    "pesan" => "Password wrong"
                );
                $this->load->view('admin/login', $this->pesanerror);
			}
			// echo $this->db->last_query();

			//row_array() से array return होता है।
			//result() से object return होता है।
			

		}else 
			if($usertype == 2){
				$query = $this->db->query("SELECT * FROM `manager` WHERE `m_mobile` = '$username' AND `m_password` = '$password'");

			if($query->num_rows() >0){
				$d = $query->result_array();

				if($d[0]['block'] == '1'){
					$this->pesanerror = array(
						"pesan" => "Your account has been blocked please contact Searcher24 team."
					);
					$this->load->view('admin/login', $this->pesanerror);
				}else if($d[0]['maintenance'] == '1'){
					$this->pesanerror = array(
						"pesan" => "Maintenance is going on at this time please wait for sometime."
					);
					$this->load->view('admin/login', $this->pesanerror);
				}else {

					$this->session->set_userdata('login', "true");

					$this->session->set_userdata('id', $d[0]['id']);
					$this->session->set_userdata('company_id', $d[0]['company_id']);
					$this->session->set_userdata('m_fname', $d[0]['m_fname']);
					$this->session->set_userdata('m_mname', $d[0]['m_mname']);
					$this->session->set_userdata('m_lname', $d[0]['m_lname']);
					$this->session->set_userdata('m_fullname', $d[0]['m_fullname']);
					$this->session->set_userdata('m_img', $d[0]['m_img']);
					$this->session->set_userdata('m_mobile', $d[0]['m_mobile']);


					$this->session->set_userdata('m_passwrod', $d[0]['m_passwrod']);
					$this->session->set_userdata('m_about', $d[0]['m_about']);
					$this->session->set_userdata('m_email', $d[0]['m_email']);
					$this->session->set_userdata('m_dob', $d[0]['m_dob']);
					$this->session->set_userdata('m_country', $d[0]['m_country']);
					$this->session->set_userdata('m_state', $d[0]['m_state']);
					$this->session->set_userdata('m_city', $d[0]['m_city']);



					$this->session->set_userdata('m_street', $d[0]['m_street']);
					$this->session->set_userdata('m_fulladdress', $d[0]['m_fulladdress']);
					$this->session->set_userdata('m_nearby', $d[0]['m_nearby']);
					$this->session->set_userdata('m_gov_issue_doc1', $d[0]['m_gov_issue_doc1']);
					$this->session->set_userdata('m_gov_issue_doc2', $d[0]['m_gov_issue_doc2']);
					$this->session->set_userdata('m_gov_issue_doc3', $d[0]['m_gov_issue_doc3']);
					$this->session->set_userdata('created_at', $d[0]['created_at']);

					$this->session->set_userdata('block', $d[0]['block']);
					$this->session->set_userdata('maintenance', $d[0]['maintenance']);


					redirect(base_url("admin/dashboard"));

				}
			}else {
				$this->pesanerror = array(
                    "pesan" => "Password wrong"
                );
                $this->load->view('admin/login', $this->pesanerror);
			}
		}


		// echo $this->db->last_query();
		
		// var_dump($username);
		// var_dump($password);
		// var_dump($company);
		// var_dump($usertype);
	}
}
