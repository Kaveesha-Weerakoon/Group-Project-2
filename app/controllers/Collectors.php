<?php
  class Collectors extends Controller {
    public function __construct(){

      $this->collector_complain_Model=$this->model('Collector_Complain');
      $this->collectorModel=$this->model('Collector');
      $this->collector_assistantModel=$this->model('Collector_Assistant');
      $this->creditModel=$this->model('Credit_amount');
      $this->collector_complain_Model=$this->model('Collector_Complain');
      $this->userModel=$this->model('User');
      $this->Request_Model=$this->model('Request');
      $this->Customer_Credit_Model=$this->model('Customer_Credit');
      $this->Collect_Garbage_Model=$this->model('Collect_Garbage');
      $this->customerModel=$this->model('Customer'); 
      $this->centerModel=$this->model('Center');

      if(!isLoggedIn('collector_id')){
        redirect('users/login');
      }
    }
    
    public function index(){
     
      $collector=$this->collectorModel->get_collector( $_SESSION['collector_id'] );
      $center=$this->centerModel->findCenterbyRegion($collector->center_name);
      $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
      $jsonData = json_encode($assinged_Requests);
      $assinged_Requests_count=count($this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] ));
      $cancel_Requests_count=count($this->Request_Model->get_cancelled_request_by_collector( $_SESSION['collector_id'] ));
      $completed_requests=count($this->Collect_Garbage_Model->get_complete_request($_SESSION['collector_id']));
      $total_garbage=$this->Collect_Garbage_Model->get_completed_garbage_totals_by_collector($_SESSION['collector_id']);
      $credit= $this->creditModel->get();
      $req_completed_history = $this->Collect_Garbage_Model->get_complete_request_cus($_SESSION['collector_id']); 
      $Notifications = $this->customerModel->get_Notification($_SESSION['collector_id']);
      

      if ($completed_requests > 0) {
        $percentage_completed = json_encode(($completed_requests / ($assinged_Requests_count+$completed_requests+$cancel_Requests_count)) * 100);
         } else {
          $percentage_completed =json_encode(0);
     } 
   
     $json_Total_Garbage = json_encode($total_garbage);
      $data = [
        'collector' =>$collector,
        'assinged_Requests_count' => $assinged_Requests_count,
        'assigned_requests' => $jsonData,
        'eco_credit_per'=>$credit,
        'req_completed_history' =>$req_completed_history,
        'percentage'=> $percentage_completed,
        'total_garbage'=> $json_Total_Garbage,
        'notification'=> $Notifications,
        'pop'=>''
         
        ];

        
        $data['lattitude']=$center->lat;
        $data['longitude']=$center->longi;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          $Notifications1 = $this->customerModel->view_Notification($_SESSION['collector_id']);
          $Notifications2 = $this->customerModel->get_Notification($_SESSION['collector_id']);
          $data['notification']=  $Notifications2 ;
           $this->view('collectors/index', $data);
  
        }
      $this->view('collectors/index', $data);
    }
    
    public function logout(){
      unset($_SESSION['collector_id']);
      unset($_SESSION['collector_email']);
      unset($_SESSION['collector_name']);
      unset($_SESSION['center_id']);
      unset($_SESSION['center']);
      unset($_SESSION['collector_profile']);
      session_destroy();
      redirect('users/login');
    }

    public function collector_assistants(){

      $collector_assistants = $this->collector_assistantModel->get_collector_assistants($_SESSION['collector_id']);
      $data = [
        'collector_assistants' => $collector_assistants,
        'assistant_id'=>'',
        'confirm_delete' =>'',
        'delete_success'=>'',
        'confirm_update' => '',
        'update_success'=>''
        
      ];
     
      $this->view('collectors/collector_assistants', $data);
    }

    public function collector_assistants_add(){
     
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
               'name' =>trim($_POST['name']),
               'nic' => trim($_POST['nic']),
               'dob'=>trim($_POST['dob']),
               'contact_no'=>trim($_POST['contact_no']),
               'address' =>trim($_POST['address']),
               'registered'=>'',
  
               'name_err' => '',
               'nic_err' => '',
               'dob_err'=>'',
               'contact_no_err'=>'',
               'address_err' =>''       
        ];

        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        } elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';

        }

        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->collector_assistantModel->getCollectorAssisByNIC($data['nic'])){
          $data['nic_err'] = 'NIC already exists';
        }

        //validate DOB
        if(empty($data['dob'])){
          $data['dob_err'] = 'Please enter dob';
        }

        // Validate Contact no
        if(empty($data['contact_no'])){
          $data['contact_no_err'] = 'Please enter contact no';
        }elseif (!preg_match('/^[0-9]{10}$/', $data['contact_no'])) {
          $data['contact_no_err'] = 'Please enter a valid contact number';
        }

        // Validate Address
        if(empty($data['address'])){
          $data['address_err'] = 'Please enter adress';
        } elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }

        if(empty($data['address_err']) && empty($data['contact_no_err']) && empty($data['dob_err']) && empty($data['nic_err']) && empty($data['name_err']) ){
          if($this->collector_assistantModel->add_collector_assistants($data)){
            $data['registered']='True';        
            $this->view('collectors/collector_assistants_add',$data);
          } else {
            die('Something went wrong');
          }
        }
        else{
          $this->view('collectors/collector_assistants_add', $data);

        }
      
      }
      else{
        $data = [
          'name' => '',
          'nic' => '',
          'dob'=>'',
          'contact_no'=>'',
          'address' =>'',
          'registered'=>'',
          'name_err' => '',
          'nic_err' => '',
          'dob_err'=>'',
          'contact_no_err'=>'',
          'address_err' =>''
        ];
        
        $this->view('collectors/collector_assistants_add', $data);
      }

      
    }

    public function collector_assistants_delete_confirm($assisId){
      $collector_assistants = $this->collector_assistantModel->get_collector_assistants($_SESSION['collector_id']);
      $data = [
        'collector_assistants' => $collector_assistants,
        'confirm_delete' =>'True',
        'collector_assistant_id'=>$assisId,
        'delete_success'=>'',
        'confirm_update' =>'',
        'update_success'=> ''
      ];
     
      $this->view('collectors/collector_assistants', $data);
    } 

    public function collector_assistants_delete($assisId){
      $collector_assistants = $this->collector_assistantModel->get_collector_assistants($_SESSION['collector_id']);
      $data = [
        'collector_assistants' => $collector_assistants,
        'delete_success'=>'',
        'confirm_delete' =>'',
        'confirm_update' =>'',
        'update_success'=> ''

      ];
      $collector_assistant = $this->collector_assistantModel->getCollectorAssisById($assisId);
      if(empty($collector_assistant)){
        die('Center worker not found');
      }
      else{
        if($this->collector_assistantModel->delete_collector_assistants($assisId)){
          $data['delete_success']='True';
          $this->view('collectors/collector_assistants',$data);
        }
        else{
          die('Something went wrong');
        }

      }
    }

    public function complains(){
    
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data =[
          'name' => trim($_POST['name']),
          'contact_no' => trim($_POST['contact_no']),
          'region' => '',
          'subject' => trim($_POST['subject']),
          'complain' => trim($_POST['complain']),
          'name_err' => '',
          'contact_no_err' => '',
          'region_err' => '',
          'subject_err' => '' ,
          'complain_err' => '' ,
          'completed'=>  '',
          'center_id'=>''
        ];
        
        /*if($data['completed']=='True'){
          $data['completed']=='';
          $this->view('collectors/complains', $data);
        }*/

        if(empty($data['name'])){
          $data['name_err'] = 'Please enter the name';
        }
       
        // Validate Password
        if (empty($data['contact_no'])) {
          $data['contact_no_err'] = 'Please enter the contact no';
      } elseif (!preg_match('/^\d{10}$/', $data['contact_no'])) {
          $data['contact_no_err'] = 'Invalid contact no';
      }
          
        if(empty($data['subject'])){
          $data['subject_err'] = 'Please enter subject';
        }
        
        if(empty($data['complain'])){
          $data['complain_err'] = 'Please enter complain';
        }

        if(empty($data['name_err']) && empty($data['contact_no_err']) && empty($data['region_err']) && empty($data['subject_err']) && empty($data['complain_err']) ){
          
          $data['center_id']=$_SESSION['center_id'];
          $data['region']=$_SESSION['center'];
          if($this->collector_complain_Model->complains($data)){
            $data['completed']="True";
            $this->view('collectors/complains', $data);
           
           
          } else {
            die('Something went wrong');
          }
        }
        else{     
              $this->view('collectors/complains', $data);         
        }
      }
      else{
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
      ];

        $id=$_SESSION['collector_id']; 
        $user=$this->collectorModel->get_collector($id);
        $data['contact_no']=$user->contact_no;
        $data['name'] =$_SESSION['collector_name'];
        $this->view('collectors/complains', $data);
      }
     
    }

    public function complains_history(){
      $id=$_SESSION['collector_id']; 
      $complains = $this->collector_complain_Model->get_complains_by_collector($id);

      $data = [
        'complains' => $complains
      ];
     
      $this->view('collectors/complains_history', $data);
    }

    public function collector_assistants_update($assisId){
  
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $collector_assistants = $this->collector_assistantModel->get_collector_assistants($_SESSION['collector_id']);
            $data = [
               'collector_assistants' => $collector_assistants,
               'id' => $assisId,
               'name' =>trim($_POST['name']),
               'nic' => trim($_POST['nic']),
               'dob'=>trim($_POST['dob']),
               'contact_no'=>trim($_POST['contact_no']),
               'address' =>trim($_POST['address']),
               'confirm_update' =>'True',
               'update_success'=>'',
               'confirm_delete'=>'',
               'delete_success'=>'',
               'completed'=>'',
               'name_err' => '',
               'nic_err' => '',
               'dob_err'=>'',
               'contact_no_err'=>'',
               'address_err' =>''            
        ];
  
        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        } elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';
        }
  
        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->collector_assistantModel->getCollectorAssisByNIC_except($data['nic'] , $assisId)){
          $data['nic_err'] = 'Already exists a center worker under this NIC';
        }
  
        //validate DOB
        if(empty($data['dob'])){
          $data['dob_err'] = 'Please enter dob';
        }
  
        // Validate Contact no
        if(empty($data['contact_no'])){
          $data['contact_no_err'] = 'Please enter contact no';
        }elseif (!preg_match('/^[0-9]{10}$/', $data['contact_no'])) {
          $data['contact_no_err'] = 'Please enter a valid contact number';
        }
  
        // Validate Address
        if(empty($data['address'])){
          $data['address_err'] = 'Please enter adress';
        } elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }
  
        if(empty($data['address_err']) && empty($data['contact_no_err']) && empty($data['dob_err']) && empty($data['nic_err']) && empty($data['name_err']) ){
          if($this->collector_assistantModel->update_collector_assistants($data)){
            $data['update_success']='True';        
            $this->view('collectors/collector_assistants',$data);
          } else {
            die('Something went wrong');
          }
        }
        else{
          $this->view('collectors/collector_assistants',$data);
        }
      }

 
      else{
        $ass = $this -> collector_assistantModel -> getCollectorAssisById($assisId);
        $collector_assistants = $this->collector_assistantModel->get_collector_assistants($_SESSION['collector_id']);

        $data = [
          'collector_assistants' => $collector_assistants,
          'id' => $assisId,
          'name' => $ass->name,
          'nic' => $ass->nic,
          'dob'=> $ass->dob,
          'contact_no'=> $ass->contact_no,
          'address' => $ass->address,
          'confirm_update' =>'True',
          'update_success'=> '',
          'confirm_delete'=>'',
          'delete_success'=>'',

          'name_err' => '',
          'nic_err' =>'',
          'dob_err'=> '',
          'contact_no_err'=> '',
          'address_err' => ''
        ];
        
        $this->view('collectors/collector_assistants', $data);
      }
    }

    public function editprofile(){

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $id=$_SESSION['collector_id']; 
        $user=$this->collectorModel->getCollectorById($id);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data=[
          'name'=>trim($_POST['name']),
         'userid'=>'',
         'email'=>trim($_POST['email']),
         'profile_image_name' => $_SESSION['collector_email'].'_'.$_FILES['profile_image']['name'],
         'contactno'=>trim($_POST['contactno']),
         'address'=>trim($_POST['address']),
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
         'success_message'=>''];

        
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

        if (empty($data['address'])) {
          $data['address_err'] = 'Please Enter an Address';
       } elseif (strlen($data['address']) > 200) {
          $data['address_err'] = 'Address should be at most 200 characters';
        }

        if(empty($data['name_err']) && empty($data['contactno_err'])  && empty($data['address_err'])){
       
           if ($_FILES['profile_image']['error'] == 4) {
                $this->collectorModel->editprofile($data);
                $data['success_message']="Profile Details Updated Successfully";
                $data['change_pw_success']='True';
                $data['profile_err'] = '';
           } else {
             $old_image_path = 'C:/xampp/htdocs/ecoplus/public/img/img_upload/collector/' . $user->image;    
            if (updateImage($old_image_path, $_FILES['profile_image']['tmp_name'], $data['profile_image_name'], '/img/img_upload/collector/')) {
              $this->collectorModel->editprofile_withimg($data);
              $data['success_message']="Profile Details Updated Successfully";
              $data['change_pw_success']='True';
              $data['profile_err'] = '';
            
            } else {
                $data['profile_err'] = 'Error uploading the profile image';
                $this->view('collectors/editprofile', $data); 
            }
            
          }
        }

        $this->view('collectors/editprofile', $data);
       }
       else{ 

        $data=['name'=>'',
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
        'success_message'=>''];

        $id=$_SESSION['collector_id']; 
        $user=$this->collectorModel->getCollectorById($id);
        $data['name']=$_SESSION['collector_name'];
        $data['contactno']=$user->contact_no;
        $data['address']=$user->address;      
        $data['email']=$_SESSION['collector_email'];

        $this->view('collectors/editprofile', $data);
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


      $id=$_SESSION['collector_id']; 
      $user=$this->collectorModel->getCollectorById($id);
      $data['name']=$_SESSION['collector_name'];
      $data['contactno']=$user->contact_no;
      $data['address']=$user->address;
   

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
         
            if($this->userModel->pw_check($_SESSION['collector_id'],$data['current'])){
              if($data['new_pw']!=$data['re_enter_pw']){
                $data['new_pw_err'] = 'Passwords Does not match';
              }
              else{
                if($this->userModel->change_pw($_SESSION['collector_id'],$data['re_enter_pw'])){
                  $data['success_message']="Password Changed Successfully";
                  $data['change_pw_success']='True';
                  $this->view('collectors/editprofile', $data);
                }
              }
            }
            else{
              $data['current_err'] = 'Invalid Password';
            }
                 
      }

      $this->view('collectors/editprofile', $data);
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

   public function request_assinged(){

    $collector=$this->collectorModel->get_collector( $_SESSION['collector_id'] );
    $center=$this->centerModel->getCenterById($collector->center_id);

   if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
      $jsonData = json_encode($assinged_Requests);
       $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
       $data = [
        'assigned_requests' => $assinged_Requests,
        'jsonData' => $jsonData,
        'request_id'=>trim($_POST['id']),
        'reason'=>trim($_POST['reason']),
        'cancelled_by'=>'Collector',
        'assinged'=>'Yes',
        'collector_id'=>($_SESSION['collector_id']),
        'popup'=>'',
        'popup_confirm_collect'=>'',
        'creditData'=>'',
        'center'=>$center

      ];
      if (empty($data['reason']) || str_word_count($data['reason']) > 200) {
        $this->view('collectors/request_assinged', $data);

      } else {
        $this->Request_Model->cancel_request($data);
        $this->request_cancelled();
      }

    }
    else{
      $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
      $jsonData = json_encode($assinged_Requests);
      $data = [
        'assigned_requests' => $assinged_Requests,
        'jsonData' => $jsonData,
        'popup'=>'',
        'popup_confirm_collect'=>'',
        'creditData'=>'' ,
        'center'=>$center

      ];
    
      $this->view('collectors/request_assinged', $data);
    }
    
   }

   public function request_completed(){
    $completed_Requests=$this->Collect_Garbage_Model->get_complete_request( $_SESSION['collector_id'] );
      $jsonData = json_encode($completed_Requests);
      $data = [
        'completed_requests' => $completed_Requests,
        'jsonData' => $jsonData,
      ];
   
    $this->view('collectors/request_completed', $data);
   }

   public function request_cancelled(){

    $cancelled_requests=$this->Request_Model->get_cancelled_request_by_collector($_SESSION['collector_id']);
    $data = [
      'cancelled_requests' => $cancelled_requests,
    ];
   
    $this->view('collectors/request_cancelled', $data);
   }

   public function enterWaste_And_GenerateEcoCredits($req_id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
        $jsonData = json_encode($assinged_Requests);
        $collector_id = $_SESSION['collector_id'];
        //$creditAmount = $this->Customer_Credit_Model->get_customer_credit_balance($customer_id);

        $data = [
            'assigned_requests' => $assinged_Requests,
            'jsonData' => $jsonData,
            'req_id'=>$req_id,
            'collector_id' => $collector_id,
            'polythene_quantity' => trim($_POST['polythene_quantity']),
            'plastic_quantity' => trim($_POST['plastic_quantity']),
            'glass_quantity' => trim($_POST['glass_quantity']),
            'paper_waste_quantity' => trim($_POST['paper_waste_quantity']),
            'electronic_waste_quantity' => trim($_POST['electronic_waste_quantity']),
            'metals_quantity' => trim($_POST['metals_quantity']),
            //'credit_Amount'=> $creditAmount,
            'note' => trim($_POST['note']),
            'popup' => 'True',
            'popup_confirm_collect'=>'',
            'polythene_quantity_err'=>'',
            'plastic_quantity_err'=>'',
            'glass_quantity_err'=>'',
            'paper_waste_quantity_err'=>'',
            'electronic_waste_quantity_err'=>'',
            'metals_quantity_err'=>'',
            'note_err'=>'',
            'creditData'=>''

        ];

        

          // Check if at least one field is filled
        $fieldsToCheck = ['polythene_quantity', 'plastic_quantity', 'glass_quantity', 'paper_waste_quantity', 'electronic_waste_quantity', 'metals_quantity'];
        $atLeastOneFilled = false;
        $allFieldsValid = true;

        foreach ($fieldsToCheck as $field) {
          if (!empty($_POST[$field])) {
              if (!is_numeric($_POST[$field])) {
                  $data["{$field}_err"] = "Please enter a valid number";
                  $allFieldsValid = false;
              } elseif (preg_match('/^\d+(\.\d{1,2})?$/', $_POST[$field]) !== 1) {
                  $data["{$field}_err"] = "Please enter up to two decimal places.";
                  $allFieldsValid = false;
              } else {
                  $atLeastOneFilled = true;
              }
          }
      }


                

            if (!$atLeastOneFilled && $allFieldsValid) {
                $data['polythene_quantity_err'] = 'Please fill polythene quantity';
                $data['plastic_quantity_err'] = 'Please fill plastic quantity';
                $data['glass_quantity_err'] = 'Please fill glass quantity';
                $data['paper_waste_quantity_err'] = 'Please fill paper_waste quantity';
                $data['electronic_waste_quantity_err'] = 'Please fill electronic_waste quantity';
                $data['metals_quantity_err'] = 'Please fill metals quantity';
                
            }

            if(empty($_POST['note'])){
              $data['note_err'] = 'Please fill in the Note field';
            }


          

            if ($atLeastOneFilled && empty($data['note_err']) && $allFieldsValid) {
              $creditData = $this->creditModel->get();

              $credit_Amount =
              (floatval($data['polythene_quantity']) * $creditData->polythene) +
              (floatval($data['plastic_quantity']) * $creditData->plastic) +
              (floatval($data['glass_quantity']) * $creditData->glass) +
              (floatval($data['paper_waste_quantity']) * $creditData->paper) +
              (floatval($data['electronic_waste_quantity']) * $creditData->electronic) +
              (floatval($data['metals_quantity']) * $creditData->metal);
              
            $data['creditData']=$creditData ;
            $data['credit_Amount'] = $credit_Amount;
            $data['popup_confirm_collect'] ="True";
            $this->view('collectors/request_assinged', $data);

            // $inserted = $this->Collect_Garbage_Model->insert($data);
            
            
            // $requst = $this->Request_Model->get_request_by_id($req_id);// Assuming you have the customer ID
            // $customer_id= $requst->customer_id;
            // $current_credit = $this->Customer_Credit_Model->get_customer_credit_balance($customer_id);

            // $new_credit_balance = $current_credit + $credit_Amount; // Calculate new credit balance

            // // Update the customer credit balance in the database
            // $update_result = $this->Customer_Credit_Model->update_credit_balance($customer_id, $new_credit_balance);


            // if ($inserted && $update_result ) {
            //     $this->view('collectors/request_assinged', $data);
            // } else {
            //     // Handle insertion failure
            //     // Show an error message or perform necessary actions
            // }
            } else {
              $this->view('collectors/request_assinged', $data);
            }
          
        } else {
          $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
          $jsonData = json_encode($assinged_Requests);
          $collector_id = $_SESSION['collector_id'];
          //$collector_id = $_SESSION['user_id'];
          //$creditAmount = $this->collectorModel->get_customer_credit_balance($customer_id);
          $data = [
          'assigned_requests' => $assinged_Requests,
          'jsonData' => $jsonData,
          'req_id'=>$req_id,
          'collector_id' => $collector_id,
          'polythene_quantity' =>'',
          'plastic_quantity' => '',
          'glass_quantity' => '',
          'paper_waste_quantity' => '',
          'electronic_waste_quantity' => '',
          'metals_quantity' => '',
          'credit_Amount'=> '',
          'note' => '',
          'popup' => 'True',
          'popup_confirm_collect'=>'',
          'polythene_quantity_err'=>'',
          'plastic_quantity_err'=>'',
          'glass_quantity_err'=>'',
          'paper_waste_quantity_err'=>'',
          'electronic_waste_quantity_err'=>'',
          'metals_quantity_err'=>'',
          'note_err'=>'',
          'creditData'=>''
          ];
          $this->view('collectors/request_assinged', $data);
        } 
}

   public function Eco_Credit_Insert($req_id) {
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
      $jsonData = json_encode($assinged_Requests);
      $collector_id = $_SESSION['collector_id'];
      //$creditAmount = $this->Customer_Credit_Model->get_customer_credit_balance($customer_id);

      $data = [
          'assigned_requests' => $assinged_Requests,
          'jsonData' => $jsonData,
          'req_id'=>$req_id,
          'collector_id' => $collector_id,
          'polythene_quantity' => trim($_POST['polythene_quantity']),
          'plastic_quantity' => trim($_POST['plastic_quantity']),
          'glass_quantity' => trim($_POST['glass_quantity']),
          'paper_waste_quantity' => trim($_POST['paper_waste_quantity']),
          'electronic_waste_quantity' => trim($_POST['electronic_waste_quantity']),
          'metals_quantity' => trim($_POST['metals_quantity']),
          //'credit_Amount'=> $creditAmount,
          'note' => trim($_POST['note']),
          'popup' => 'True',
          'popup_confirm_collect'=>'',
          'polythene_quantity_err'=>'',
          'plastic_quantity_err'=>'',
          'glass_quantity_err'=>'',
          'paper_waste_quantity_err'=>'',
          'electronic_waste_quantity_err'=>'',
          'metals_quantity_err'=>'',
          'note_err'=>'',
          'creditData'=>''
      ];

        // Check if at least one field is filled
      $fieldsToCheck = ['polythene_quantity', 'plastic_quantity', 'glass_quantity', 'paper_waste_quantity', 'electronic_waste_quantity', 'metals_quantity'];
      $atLeastOneFilled = false;
      $allFieldsValid = true;

      foreach ($fieldsToCheck as $field) {
        if (!empty($_POST[$field])) {
            if (!is_numeric($_POST[$field])) {
                $data["{$field}_err"] = "Please enter a valid number";
                $allFieldsValid = false;
            } elseif (preg_match('/^\d+(\.\d{1,2})?$/', $_POST[$field]) !== 1) {
                $data["{$field}_err"] = "Please enter up to two decimal places.";
                $allFieldsValid = false;
            } else {
                $atLeastOneFilled = true;
            }
        }
    }

          if (!$atLeastOneFilled && $allFieldsValid) {
              $data['polythene_quantity_err'] = 'Please fill polythene quantity';
              $data['plastic_quantity_err'] = 'Please fill plastic quantity';
              $data['glass_quantity_err'] = 'Please fill glass quantity';
              $data['paper_waste_quantity_err'] = 'Please fill paper_waste quantity';
              $data['electronic_waste_quantity_err'] = 'Please fill electronic_waste quantity';
              $data['metals_quantity_err'] = 'Please fill metals quantity';     
          }

          if(empty($_POST['note'])){
            $data['note_err'] = 'Please fill in the Note field';
          }

          if ($atLeastOneFilled && empty($data['note_err']) && $allFieldsValid) {
            $creditData = $this->creditModel->get();

            $credit_Amount =
            (floatval($data['polythene_quantity']) * $creditData->polythene) +
            (floatval($data['plastic_quantity']) * $creditData->plastic) +
            (floatval($data['glass_quantity']) * $creditData->glass) +
            (floatval($data['paper_waste_quantity']) * $creditData->paper) +
            (floatval($data['electronic_waste_quantity']) * $creditData->electronic) +
            (floatval($data['metals_quantity']) * $creditData->metal);
            
          $data['creditData']=$creditData ;
          $data['credit_Amount'] = $credit_Amount;

          $collector = $this->collectorModel->getCollectorById($_SESSION['collector_id']);
          $data['center_id'] = $collector->center_id;
          $data['region'] = $collector->center_name;


          $inserted = $this->Collect_Garbage_Model->insert($data);
          
          
          $requst = $this->Request_Model->get_request_by_id($req_id);// Assuming you have the customer ID
          $customer_id= $requst->customer_id;
          $current_credit = $this->Customer_Credit_Model->get_customer_credit_balance($customer_id);

          $new_credit_balance = $current_credit + $credit_Amount; // Calculate new credit balance

          // Update the customer credit balance in the database
          $update_result = $this->Customer_Credit_Model->update_credit_balance($customer_id, $new_credit_balance);
          $updatedGarbageTotals = $this->Collect_Garbage_Model->updateGarbageTotals($req_id);

          if ($inserted && $update_result && $updatedGarbageTotals ) {
              $this->request_completed();

          } else {
            $data['popup']='';
            $this->view('collectors/request_assinged', $data);
          }
          } else {
            $this->view('collectors/request_assinged', $data);
          }
        
      } else {
        $assinged_Requests=$this->Request_Model->get_assigned_request_by_collector( $_SESSION['collector_id'] );
        $jsonData = json_encode($assinged_Requests);
        $collector_id = $_SESSION['collector_id'];
        //$collector_id = $_SESSION['user_id'];
        //$creditAmount = $this->collectorModel->get_customer_credit_balance($customer_id);
        $data = [
        'assigned_requests' => $assinged_Requests,
        'jsonData' => $jsonData,
        'req_id'=>$req_id,
        'collector_id' => $collector_id,
        'polythene_quantity' =>'',
        'plastic_quantity' => '',
        'glass_quantity' => '',
        'paper_waste_quantity' => '',
        'electronic_waste_quantity' => '',
        'metals_quantity' => '',
        'credit_Amount'=> '',
        'note' => '',
        'popup' => 'True',
        'popup_confirm_collect'=>'',
        'polythene_quantity_err'=>'',
        'plastic_quantity_err'=>'',
        'glass_quantity_err'=>'',
        'paper_waste_quantity_err'=>'',
        'electronic_waste_quantity_err'=>'',
        'metals_quantity_err'=>'',
        'note_err'=>'',
        'creditData'=>''
        ];
        $this->view('collectors/request_assinged', $data);
      } 
}

public function displayCustomerTotalGarbage() {
  $customerGarbageData = $this->Collect_Garbage_Model->getCustomerTotalGarbage();
  $data['customerGarbageData'] = $customerGarbageData;
  $this->view('customer_total_garbage_view', $data);
}


  
  }




  