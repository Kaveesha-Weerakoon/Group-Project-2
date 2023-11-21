<?php
  class Customers extends Controller {
    public function __construct(){

      $this->customer_complain_Model=$this->model('Customer_Complain');
      $this->creditModel=$this->model('Credit_amount');
      $this->customerModel=$this->model('Customer'); 
      $this->userModel=$this->model('User');
      if(!isLoggedIn('user_id')){
        redirect('users/login');
      }
    }
    
    public function index(){
      $data = [
        'title' => 'TraversyMVC',
        'pop'=>''
      ];
     
      $this->view('customers/index', $data);
    }
    
    public function viewprofile(){
      $data = [
        'title' => 'TraversyMVC',
        'pop'=>'True',
        'name'=>'',
        'userid'=>'',
        'email'=>'',
        'contactno'=>'',
        'address'=>'',
        'city'=>''
      ];
      $id=$_SESSION['user_id']; 
      $user=$this->customerModel->get_customer($id);
      $data['name']=$_SESSION['user_name'];
      $data['userid']=$_SESSION['user_id'];
      $data['email']=$_SESSION['user_email'];
      $data['contactno']=$user->mobile_number;
      $data['address']=$user->address;
      $data['city']=$user->city;
      $this->view('customers/index', $data);
     
    }

    public function request_main(){
      $data = [
        'title' => 'TraversyMVC',
      ];
     
      $this->view('customers/request_main', $data);
    }

    public function request_completed(){
      $data = [
        'title' => 'TraversyMVC',
      ];
     
      $this->view('customers/request_completed', $data);
    }

    public function request_cancelled(){
      $data = [
        'title' => 'TraversyMVC',
      ];
     
      $this->view('customers/request_cancelled', $data);
    }

    public function history(){
      $data = [
        'title' => 'TraversyMVC',
      ];
     
      $this->view('customers/history', $data);
    }

    public function history_complains(){
      $id=$_SESSION['user_id']; 
      $complains = $this->customer_complain_Model->get_complains($id);

      $data = [
        'complains' => $complains
      ];
     
      $this->view('customers/history_complains', $data);
    }

    public function editprofile(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id=$_SESSION['user_id']; 
        $user=$this->customerModel->get_customer($id);
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'name'=>trim($_POST['name']),
        'userid'=>'',
        'profile_image_name' => $_SESSION['user_email'].'_'.$_FILES['profile_image']['name'],
        'contactno'=>trim($_POST['contactno']),
        'address'=>trim($_POST['address']),
        'city'=>trim($_POST['city']),
        'current'=>'',
        'new_pw'=>'',
        're_enter_pw'=>'',
        'current_err'=>'',
        'new_pw_err'=>'',
        're_enter_pw_err'=>'',
        'change_pw_success'=>'',
        'name_err'=>'',
        'address_err'=>'',
        'contactno_err' =>'',
        'city_err'=>'',
        'profile_err'=>'',
        'success_message'=>''
      ];

      if (empty($data['name'])) {
        $data['name_err'] = 'Please Enter a Name';
      } elseif (strlen($data['name']) > 200) {
        $data['name_err'] = 'Name should be at most 200 characters';
      }

      if (empty($data['contactno'])) {
        $data['contactno_err'] = 'Please Enter a Contact No';
      } elseif (!preg_match('/^\d{10}$/', $data['contactno'])) {
        $data['contactno_err'] = 'Contact No should be 10 digits ';
      }

      if (empty($data['city'])) {
       $data['city_err'] = 'Please Enter a City';
      } elseif (strlen($data['city']) > 200) {
        $data['city_err'] = 'City should be at most 200 characters';
       }

     if (empty($data['address'])) {
        $data['address_err'] = 'Please Enter an Address';
     } elseif (strlen($data['address']) > 200) {
        $data['address_err'] = 'Address should be at most 200 characters';
      }

      if(empty($data['name_err']) && empty($data['contactno_err']) && empty($data['city_err']) && empty($data['address_err'])){
       
        if ($_FILES['profile_image']['error'] == 4) {
           $data['profile_image_name']='';
           $this->customerModel->editprofile($data);
           $data['success_message']="Profile Details Updated Successfully";
           $data['change_pw_success']='True';

          
        } else {
          $old_image_path = 'C:/xampp/htdocs/ecoplus/public/img/img_upload/customer/' . $user->image;

          if (updateImage($old_image_path, $_FILES['profile_image']['tmp_name'], $data['profile_image_name'], '/img/img_upload/customer/')) {
            $this->customerModel->editprofile_withimg($data); 
            $data['success_message']="Profile Details Updated Successfully";
            $data['change_pw_success']='True';
            $data['profile_err'] = '';
          } else {
              $data['profile_err'] = 'Error uploading the profile image';
          }
          
        }
      } 
    
      $this->view('customers/edit_profile', $data);
     }
     else{
      $data = [
        'name'=>'',
        'userid'=>'',
        'email'=>'',
        'contactno'=>'',
        'address'=>'',
        'city'=>'',
        'current'=>'',
        'new_pw'=>'',
        're_enter_pw'=>'',
        'current_err'=>'',
        'new_pw_err'=>'',
        're_enter_pw_err'=>'',
        'change_pw_success'=>'',
        'name_err'=>'',
        'address_err'=>'',
        'contactno_err' =>'',
        'city_err'=>'',
        'profile_err'=>'',
        'success_message'=>''

      ];
      $id=$_SESSION['user_id']; 
      $user=$this->customerModel->get_customer($id);
      $data['name']=$_SESSION['user_name'];
      $data['userid']=$_SESSION['user_id'];
      $data['email']=$_SESSION['user_email'];
      $data['contactno']=$user->mobile_number;
      $data['address']=$user->address;
      $data['city']=$user->city;
     
      $this->view('customers/edit_profile', $data);
     }
    }

    public function change_password(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); $data=[
          'name'=>'',
          'userid'=>'',
          'email'=>'',
          'contactno'=>'',
          'address'=>'',
          'city'=>'',
          'current'=>trim($_POST['current']),
          'new_pw'=>trim($_POST['new_pw']),
          're_enter_pw'=>trim($_POST['re_enter_pw']),
          'current_err'=>'',
          'new_pw_err'=>'',
          're_enter_pw_err'=>'',
          'change_pw_success'=>'',
          'name_err'=>'',
          'address_err'=>'',
          'contactno_err' =>'',
          'city_err'=>'' ,
          'profile_err'=>'',
          'success_message'=>''
        ];


        $id=$_SESSION['user_id']; 
        $user=$this->customerModel->get_customer($id);
        $data['name']=$_SESSION['user_name'];
        $data['userid']=$_SESSION['user_id'];
        $data['email']=$_SESSION['user_email'];
        $data['contactno']=$user->mobile_number;
        $data['address']=$user->address;
        $data['city']=$user->city;

        if (empty($data['current'])) {
          $data['current_err'] = 'Please Enter Current Password';
      }
      
      if (empty($data['new_pw'])) {
          $data['new_pw_err'] = 'Please Enter New Password';
      } elseif (strlen($data['new_pw']) < 6) {
          $data['new_pw_err'] = 'New Password must be at least 6 characters';
      }
      
      if (empty($data['re_enter_pw'])) {
          $data['re_enter_pw_err'] = 'Please Confirm New Password';
      } elseif (strlen($data['re_enter_pw']) < 6) {
          $data['re_enter_pw_err'] = 'Confirmed Password must be at least 6 characters';
      }
  
      if(empty($data['new_pw_err']) && empty($data['current_err']) && empty($data['re_enter_pw_err'])) {
           {
              if($this->userModel->pw_check($_SESSION['user_id'],$data['current'])){
                if($data['new_pw']!=$data['re_enter_pw']){
                  $data['new_pw_err'] = 'Passwords Does not match';
                }
                else{
                  if($this->userModel->change_pw($_SESSION['user_id'],$data['re_enter_pw'])){
                    $data['success_message']="Password Changed Successfully";
                    $data['change_pw_success']='True';
                    $this->view('customers/edit_profile', $data);
                  }
                }
              }
              else{
                $data['current_err'] = 'Invalid Password';
              }
            }           
        }

        $this->view('customers/edit_profile', $data);
        }
        else{
          $data = [
            'name'=>'',
            'userid'=>'',
            'email'=>'',
            'contactno'=>'',
            'address'=>'',
            'city'=>'',
            'current'=>'',
            'new_pw'=>'',
            're_enter_pw'=>'',
            'current_err'=>'',
            'new_pw_err'=>'',
            're_enter_pw_err'=>'',
            'change_pw_success'=>'',
            'name_err'=>'',
            'address_err'=>'',
            'contactno_err' =>'',
            'city_err'=>'',
            'profile_err'=>'',
            'success_message'=>''


          ];
          $this->view('customers/editprofile', $data);

        }

    }
   
    public function complains(){
    
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data =[
          'name' => trim($_POST['name']),
          'contact_no' => trim($_POST['contact_no']),
          'region' => trim($_POST['region']),
          'subject' => trim($_POST['subject']),
          'complain' => trim($_POST['complain']),
          'name_err' => '',
          'contact_no_err' => '',
          'region_err' => '',
          'subject_err' => '' ,
          'complain_err' => '' ,
          'completed'=>''    
        ];
        
        if($data['completed']=='True'){
          $data['completed']=='';
          $this->view('customers/complains', $data);
        }

        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }
       
        // Validate Password
        if(empty($data['contact_no'])){
          $data['contact_no_err'] = 'Please enter contact no';
        }

        if(empty($data['region'])){
          $data['region_err'] = 'Please enter region';
        } 
        
        if(empty($data['subject'])){
          $data['subject_err'] = 'Please enter subject';
        }
        
        if(empty($data['complain'])){
          $data['complain_err'] = 'Please enter the complain';
        }

        if(empty($data['name_err']) && empty($data['contact_no_err']) && empty($data['region_err']) && empty($data['subject_err']) && empty($data['complain_err']) ){
          if($this->customer_complain_Model->complains($data)){
            $data['completed']="True";
            $this->view('customers/complains', $data);
           
          } else {
            die('Something went wrong');
          }
        }
        else{     
              $this->view('customers/complains', $data);         
        }
      }
      else
      $data =[
        'name' => '',
        'contact_no' => '',
        'region' => '',
        'subject' => '',
        'complain' => '',
        'name_err' => '',
        'contact_no_err' => '',
        'region_err' => '',
        'subject_err' => '' ,
        'complain_err' => ''  ,
        'completed'=>''      
      ];{
        $this->view('customers/complains', $data);
      }
     
    }

    public function request_collect(){
      $data = [
        'title' => 'TraversyMVC',
      ];
     
      $this->view('customers/request_collect', $data);
    }

    public function credit_per_waste(){
       $credit= $this->creditModel->get();
      $data = [
        'title' => 'TraversyMVC',
        'eco_credit_per'=>$credit
      ];
      $this->view('customers/credits_per_waste', $data);
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }

    public function transfer() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'customer_id' => trim($_POST['customer_id']),
            'credit_amount' => trim($_POST['credit_amount']),

            'customer_id_err' => '',
            'credit_amount_err' => '',
            'completed' => ''
        ];

        if (empty($data['customer_id'])) {
          $data['customer_id_err'] = 'Please enter customer id';
      } else {
          // Extract numeric part from the user input
          $numeric_part = preg_replace('/[^0-9]/', '', $data['customer_id']);
          // Convert extracted numeric part to an integer
          $customer_id = (int)$numeric_part;
      
          // Check if the user input matches the required format
          if (!preg_match('/^C\s*\d+(\s+\d+)*$/i', $data['customer_id'])) {
              $data['customer_id_err'] = "Customer ID should be in the format 'C xxx' or 'Cxxx'";
          } elseif (!$this->customerModel->get_customer($customer_id)) {
              $data['customer_id_err'] = 'Customer ID does not exist';
        }
      } 
        if (empty($data['credit_amount'])) {
            $data['credit_amount_err'] = 'Please enter credit amount';
        } elseif (!filter_var($data['credit_amount'], FILTER_VALIDATE_FLOAT)) {
            $data['credit_amount_err'] = 'Credit amount should be a valid number';
        }

        if (empty($data['customer_id_err']) && empty($data['credit_amount_err'])) {
            /*...*/
        } else {
            $this->view('customers/transfer', $data);
        }
      } else {
        $data = [
            'customer_id' => '',
            'credit_amount' => '',
            'customer_id_err' => '',
            'credit_amount_err' => '',
            'completed' => ''
        ];

        $this->view('customers/transfer', $data);
    }
 }

}
  ?>