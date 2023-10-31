<?php
  class Admin extends Controller {
    public function __construct(){

      $this->adminModel=$this->model('Admins');
      $this->userModel=$this->model('User');

      if(!isLoggedIn('admin_id')){
        redirect('users/login');
      }
    }
    
    public function index(){
      $data = [
        'pop_eco_credits' => '',
      ];
     
      $this->view('admin/index', $data);
    }

    public function pop_eco_credit(){

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'pop_eco_credits' => 'True',
        'plastic_credit' =>trim($_POST['plastic']),
        'polythene_credit'=>trim($_POST['polythene']),
        'paper_credit'=>trim($_POST['paper']),
        'glass_credit'=>trim($_POST['glass']),
        'electronic_credit'=>trim($_POST['electronic']),
        'metal_credit'=>trim($_POST['metal']),
        'plastic_credit_err'=>'',
        'polythene_credit_err'=>'',
        'paper_credit_err'=>'',
        'electronic_credit_err'=>'',
        'metal_credit_err'=>'',
        'glass_credit_err'=>''
      ];

      if(empty($data['plastic_credit'])){
        $data['plastic_credit_err'] = 'Please enter name';  
       
      }

      if(empty($data['polythene_credit'])){
        $data['polythene_credit_err'] = 'Please enter NIC'; 
      }

      if(empty($data['paper_credit'])){
        $data['paper_credit_err'] = 'Please enter dob'; 
      }

      // Validate Contact no
      if(empty($data['electronic_credit'])){
        $data['electronic_credit_err'] = 'Please enter contact no';   
      }

      if(empty($data['metal_credit'])){
        $data['metal_credit_err'] = 'Please enter contact no';  
      }

      if(empty($data['glass_credit_err'])){
        $data['glass_credit_err'] = 'Please enter contact no';
      }

      if(empty($data['metal_credit_err']) &&  empty($data['plastic_credit_err']) &&  empty($data['polythene_credit_err']) &&  empty($data['glass_credit_err'])  &&  empty($data['paper_credit_err'])  &&  empty($data['electronic_credit_err']) ){
        die(sdsd);
      }
    
      $this->view('admin/index', $data);

      }
      else{
        $data = [
          'pop_eco_credits' => 'True',
          'plastic_credit' =>'',
          'polythene_credit'=>'',
          'paper_credit'=>'',
          'glass_credit'=>'',
          'electronic_credit'=>'',
          'metal_credit'=>'',
        ];
        $this->view('admin/index', $data);

      }
    }


    public function complain_customers(){
    
      $complains = $this->adminModel->get_customer_complains();
      $data = [
        'complains' => $complains
      ];
      
      $this->view('admin/complain_customers', $data);
    }

    public function center_managers(){

      $center_managers = $this->adminModel->get_center_managers();
      $data = [
        'center_managers' => $center_managers 
      ];
     
      $this->view('admin/center_managers', $data);
    }

    public function center_managers_add(){
     
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data =[
          'name' => trim($_POST['name']),
          'contact_no' => trim($_POST['contact_no']),
          'nic' => trim($_POST['nic']),
          'address' => trim($_POST['address']),
          'dob' => trim($_POST['dob']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'contact_no_err' => '',
          'nic_err' => '',
          'address_err' => '' ,
          'dob_err' => '' ,
          'email_err' => '' ,
          'password_err' => '' ,
          'complain_err' => '' ,
          'confirm_password_err'=>'' ,
          'completed'=>''   
        ];

        /*if($data['completed']=='True'){
          $data['completed']=='';
          $this->view('customers/complains', $data);
        }*/

        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        } else {
          // Check email
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Pleae enter name';
        }

        if(empty($data['nic'])){
          $data['nic_err'] = 'Pleae enter NIC';
        }

        if(empty($data['dob'])){
          $data['dob_err'] = 'Pleae enter dob';
        }

        // Validate Contact no
        if(empty($data['contact_no'])){
          $data['contact_no_err'] = 'Pleae enter contact no';
        }

        // Validate Adress
        if(empty($data['address'])){
          $data['address_err'] = 'Pleae enter adress';
        }
            // Validate Password
            if(empty($data['password'])){
              $data['password_err'] = 'Pleae enter password';
            } elseif(strlen($data['password']) < 6){
              $data['password_err'] = 'Password must be at least 6 characters';
            }
    
            // Validate Confirm Password
            if(empty($data['confirm_password'])){
              $data['confirm_password_err'] = 'Please confirm password';
            } else {
              if($data['password'] != $data['confirm_password']){
                $data['confirm_password_err'] = 'Passwords do not match';
              }
            }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['contact_no_err']) && empty($data['nic_err']) && empty($data['address_err']) && empty($data['dob_err'])){
              // Validated
               $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
             
              if($this->adminModel->register_center_manager($data)){
                $data['completed']='True';        
                $this->view('admin/center_managers_add',$data);
              } else {
                die('Something went wrong');
              }
            }
            else{
              $this->view('admin/center_managers_add', $data);
            }
            

      }
      else{
        
        $data = [
          'name' =>'',
          'contact_no' => '',
          'nic' => '',
          'address' => '',
          'dob' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'contact_no_err' => '',
          'nic_err' => '',
          'address_err' => '' ,
          'dob_err' => '' ,
          'email_err' => '' ,
          'password_err' => '' ,
          'complain_err' => '' ,
          'confirm_password_err'=>'',
          'completed'=>''  
        ];
        $this->view('admin/center_managers_add', $data);
      }
    
      }

 
      public function logout(){
      unset($_SESSION['admin_id']);
      unset($_SESSION['admin_email']);
      unset($_SESSION['admin_name']);
      session_destroy();
      redirect('users/login');
    }
   
  }