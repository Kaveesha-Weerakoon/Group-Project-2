<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
  class CenterManagers extends Controller {

    public function __construct(){
      $this->userModel=$this->model('User');
      $this->collectorModel=$this->model('Collector');
      $this->center_model=$this->model('Center');
      $this->collector_complain_Model=$this->model('Collector_Complain');
      $this->centermanagerModel=$this->model('Center_Manager');
      $this->centerworkerModel=$this->model('Center_Worker');      
      $this->Request_Model=$this->model('Request');
      $this->collect_garbage_Model=$this->model('Collect_Garbage');
      $this->garbage_Model=$this->model('Garbage_Stock');
      $this->center_complaints_model=$this->model('Center_Complaints');
      $this->notification_Model=$this->model('Notifications');
      $this->Customer_Model=$this->model('Customer');
      $this->garbageTypeModel=$this->model('Garbage_Types');
      $this->Center_Manager_Report_Model=$this->model('Center_Manager_Report');
      $this->Report_Model=$this->model('Report');
      $this->customer_complaints_model = $this->model('Customer_Complain');
      
      $this->mail = new PHPMailer();
      $this->mail->isSMTP();
      $this->mail->Host = 'sandbox.smtp.mailtrap.io';
      $this->mail->SMTPAuth = true;
      $this->mail->Port = 2525;
      $this->mail->Username = 'f4ab65cd067d1f';
      $this->mail->Password = '111c78b575960b';
      
      if(!isLoggedIn('center_manager_id')){
        redirect('users/login');
      }
    }
    
    public function index(){

      $center=$this->center_model->getCenterById($_SESSION['center_id']);
      $current_garbage_stock = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);
      $json_Current_Garbage = json_encode($current_garbage_stock);
      $Notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $center = $this->center_model->getCenterById($_SESSION['center_id']);
      $incoming_requests_count = $this->Request_Model->get_incoming_requests_count($center->region);
      $no_of_collectors = $this->collectorModel->get_no_of_Collectors($_SESSION['center_id']);
      $no_of_workers = $this->centerworkerModel->get_no_of_center_workers($_SESSION['center_id']);
      $completed_requests_count = $this->Request_Model->get_completed_requests_count($center->region);
      $customers_count = $this->Customer_Model->get_customers_count($center->region);
      $marked_holidays = $this->centermanagerModel->get_marked_holidays($center->region);
      $completed_requests = count($this->collect_garbage_Model->get_completed_requests_bycenter($center->region));
      $total_requests = $this->Request_Model->get_total_requests_by_region($center->region);

      if ($total_requests > 0) {
        $percentage_completed = json_encode(($completed_requests / $total_requests) * 100);
      } else {
        $percentage_completed =json_encode(0);
      }    


      $data = [
        'center_id' => $center->id,
        'center_name' => $center->region,
        'current_garbage'=> $json_Current_Garbage,
        'notification'=> $Notifications,
        'incoming_request_count'=> $incoming_requests_count,
        'collectors_count'=> $no_of_collectors,
        'center_workers_count'=> $no_of_workers,
        'completed_request_count'=> $completed_requests_count,
        'customers_count'=> $customers_count,
        'holiday_success'=> '',
        'marked_holidays'=> $marked_holidays,
        'percentage'=> $percentage_completed
      ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Notifications1 = $this->notification_Model->view_center_Notification($_SESSION['center_id']);
        $Notifications2 = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
        $data['notification']=  $Notifications2 ;
        $this->view('center_managers/index', $data);
      }
      else{
        $this->view('center_managers/index', $data);
      }
     
      //$this->view('center_managers/index', $data);
    }
    
    public function logout(){
      unset($_SESSION['center_manager_id']);
      unset($_SESSION['center_manager_email']);
      unset($_SESSION['center_manager_name']);
      unset($_SESSION['center_id']);
      unset($_SESSION['cm_profile']);
      session_destroy();
      redirect('users/login');
    }

    public function collectors(){
      $collectors = $this->collectorModel->get_collectors_bycenterid_with_assigned($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'collectors' => $collectors,
        'notification' => $notifications,
        'click_update' =>'',
        'update_success'=>'',
        'confirm_delete' =>'',
        'delete_success' =>'',
        'personal_details_click'=> '',
        'vehicle_details_click'=> ''
      ];
      $this->view('center_managers/collectors', $data);
    }

    public function collector_assistants(){
      
      $collector_assistants = $this->collectorModel->getCollectorAssistantsByCenterId($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'collector_assistants' => $collector_assistants,
        'notification' => $notifications,
      ];
     
      $this->view('center_managers/collector_assistants', $data);
    }

    public function collectors_complains(){
      $center=$this->center_model->getCenterById($_SESSION['center_id']); 
      $collector_complains=$this->collector_complain_Model->get_collector_complaints_by_region($center->region);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'collectors_complains' => $collector_complains,
        'notification' =>  $notifications
      ];
     
     
      $this->view('center_managers/collectors_complains', $data);
    }

    public function collectors_add(){

      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
          'name' =>trim($_POST['name']),
          'nic' => trim($_POST['nic']),
          'dob'=>trim($_POST['dob']),
          'contact_no'=>trim($_POST['contact_no']),
          'profile_name'=>'profile.png',
          'address' =>trim($_POST['address']),
          'email'=>trim($_POST['email']),
          'vehicle_no'=>trim($_POST['vehicle_no']),
          'vehicle_type'=>trim($_POST['vehicle_type']),
          'registered'=>'',
          'password'=>trim($_POST['password']),
          'confirm_password'=>trim($_POST['confirm_password']),
          'center_name'=>'',
          'notification'=> $notifications,

          'name_err' =>'',
          'nic_err' =>'',
          'dob_err' =>'',
          'contactNo_err' =>'',
          'address_err' =>'',
          'email_err'=>'', 
          'vehicleNo_err' =>'',
          'vehicleType_err' =>'',
          'password_err'=>'',
          'confirm_password_err'=>'',
          'profile_err'=>''
                
        ];



        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';
        }

        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->collectorModel->getCollectorByNIC($data['nic'])){
          $data['nic_err'] = 'NIC already exists';
        }

        //validate DOB
        if(empty($data['dob'])){
          $data['dob_err'] = 'Please enter dob';
      } else {
          $min_birthdate = date('Y-m-d', strtotime('-18 years'));
      
          if($data['dob'] > $min_birthdate) {
              $data['dob_err'] = 'You must be at least 18 years old.';
          }
      }
      

        //validate contact number
        if(empty($data['contact_no'])){
          $data['contactNo_err'] = 'Please enter contact no';
        }elseif (!preg_match('/^[0-9]{10}$/', $data['contact_no'])) {
          $data['contactNo_err'] = 'Please enter a valid contact number';
        }

        //validate address
        if(empty($data['address'])){
          $data['address_err'] = 'Please enter address';
        }elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }

        //validate vehicle number
        if(empty($data['vehicle_no'])){
          $data['vehicleNo_err'] = 'Please enter vehicle plate number';
        }elseif(!preg_match('/^[A-Z]{2,3}-[0-9]{4}$/', $data['vehicle_no'])){
          $data['vehicleNo_err'] = 'Please enter a valid vehicle plate number';
        }elseif($this->collectorModel->getCollectorByVehicleNo($data['vehicle_no'])){
          $data['vehicleNo_err'] = 'Vehicle have already registered';
        }

        //validate vehicle type
        if(empty($data['vehicle_type'])){
          $data['vehicleType_err'] = 'Please enter vehicle type';
        }elseif(strlen($data['address']) > 50){
          $data['vehicleType_err'] = 'Vehicle type is too long';
        }

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter an email';
        } else {
            // Check email format
            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Invalid email format';
            } else {
                // Check email length
                if(strlen($data['email']) > 255) { // You can adjust the maximum length as needed
                    $data['email_err'] = 'Email is too long';
                } else {
                    // Check email availability
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already taken';
                    }
                }
            }
        }

        //validate password
        if (empty($data['password'])) {
          $data['password_err'] = 'Please enter password';
        } elseif (strlen($data['password']) < 8 || strlen($data['password']) > 30) {
            $data['password_err'] = 'Password must be between 8 and 30 characters';
        } elseif (!preg_match('/[^\w\s]/', $data['password'])) {
            $data['password_err'] = 'Password must include at least one symbol';
        } elseif (!preg_match('/[A-Z]/', $data['password'])) {
            $data['password_err'] = 'Password must include at least one uppercase letter';
        } elseif (!preg_match('/[a-z]/', $data['password'])) {
            $data['password_err'] = 'Password must include at least one lowercase letter';
        } elseif (!preg_match('/[0-9]/', $data['password'])) {
            $data['password_err'] = 'Password must include at least one number';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }


        if(empty($data['name_err']) && empty($data['nic_err']) && empty($data['dob_err']) && empty($data['contactNo_err'])&& empty($data['address_err']) && empty($data['email_err']) && empty($data['vehicleNo_err']) && empty($data['vehicleType_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) ){
            // $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            // $center=$this->center_model->getCenterById($_SESSION['center_id']);
            // $data['center_name']=$center->region;
            // if($this->collectorModel->register_collector($data)){
            //   $data['registered']='True';  
            
            //   $this->view('center_managers/collectors_add',$data);
            // } else {
            //   die('Something went wrong');
            // }
            
            $center=$this->center_model->getCenterById($_SESSION['center_id']);
            $data['center_name']=$center->region;
            $data['center_id']=$center->id;
            $pw=$data['password'];
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);
           
            $url = 'http://localhost/ecoplus/users/register_success_collector?selector='.$selector.'&validator='.bin2hex($token).'&email='.urlencode($data['email']);            
            //Expiration date will last for half an hour
            $expires = date("U") + 1800;
            if(!$this->userModel->deleteEmail_Collector($data['email'])){
              header("Location: " . URLROOT . "");        

            }
            // if(!$this->resetPasswordModel->deleteEmail($usersEmail)){
            //     die("There was an error");
            // }
            
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            $data['selector']=$selector;
            $data['hashedToken']=$hashedToken;
            $data['expires']=$expires;
            // Register User
            if($this->userModel->register_confirm_collector($data)){
              
              $usersEmail = $data['email'];
              $subject = "Login to your account";
              $message = "<p>We recieved a login request.</p>";
              $message .= "<p>Here is your login link: </p>";
              $message .= "<a href='".$url."'>".$url."</a>";
  
              $this->mail->setFrom('ecoplusgroupproject@gmail.com');
              $this->mail->isHTML(true);
              $this->mail->Subject = $subject;
              $this->mail->Body = $message;
              $this->mail->addAddress( $usersEmail);
  
              
              if (!$this->mail->send()) {
                 redirect('users/login');

              } else {
                $data['registered']='True';  
                $this->view('center_managers/collectors_add',$data);     
              }

            
            }
             else {
              redirect('users/login');
            }
        }
        else{
          $this->view('center_managers/collectors_add', $data);

        }

      }
      else{
        $data = [
          'notification'=> $notifications,
          'name' =>'',
          'nic' => '',
          'dob'=>'',
          'contact_no'=>'',
          'address' =>'',
          'email'=>'',
          'vehicle_no'=>'',
          'vehicle_type'=>'',
          'registered'=>'',
          'password'=>'',
          'confirm_password'=>'',

          'name_err' =>'',
          'nic_err' =>'',
          'dob_err' =>'',
          'contactNo_err' =>'',
          'address_err' =>'',
          'email_err'=>'', 
          'vehicleNo_err' =>'',
          'vehicleType_err' =>'',
          'password_err'=>'',
          'confirm_password_err'=>'',
          'profile_err'=>''
        ];
          
        $this->view('center_managers/collectors_add', $data);
      }
     
     
    }  
    
    public function collector_block($id){
      $this->collectorModel->block($id);
      header("Location: " . URLROOT . "/centermanagers/collectors");        
    }
    
    public function collector_unblock($id){

      $this->collectorModel->unblock($id);
      header("Location: " . URLROOT . "/centermanagers/collectors");        
    }

    public function collectors_update($collectorId){
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $collectors = $this->collectorModel->get_collectors_bycenterid_with_assigned($_SESSION['center_id']);
            $data = [
                'collectors' => $collectors,
                'id'=> $collectorId,
                'name' =>trim($_POST['name']),
                'nic' => trim($_POST['nic']),
                'dob'=>trim($_POST['dob']),
                'contact_no'=>trim($_POST['contact_no']),
                'address' =>trim($_POST['address']),
                'vehicle_no'=>trim($_POST['vehicle_no']),
                'vehicle_type'=>trim($_POST['vehicle_type']),
                'click_update' =>'True',
                'notification'=> $notifications,
                'update_success'=>'',
                'confirm_delete'=> '',
                'delete_success'=> '',
                'personal_details_click'=> '',
                'vehicle_details_click'=> '',
                
                'name_err' => '',
                'nic_err' => '',
                'dob_err'=>'',
                'contact_no_err'=>'',
                'address_err' =>'',
                'vehicle_no_err'=> '',
                'vehicle_type_err'=> ''
                
            ];

        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';
        }

        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->collectorModel->getCollectorByNIC_except($data['nic'] , $collectorId)){
          $data['nic_err'] = 'Already exists a collector under this NIC';
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
          $data['address_err'] = 'Please enter address';
        }elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }

        //Validate Vehicle plate No
        if(empty($data['vehicle_no'])){
          $data['vehicle_no_err'] = 'Please enter vehicle plate number';
        }elseif(!preg_match('/^[A-Z]{2,3}-[0-9]{4}$/', $data['vehicle_no'])){
          $data['vehicle_no_err'] = 'Please enter a valid vehicle plate number';
        }elseif($this->collectorModel->getVehicleNo_except($data['vehicle_no'] , $collectorId)){
          $data['vehicle_no_err'] = 'Already exists a vehicle under this vehicle No';
        }

        //validate vehicle type
        if(empty($data['vehicle_type'])){
          $data['vehicle_type_err'] = 'Please enter vehicle type';
        }elseif(strlen($data['address']) > 50){
          $data['vehicle_type_err'] = 'Vehicle type is too long';
        }

        if(empty($data['address_err']) && empty($data['contact_no_err']) && empty($data['dob_err']) && empty($data['nic_err']) && empty($data['name_err']) && empty($data['vehicle_no_err']) && empty($data['vehicle_type_err'])){
          if($this->collectorModel->update_collectors($data)){
            $data['update_success']='True';       
            $this->view('center_managers/collectors',$data);
          } else {
            die('Something went wrong');
          }
        }
        else{
          $this->view('center_managers/collectors', $data);
        }
        //$this->view('center_managers/collectors', $data);
      }
      else{

        $collectors = $this->collectorModel->get_collectors_bycenterid_with_assigned($_SESSION['center_id']);
        $collector = $this->collectorModel->getCollector_ByID_view($collectorId);
        $data = [

          'collectors' => $collectors,
          'id'=> $collectorId,
          'name' => $collector->name,
          'nic' => $collector->nic,
          'dob'=> $collector->dob,
          'contact_no'=> $collector->contact_no,
          'address' => $collector->address,
          'vehicle_no'=> $collector->vehicle_no,
          'vehicle_type'=> $collector->vehicle_type,
          'click_update' =>'True',
          'notification'=> $notifications,
          'update_success'=>'',
          'confirm_delete'=> '',
          'delete_success'=> '',
          'personal_details_click'=> '',
          'vehicle_details_click'=> '',

          'name_err' => '',
          'nic_err' => '',
          'dob_err'=>'',
          'contact_no_err'=>'',
          'address_err' =>'',
          'vehicle_no_err'=>'',
          'vehicle_type_err'=>''
          
        ];
        
        $this->view('center_managers/collectors', $data);
      }
    }


    public function collector_delete($collectorId){

      $collectors = $this->collectorModel->get_collectors_bycenterid_with_assigned($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'collectors' => $collectors,
        'notification'=> $notifications,
        'click_update' =>'',
        'update_success'=>'',
        'confirm_delete'=> '',
        'personal_details_click'=> '',
        'vehicle_details_click'=> ''
      ];

      $collector = $this->collectorModel->getCollectorById($collectorId);
      if(empty($collector)){
        die('Collector not found');
      }
      else{
        if($this->collectorModel->delete_collectors($collectorId)){
          deleteImage("C:\\xampp\\htdocs\\ecoplus\\public\\img\\img_upload\\collector\\".$collector->image);
          $data['delete_success'] = 'True';
          $this->view('center_managers/collectors', $data);
        }
        else{
          die('Something went wrong');
        }

      }

    }

    public function center_workers(){
      $center_workers = $this->centerworkerModel->get_center_workers($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'center_workers' => $center_workers,
        'notification' =>  $notifications,
        'center_worker_id'=>'',
        'click_update' =>'',
        'update_success'=>'',
        'confirm_delete' => '',
        'delete_success' =>''
      ];
       
       $this->view('center_managers/center_workers', $data);
    }

    public function center_workers_add(){
     
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
               'name' =>trim($_POST['name']),
               'nic' => trim($_POST['nic']),
               'dob'=>trim($_POST['dob']),
               'contact_no'=>trim($_POST['contact_no']),
               'address' =>trim($_POST['address']),
               'registered'=>'',
               'notification'=> $notifications,
               
               'name_err' => '',
               'nic_err' => '',
               'dob_err'=>'',
               'contact_no_err'=>'',
               'address_err' =>''            
        ];

        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';
        }

        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->centerworkerModel->getCenterWorkerByNIC($data['nic'])){
          $data['nic_err'] = 'NIC already exists';
        }

          // Validate date of birth
        if(empty($data['dob'])) {
          $data['dob_err'] = 'Please enter your date of birth.';
        } else {
          // Calculate age
            $dob = new DateTime($data['dob']);
            $now = new DateTime();
            $age = $now->diff($dob)->y;

            // Check if age is less than 18
            if($age < 18) {
              $data['dob_err'] = 'You must be at least 18 years old.';
             }
        }


        // Validate Contact no
        if(empty($data['contact_no'])){
          $data['contact_no_err'] = 'Please enter contact no';
        }elseif (!preg_match('/^[0-9]{10}$/', $data['contact_no'])) {
          $data['contact_no_err'] = 'Please enter a valid contact number';
        }

        // Validate Address
        if(empty($data['address'])){
          $data['address_err'] = 'Please enter address';
        }elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }

        if(empty($data['address_err']) && empty($data['contact_no_err']) && empty($data['dob_err']) && empty($data['nic_err']) && empty($data['name_err']) ){
          if($this->centerworkerModel->add_center_workers($data)){
            $data['registered']='True';       
            $this->view('center_managers/center_workers_add',$data);
          } else {
            die('Something went wrong');
          }
        }
        else{
          $this->view('center_managers/center_workers_add', $data);
        }
      
      }
      else{
        $data = [
          'notification'=> $notifications,
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
          'address_err' =>'',
          
        ];
        
        $this->view('center_managers/center_workers_add', $data);
      }

      
    }

    public function center_workers_update($workerId){

      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $center_workers = $this->centerworkerModel->get_center_workers($_SESSION['center_id']);
            $data = [
                'center_workers' => $center_workers,
                'id'=> $workerId,
                'name' =>trim($_POST['name']),
                'nic' => trim($_POST['nic']),
                'dob'=>trim($_POST['dob']),
                'contact_no'=>trim($_POST['contact_no']),
                'address' =>trim($_POST['address']),
                'click_update' =>'True',
                'notification'=> $notifications,
                'update_success'=>'',
                'confirm_delete'=> '',          
                'name_err' => '',
                'nic_err' => '',
                'dob_err'=>'',
                'contact_no_err'=>'',
                'address_err' =>''          
            ];

        //validate name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }elseif (strlen($data['name']) > 255) {
          $data['name_err'] = 'Name is too long';
        }

        //validate NIC
        if(empty($data['nic'])){
          $data['nic_err'] = 'Please enter NIC';
        }elseif(!(is_numeric($data['nic']) && (strlen($data['nic']) == 12)) && !preg_match('/^[0-9]{9}[vV]$/', $data['nic'])){
          $data['nic_err'] = 'Please enter a valid NIC';
        }elseif($this->centerworkerModel->getCenterWorkersByNIC_except($data['nic'] , $workerId)){
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
          $data['address_err'] = 'Please enter address';
        }elseif (strlen($data['address']) > 500) {
          $data['address_err'] = 'Address is too long ';
        }

        
        if(empty($data['address_err']) && empty($data['contact_no_err']) && empty($data['dob_err']) && empty($data['nic_err']) && empty($data['name_err']) ){
          if($this->centerworkerModel->update_center_workers($data)){
            //die('sucess');
            $data['update_success']='True';       
            $this->view('center_managers/center_workers',$data);
          } else {
            die('Something went wrong');
          }
        }
        else{
          $this->view('center_managers/center_workers', $data);
        }

        //$this->view('center_managers/center_workers', $data);
      
      }
      else{

        $center_workers = $this->centerworkerModel->get_center_workers($_SESSION['center_id']);
        $center_worker = $this->centerworkerModel->getCenterWorkerById($workerId);
        $data = [

          'center_workers' => $center_workers,
          'id'=> $workerId,
          'name' => $center_worker->name,
          'nic' => $center_worker->nic,
          'dob'=> $center_worker->dob,
          'contact_no'=> $center_worker->contact_no,
          'address' => $center_worker->address,
          'click_update' =>'True',
          'notification'=> $notifications,
          'update_success'=>'',
          'confirm_delete'=> '',

          'name_err' => '',
          'nic_err' => '',
          'dob_err'=>'',
          'contact_no_err'=>'',
          'address_err' =>''
          
        ];
        
        $this->view('center_managers/center_workers', $data);

        
      }

     

    }

    public function center_workers_delete_confirm($workerId){
      $center_workers = $this->centerworkerModel->get_center_workers($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'center_workers' => $center_workers,
        'confirm_delete' =>'True',
        'delete_success' =>'',
        'click_update' =>'',
        'update_success'=>'',
        'center_worker_id'=>$workerId,
        'notification' => $notifications

      ];

      $this->view('center_managers/center_workers', $data);
    } 

    public function center_workers_delete($workerId){

      $center_workers = $this->centerworkerModel->get_center_workers($_SESSION['center_id']);
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data = [
        'center_workers' => $center_workers,
        'notification' => $notifications

      ];
      $center_worker = $this->centerworkerModel->getCenterWorkerById($workerId);
      if(empty($center_worker)){
        die('Center worker not found');
      }
      else{
        if($this->centerworkerModel->delete_center_workers($workerId)){
          $data['delete_success'] = 'True';
          $this->view('center_managers/center_workers', $data);
        }
        else{
          die('Something went wrong');
        }

      }

    }

   public function editprofile(){
      $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $id=$_SESSION['center_manager_id']; 
        $user=$this->centermanagerModel->getCenterManagerByID($id);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data=[
          'name'=>trim($_POST['name']),
         'userid'=>'',
         'email'=>trim($_POST['email']),
         'profile_image_name' => $_SESSION['center_manager_email'].'_'.$_FILES['profile_image']['name'],
         'contactno'=>trim($_POST['contactno']),
         'address'=>trim($_POST['address']),
         'notification'=> $notifications,
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
                $this->centermanagerModel->editprofile($data);
                $data['success_message']="Profile Details Updated Successfully";
                $data['change_pw_success']='True';
                $data['profile_err'] = '';
           } else {
             $old_image_path = 'C:/xampp/htdocs/ecoplus/public/img/img_upload/center_manager/' . $user->image;    
            if (updateImage($old_image_path, $_FILES['profile_image']['tmp_name'], $data['profile_image_name'], '/img/img_upload/center_manager/')) {
              $this->centermanagerModel->editprofile_withimg($data);
              $data['success_message']="Profile Details Updated Successfully";
              $data['change_pw_success']='True';
              $data['profile_err'] = '';
            
            } else {
                $data['profile_err'] = 'Error uploading the profile image';
                $this->view('center_managers/editprofile', $data); 
            }
            
          }
        }

        $this->view('center_managers/editprofile', $data);
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
        'success_message'=>'',
        'notification'=> $notifications
        ];
       
        $id=$_SESSION['center_manager_id']; 
        $user=$this->centermanagerModel->getCenterManagerByID($id);
        $data['name']=$_SESSION['center_manager_name'];
        $data['contactno']=$user->contact_no;
        $data['email']=$_SESSION['center_manager_email'];
        $data['address']=$user->address;
       
        $this->view('/center_managers/editprofile', $data);
       }
  
   
   }

   public function change_password(){
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
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
        'notification'=> $notifications,
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


      $id=$_SESSION['center_manager_id']; 
      $user=$this->centermanagerModel->getCenterManagerByID($id);
      $data['name']=$_SESSION['center_manager_name'];
      $data['contactno']=$user->contact_no;
      $data['address']=$user->address;
   

      if (empty($data['current'])) {
        $data['current_err'] = 'Please Enter Current Password';
    }
    
    if (empty($data['new_pw'])) {
      $data['new_pw_err'] = 'Please Enter New Password';
    } elseif (strlen($data['new_pw']) < 8 || strlen($data['new_pw']) > 30) {
        $data['new_pw_err'] = 'New password must be between 8 and 30 characters';

    } elseif (!preg_match('/[^\w\s]/', $data['new_pw'])) {
        $data['new_pw_err'] = 'New password must include at least one symbol';

    } elseif (!preg_match('/[A-Z]/', $data['new_pw'])) {
        $data['new_pw_err'] = 'New password must include at least one uppercase letter';

    } elseif (!preg_match('/[a-z]/', $data['new_pw'])) {
        $data['new_pw_err'] = 'New password must include at least one lowercase letter';

    } elseif (!preg_match('/[0-9]/', $data['new_pw'])) {
      $data['new_pw_err'] = 'New password must include at least one number';
    }


    if (empty($data['re_enter_pw'])) {
      $data['re_enter_pw_err'] = 'Please confirm new password';
    } elseif (strlen($data['re_enter_pw']) < 8 || strlen($data['re_enter_pw']) > 30) {
        $data['re_enter_pw_err'] = 'Confirmed password must be between 8 and 30 characters';

    } elseif (!preg_match('/[^\w\s]/', $data['re_enter_pw'])) {
        $data['re_enter_pw_err'] = 'Confirmed password must include at least one symbol';

    } elseif (!preg_match('/[A-Z]/', $data['re_enter_pw'])) {
        $data['re_enter_pw_err'] = 'Confirmed password must include at least one uppercase letter';

    } elseif (!preg_match('/[a-z]/', $data['re_enter_pw'])) {
        $data['re_enter_pw_err'] = 'Confirmed password must include at least one lowercase letter';

    } elseif (!preg_match('/[0-9]/', $data['re_enter_pw'])) {
      $data['re_enter_pw_err'] = 'Confirmed password must include at least one number';
    }
  

    if(empty($data['new_pw_err']) && empty($data['current_err']) && empty($data['re_enter_pw_err'])) {
         
            if($this->userModel->pw_check($_SESSION['center_manager_id'],$data['current'])){
              if($data['new_pw']!=$data['re_enter_pw']){
                $data['new_pw_err'] = 'Passwords Does not match';
              }
              else{
                if($this->userModel->change_pw($_SESSION['center_manager_id'],$data['re_enter_pw'])){
                  $data['success_message']="Password Changed Successfully";
                  $data['change_pw_success']='True';
                  $this->view('center_managers/editprofile', $data);
                }
              }
            }
            else{
              $data['current_err'] = 'Invalid Password';
            }
                 
      }

      $this->view('center_managers/editprofile', $data);
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
          'success_message'=>'',
          'notification'=> $notifications


        ];
        $this->view('customers/editprofile', $data);

      }

  } 

  public function request_incomming(){
    $collectors1 = $this->collectorModel->get_collectors_bycenterid($_SESSION['center_id']);
    $collectors = $this->collectorModel->get_collectors_by_center_id_no_assign($_SESSION['center_id']);
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $incoming_requests = $this->Request_Model-> get_incoming_request($center->region);
    $jsonData = json_encode($incoming_requests);
    $assigned_requests = $this->Request_Model->get_assigned_request_by_center($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    $assigned_requests_count = [];
    $result=$this->Request_Model->cancelling_auto();

    foreach ($collectors as $collector) {

        $assigned_requests_count[$collector->id] = $this->Request_Model->get_assigned_requests_count_by_collector_for_day($collector->id);
    }

    $data = [
      'incoming_requests' => $incoming_requests,
      'jsonData' => $jsonData,
      'pop_location'=>'',
      'map'=>'',
      'collectors'=>$collectors,
      'assigned_requests_count' => $assigned_requests_count,
      'assigned_requests'=> $assigned_requests,
      'lattitude'=> $center->lat,
      'longitude'=> $center->longi,
      'radius' => $center->radius,
      'notification' => $notifications
    ];
    $this->view('center_managers/request_incomming', $data);

  }

  public function request_cancell(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $incoming_requests = $this->Request_Model-> get_incoming_request($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    $jsonData = json_encode($incoming_requests);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'incoming_requests' => $incoming_requests,
        'jsonData' => $jsonData,
        'pop_location'=>'',
        'map'=>'',
        'request_id'=>trim($_POST['id']),
        'reason'=>trim($_POST['reason']),
        'cancelled_by'=>"Center",
        'assinged'=>'No',
        'collector_id'=>'',
        'notification'=> $notifications

      ];

      if (empty($data['reason']) || str_word_count($data['reason']) > 200) {
         $this->view('center_managers/request_incomming', $data);

      } else {
        $this->Request_Model->cancel_request($data);
      
        header("Location: " . URLROOT . "/centermanagers/request_cancelled");        
    }
    
    }
    else{
      $center=$this->center_model->getCenterById($_SESSION['center_id']); 
      $incoming_requests = $this->Request_Model-> get_incoming_request($center->region);
      $jsonData = json_encode($incoming_requests);
      $data = [
        'incoming_requests' => $incoming_requests,
        'jsonData' => $jsonData,
        'pop_location'=>'',
        'map'=>'',
        'notification'=> $notifications

      ];
      $this->view('center_managers/request_incomming', $data);
    }
  }

  public function request_cancelled(){
       $center=$this->center_model->getCenterById($_SESSION['center_id']); 
       $cancelled_request = $this->Request_Model->get_cancelled_request_bycenter($center->region);
       $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
       $data=[
         'cancelled_request'=>$cancelled_request,
         'notification'=> $notifications
       ];
       $this->view('center_managers/request_cancelled', $data);

  }

  public function assing(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $incoming_requests = $this->Request_Model-> get_incoming_request($center->region);
    $jsonData = json_encode($incoming_requests);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
     
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'incoming_requests' => $incoming_requests,
        'jsonData' => $jsonData,
        'pop_location'=>'',
        'map'=>'',
        'request_id'=>trim($_POST['assign_req_id']),
        'collector_id'=>trim($_POST['selected_collector_id']),
        'notification'=> $notifications

      ];
      if (empty($data['request_id']) || empty($data['collector_id']) ) {
      
          $this->request_incomming();
      } 
      else {
        if($data['collector_id']=='default'){
           $this->request_incomming();
        }
        else{
          $this->Request_Model->assing_collector($data);
          $this->request_assigned();
          
        }
    } 
    }
    else{
      $center=$this->center_model->getCenterById($_SESSION['center_id']); 
      $incoming_requests = $this->Request_Model-> get_incoming_request($center->region);
      $jsonData = json_encode($incoming_requests);
      $data = [
        'incoming_requests' => $incoming_requests,
        'jsonData' => $jsonData,
        'pop_location'=>'',
        'map'=>'',
        'notification'=> $notifications

      ];
      $this->view('center_managers/request_assinged', $data);
    }
  }

  public function request_assigned(){
    $result=$this->Request_Model->cancelling_auto();

    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $assined_requests=$this->Request_Model->get_assigned_request_by_center($center->region);
    $jsonData = json_encode($assined_requests);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    $data=[
      'assined_requests'=>$assined_requests,
      'jsonData'=>$jsonData,
      'notification'=> $notifications,
      'lattitude'=> $center->lat,
      'longitude'=> $center->longi,
      'radius' => $center->radius
    ];
    
    $this->view('center_managers/request_assinged', $data);

  }


  public function request_completed(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $completed_requests = $this->collect_garbage_Model->get_completed_requests_bycenter($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    $data=[
      'completed_requests'=>$completed_requests,
      'confirm_popup'=> '',
      'confirm_success'=> '',
      'notification'=> $notifications
      
    ];
    $this->view('center_managers/request_completed', $data);

  }

  public function confirm_garbage_details($req_id){
    $center = $this->center_model->getCenterById($_SESSION['center_id']);
    $assigned_request = $this->Request_Model->get_assigned_request($req_id);
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $completed_requests = $this->collect_garbage_Model->get_completed_requests_bycenter($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
      $data = [
        'req_id'=>$req_id,
        'center_id'=>$_SESSION['center_id'],
        'region'=>$center->region,
        'collector_id'=>$assigned_request->collector_id,
        'completed_requests'=>$completed_requests,
        'polythene_quantity' => trim($_POST['polythene_quantity']),
        'plastic_quantity' => trim($_POST['plastic_quantity']),
        'glass_quantity' => trim($_POST['glass_quantity']),
        'paper_waste_quantity' => trim($_POST['paper_waste_quantity']),
        'electronic_waste_quantity' => trim($_POST['electronic_waste_quantity']),
        'metals_quantity' => trim($_POST['metals_quantity']),
        'note' => trim($_POST['note']),
        'confirm_popup' => 'True',
        'notification'=> $notifications,
        'confirm_success'=> '',
        'polythene_quantity_err'=>'',
        'plastic_quantity_err'=>'',
        'glass_quantity_err'=>'',
        'paper_waste_quantity_err'=>'',
        'electronic_waste_quantity_err'=>'',
        'metals_quantity_err'=>'',
        'note_err'=>'',
      

      ];

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
        
        if($this->garbage_Model->garbage_details_confirm($data)){ 
          $data['confirm_success'] = 'True';
          $this->view('center_managers/request_completed',$data);
        } else {
          die('Something went wrong');
        }

      }else{
        $this->view('center_managers/request_completed', $data);
      }


    }else{
      $center=$this->center_model->getCenterById($_SESSION['center_id']); 
      $completed_requests = $this->collect_garbage_Model->get_completed_requests_bycenter($center->region);
      $completed_request = $this->collect_garbage_Model->get_completed_request_byreqId($req_id);

      $data =[
        'req_id'=>$req_id,
        'completed_requests'=>$completed_requests,
        'polythene_quantity'=>$completed_request->Polythene,
        'plastic_quantity'=>$completed_request->Plastic,
        'glass_quantity' => $completed_request->Glass,
        'paper_waste_quantity' => $completed_request->Paper_Waste,
        'electronic_waste_quantity' => $completed_request->Electronic_Waste,
        'metals_quantity' => $completed_request->Metals,
        'note'=> '',
        'confirm_popup' => 'True',
        'notification'=> $notifications,
        'confirm_success'=> '',
        'polythene_quantity_err'=>'',
        'plastic_quantity_err'=>'',
        'glass_quantity_err'=>'',
        'paper_waste_quantity_err'=>'',
        'electronic_waste_quantity_err'=>'',
        'metals_quantity_err'=>'',
        'note_err'=>''


      ];


      $this->view('center_managers/request_completed', $data);

    }

  }

  public function waste_management(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $confirmed_requests = $this->garbage_Model->get_confirmed_requests_by_region($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    $data =[
      'confirmed_requests'=>$confirmed_requests,
      'notification'=> $notifications
    ];
    
    $this->view('center_managers/waste_management', $data);

  }

  public function center_garbage_stock(){

    $current_quantities = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
   
    $data =[
      'current_polythene'=>$current_quantities->current_polythene,
      'current_plastic'=>$current_quantities->current_plastic,
      'current_glass'=>$current_quantities->current_glass,
      'current_paper'=>$current_quantities->current_paper,
      'current_electronic'=>$current_quantities->current_electronic,
      'current_metals'=>$current_quantities->current_metal,
      'notification'=> $notifications,
      'release_popup' => '',
      'sell_price_pop' => '',
      'release_success' => ''

    ];

    $this->view('center_managers/center_garbage_stock', $data);

  }

  public function release_stocks($complete="False",$pop="False"){
    $types=$this->garbageTypeModel->get_all();
    
    $current_quantities = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
   
      $data = [

        'center_id'=>$_SESSION['center_id'],
        'current_polythene'=>$current_quantities->current_polythene,
        'current_plastic'=>$current_quantities->current_plastic,
        'current_glass'=>$current_quantities->current_glass,
        'current_paper'=>$current_quantities->current_paper,
        'current_electronic'=>$current_quantities->current_electronic,
        'current_metals'=>$current_quantities->current_metal,
        'released_person'=> trim($_POST['released_person']),
        'release_note' => trim($_POST['release_note']),
        'release_popup' => 'True',
        'notification'=> $notifications,
        'release_success'=> '',
        'sell_price_pop'=>$pop,
        'released_person_err'=> '',
        'release_note_err'=>'',
        'types'=>$types,
        'total_selling_price'=>''
      ];

      foreach ($types as $type) {
        if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}"] = trim($_POST["{$typeName}"]);
        }
     } 
     
     foreach ($types as $type) {
      if ($type) {
          $typeName = strtolower($type->name);
          $data["{$typeName}_err"] = '';
      }
     }

      $fieldsToCheck = ['polythene', 'plastic', 'glass', 'paper_waste', 'electronic_waste', 'metals'];
      $atLeastOneFilled = false;
      $allFieldsValid = true;

      foreach ($types as $field) {
        if (!empty($_POST["{$field->name}"])) {
          if (!is_numeric($_POST["{$field->name}"])) {
              $data["{$field}_quantity_err"] = "Please enter a valid number";
              $allFieldsValid = false;
            } elseif (preg_match('/^\d+(\.\d{1,2})?$/', $_POST["{$field->name}"]) !== 1) {
                $data["{$field->name}_err"] = "Please enter up to two decimal places.";
                $allFieldsValid = false;
              }elseif ($_POST["{$field->name}"] > $data["current_{$field->name}"]) {
                $data["{$field->name}_err"] = "Please enter a valid quantity within the available stock";
                $allFieldsValid = false;
            }else {
                $atLeastOneFilled = true;
            }
        }
      }
      if (!$atLeastOneFilled && $allFieldsValid) {
        foreach ($types as $type) {
          if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}_err"] = "Please fill {$typeName} quantity";
          }
      }      
    }

    if(empty($_POST['note'])){
      $data['note_err'] = 'Please fill in the Note field';
     }  

      if(empty($_POST['released_person'])){
        $data['released_person_err'] = 'Please fill in the released person field';
      }

      if(empty($_POST['release_note'])){
        $data['release_note_err'] = 'Please fill in the Note field';
      }
      
      if ($atLeastOneFilled && empty($data['release_note_err']) && empty($data['released_person_err']) && $allFieldsValid) {
        
          $total_price=0;

          foreach ($types as $type) {
            if ($type) {
                $total_price+= (floatval($data["$type->name"]) * $type->selling_price);
            }
         } 
          $data['total_sell_price'] = $total_price;
          $data['sell_price_pop'] = 'True';
          if($complete=="True"){
            $this->garbage_Model->release_garbage_stocks($data);

            header("Location: " . URLROOT . "/centermanagers/center_garbage_stock");        
          }
          $this->view('center_managers/center_garbage_stock',$data);
       

       }else{
        $this->view('center_managers/center_garbage_stock', $data);
      }
     } else {

      $current_quantities = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);

      $data = [
        'center_id'=>$_SESSION['center_id'],
        'current_polythene'=>$current_quantities->current_polythene,
        'current_plastic'=>$current_quantities->current_plastic,
        'current_glass'=>$current_quantities->current_glass,
        'current_paper'=>$current_quantities->current_paper,
        'current_electronic'=>$current_quantities->current_electronic,
        'current_metals'=>$current_quantities->current_metal,
        'notification'=> $notifications,
        'released_person'=> '',
        'release_note' => '',
        'release_popup' => 'True',
        'release_success'=> '',
        'released_person_err'=> '',
        'release_note_err'=>'',
        'sell_price_pop'=>'',
        'types'=>$types

      ]; 
      foreach ($types as $type) {
        if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}_err"] = '';
        }
     } 
     
     foreach ($types as $type) {
      if ($type) {
          $typeName = strtolower($type->name);
          $data["{$typeName}"] = '';
      }
     }

      $this->view('center_managers/center_garbage_stock', $data);

    }
    
  }

  public function realease_stocks2(){
    $types=$this->garbageTypeModel->get_all();

    $current_quantities = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [

        'center_id'=>$_SESSION['center_id'],
        'current_polythene'=>$current_quantities->current_polythene,
        'current_plastic'=>$current_quantities->current_plastic,
        'current_glass'=>$current_quantities->current_glass,
        'current_paper'=>$current_quantities->current_paper,
        'current_electronic'=>$current_quantities->current_electronic,
        'current_metals'=>$current_quantities->current_metal,
        'released_person'=> trim($_POST['released_person']),
        'release_note' => trim($_POST['release_note']),
        'release_popup' => 'True',
        'notification'=> $notifications,
        'release_success'=> '',
        'sell_price_pop'=>'',
        'released_person_err'=> '',
        'release_note_err'=>'',
        'types'=>$types,
        'total_selling_price'=>''
      ];

      foreach ($types as $type) {
        if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}"] = trim($_POST["{$typeName}"]);
        }
     } 
     
     foreach ($types as $type) {
      if ($type) {
          $typeName = strtolower($type->name);
          $data["{$typeName}_err"] = '';
      }
   }

      $fieldsToCheck = ['polythene', 'plastic', 'glass', 'paper_waste', 'electronic_waste', 'metals'];
      $atLeastOneFilled = false;
      $allFieldsValid = true;

      foreach ($types as $field) {
        if (!empty($_POST["{$field->name}"])) {
          if (!is_numeric($_POST["{$field->name}"])) {
              $data["{$field}_quantity_err"] = "Please enter a valid number";
              $allFieldsValid = false;
            } elseif (preg_match('/^\d+(\.\d{1,2})?$/', $_POST["{$field->name}"]) !== 1) {
                $data["{$field->name}_err"] = "Please enter up to two decimal places.";
                $allFieldsValid = false;
              }elseif ($_POST["{$field->name}"] > $data["current_{$field->name}"]) {
                $data["{$field->name}_err"] = "Please enter a valid quantity within the available stock";
                $allFieldsValid = false;
            }else {
                $atLeastOneFilled = true;
            }
        }
      }
      if (!$atLeastOneFilled && $allFieldsValid) {
        foreach ($types as $type) {
          if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}_err"] = "Please fill {$typeName} quantity";
          }
      }      
    }

    if(empty($_POST['note'])){
      $data['note_err'] = 'Please fill in the Note field';
   }  

      if(empty($_POST['released_person'])){
        $data['released_person_err'] = 'Please fill in the released person field';
      }

      if(empty($_POST['release_note'])){
        $data['release_note_err'] = 'Please fill in the Note field';
      }

      if ($atLeastOneFilled && empty($data['release_note_err']) && empty($data['released_person_err']) && $allFieldsValid) {
        
          $total_price=0;

          foreach ($types as $type) {
            if ($type) {
                $total_price+= (floatval($data["$type->name"]) * $type->selling_price);
            }
         } 
        
          $data['total_sell_price'] = $total_price;
          $data['garbage_prices'] = $priceData;
          $data['sell_price_pop'] = 'True';
          $this->view('center_managers/center_garbage_stock',$data);
       

      }else{
        $this->view('center_managers/center_garbage_stock', $data);
      }



    } else {

      $current_quantities = $this->garbage_Model->get_current_quantities_of_garbage($_SESSION['center_id']);

      $data = [
        'center_id'=>$_SESSION['center_id'],
        'current_polythene'=>$current_quantities->current_polythene,
        'current_plastic'=>$current_quantities->current_plastic,
        'current_glass'=>$current_quantities->current_glass,
        'current_paper'=>$current_quantities->current_paper,
        'current_electronic'=>$current_quantities->current_electronic,
        'current_metals'=>$current_quantities->current_metal,
        'released_person'=> '',
        'notification'=> $notifications,
        'release_note' => '',
        'release_popup' => 'True',
        'release_success'=> '',
        'released_person_err'=> '',
        'release_note_err'=>'',
        'sell_price_pop'=>'',
        'types'=>$types

      ]; 
      foreach ($types as $type) {
        if ($type) {
            $typeName = strtolower($type->name);
            $data["{$typeName}_err"] = '';
        }
     } 
     
     foreach ($types as $type) {
      if ($type) {
          $typeName = strtolower($type->name);
          $data["{$typeName}"] = '';
      }
   }

      $this->view('center_managers/center_garbage_stock', $data);

    }
  }

  public function stock_release_details(){
    $release_details = $this->garbage_Model->get_release_details($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    $data = [
      'release_details'=>$release_details,
      'notification'=> $notifications
    ];

    $this->view('center_managers/stock_releases', $data);

  }

  public function complaints(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data =[
        'center_id'=> $_SESSION['center_id'],
        'region'=> $center->region,
        'cm_id' => $_SESSION['center_manager_id'],
        'name' => trim($_POST['name']),
        'contact_no' => trim($_POST['contact_no']),
        'subject' => trim($_POST['subject']),
        'complaint' => trim($_POST['complaint']),
        'notification'=> $notifications,
        'name_err' => '',
        'contact_no_err' => '',
        'subject_err' => '' ,
        'complaint_err' => '' ,
        'completed'=>''    
      ];
      
      //validate name
      if(empty($data['name'])){
        $data['name_err'] = 'Please enter name';
      }elseif (strlen($data['name']) > 255) {
        $data['name_err'] = 'Name is too long';
      }
     
      // Validate contact number
      if(empty($data['contact_no'])){
        $data['contact_no_err'] = 'Please enter contact no';
      }elseif (!preg_match('/^[0-9]{10}$/', $data['contact_no'])) {
        $data['contact_no_err'] = 'Please enter a valid contact number';
      }

      //validate subject
      if(empty($data['subject'])){
        $data['subject_err'] = 'Please enter subject';
      }elseif (strlen($data['subject']) > 255) {
        $data['subject_err'] = 'Subject is too long';
      }
      
      //validate complaint
      if(empty($data['complaint'])){
        $data['complaint_err'] = 'Please enter the complaint';
      }

      if(empty($data['name_err']) && empty($data['contact_no_err']) && empty($data['subject_err']) && empty($data['complain_err']) ){
        if($this->center_complaints_model->submit_complaint($data)){
          $data['completed']="True";
          $this->view('center_managers/complaints', $data);         
         
        } else {
          die('Something went wrong');
        }
      }
      else{     
            $this->view('center_managers/complaints', $data);         
      }
    }
    else{
      $center_manager = $this->centermanagerModel->getCenterManagerByID($_SESSION['center_manager_id']);
      
      $data =[
        'name' => $_SESSION['center_manager_name'],
        'contact_no' => $center_manager->contact_no,
        'notification'=> $notifications,
        'subject' => '',
        'complaint' => '',
        'name_err' => '',
        'contact_no_err' => '',
        'subject_err' => '' ,
        'complaint_err' => ''  ,
        'completed'=>''      
      ];

      $this->view('center_managers/complaints', $data);
     
   } 

  }

  public function mark_holidays(){
    $center=$this->center_model->getCenterById($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'holiday' => trim($_POST['holiday']),
        'center_id'=> $_SESSION['center_id'],
        'region'=> $center->region,
        'holiday_success'=> '',
        'notification'=> $notifications
        
      ];

      if (empty($data['holiday']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['holiday'])) {
        $this->view('center_managers/index', $data);

      } else {
        if($this->centermanagerModel->mark_holidays($data)){
          $data['holiday_success']='True'; 
          $data['notification'] =  $notifications;
          $this->index();
          //$this->view('center_managers/index',$data);
          

        } else {
          die('Something went wrong');
        }
        
        
        
        //$this->index();
       
        
      }
    
    }
    else{

      $this->index();
    }
    
    
  }

  public function complaints_history(){
    $complaints_history = $this->center_complaints_model->get_center_complaints_history($_SESSION['center_id']);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
    $data=[
      'complaints_history'=>$complaints_history,
      'notification'=> $notifications
     
      
    ];

    $this->view('center_managers/complaints_history', $data);


  }

  public function reports(){

    $center1 = $this->center_model->getCenterById($_SESSION['center_id']);
    $region = $center1->region;
    $centerId=$center1->id;
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
          
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $collector =trim($_POST['collector-dropdown']);
      //print($collector);
      if($collector != "none"){
        
         $collector_id = $collector;
      }
      else{
        $collector_id="none";

      }
      $fromDate= trim($_POST['fromDate']);
      $toDate= trim($_POST['toDate']);
      
      if($toDate==""){
        $toDate="none";
      } 

      if($fromDate==""){
        $fromDate="none";
      }
     
      $collectors=$this->collectorModel->get_collectors_bycenterid($_SESSION['center_id']);
      $totalRequests = $this->Center_Manager_Report_Model->getallRequests_collector($fromDate,$toDate,$region,$collector_id);
      $completedRequests=$this->Center_Manager_Report_Model->getCompletedRequests_collector($fromDate,$toDate,$region, $collector_id);
      $cancelledRequests=$this->Center_Manager_Report_Model->getCancelledRequests_collector($fromDate,$toDate,$region, $collector_id);
      $ongoingRequests=$this->Center_Manager_Report_Model->getonGoingRequests_collector($fromDate,$toDate,$region, $collector_id);
      $collectedWasteByMonth=$this->Center_Manager_Report_Model->getCollectedGarbage_collector($fromDate,$toDate,$region, $collector_id);
      $handoveredWasteByMonth=$this->Center_Manager_Report_Model->getHandOveredGarbage_collector($fromDate,$toDate,$region, $collector_id);
      $selledWasteByMonth=$this->Center_Manager_Report_Model->getSelledGarbage($fromDate,$toDate,$centerId);
      $centers = $this->center_model->getallCenters();
      $credits=$this->Center_Manager_Report_Model->getCredits_collector($fromDate,$toDate,$region, $collector_id);
      $creditByMonth=$this->Center_Manager_Report_Model->getCreditsMonths_collector( $region , $collector_id);


      $data=[
        'completedRequests'=> count($completedRequests),
        'cancelledRequests'=> count($cancelledRequests),
        'ongoingRequests'=> count($ongoingRequests),
        'totalRequests'=> count($totalRequests),
        'collectedWasteByMonth'=>$collectedWasteByMonth,
        // 'centers'=> $centers,
        // 'center'=>$center,
        'collectors'=> $collectors,
        'collector' => $collector,
        'notification'=> $notifications,
        'to'=>$toDate,
        'from'=>$fromDate,
        'credits'=> $credits->total_credits,
        'creditsByMonth1'=>  $creditByMonth,
        
        'handoveredWasteByMonth'=>$handoveredWasteByMonth,
        'selledWasteByMonth'=>$selledWasteByMonth

      ];
      $this->view('center_managers/reports', $data);

    }
    else{
      
      $collectors=$this->collectorModel->get_collectors_bycenterid($_SESSION['center_id']);
      $totalRequests = $this->Center_Manager_Report_Model->getallRequests_collector("none", "none", $region, "none");
      $completedRequests=$this->Center_Manager_Report_Model->getCompletedRequests_collector("none", "none", $region, "none");
      $cancelledRequests=$this->Center_Manager_Report_Model->getCancelledRequests_collector("none", "none", $region, "none");
      $ongoingRequests=$this->Center_Manager_Report_Model->getonGoingRequests_collector("none", "none", $region, "none");
      $collectedWasteByMonth=$this->Center_Manager_Report_Model->getCollectedGarbage_collector("none", "none", $region, "none");
      $handoveredWasteByMonth=$this->Center_Manager_Report_Model->getHandOveredGarbage_collector("none", "none", $region, "none");
      $selledWasteByMonth=$this->Center_Manager_Report_Model->getSelledGarbage("none", "none",$centerId);
      $centers = $this->center_model->getallCenters();
      $credits=$this->Center_Manager_Report_Model->getCredits_collector("none", "none", $region, "none");
      $creditByMonth=$this->Center_Manager_Report_Model->getCreditsMonths_collector( $region , "none");

 
      $data=[
        'completedRequests'=> count($completedRequests),
        'cancelledRequests'=> count($cancelledRequests),
        'ongoingRequests'=> count($ongoingRequests),
        'totalRequests'=> count($totalRequests),
        'collectedWasteByMonth'=>$collectedWasteByMonth,
        // 'centers'=> $centers,
        'collectors'=> $collectors,
        'collector'=>'All',
        'to'=>'none',
        'from'=>'none',    
        'credits'=> $credits->total_credits,
        'creditsByMonth1'=>  $creditByMonth,
        'notification'=> $notifications,
        'handoveredWasteByMonth'=>$handoveredWasteByMonth,
        'selledWasteByMonth'=>$selledWasteByMonth

      ];

    
      $this->view('center_managers/reports', $data);

    }
    
  }

  public function garbage_types(){

    $garbage_types = $this->garbageTypeModel->get_all();
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    $data =[
      'garbage_types' => $garbage_types,
      'notification'=> $notifications

    ];

    $this->view('center_managers/garbage_types', $data);
  }

  public function view_notification($url){
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
     
      $Notifications1 = $this->notification_Model->view_center_Notification($_SESSION['center_id']);
      $Notifications2 = $this->notification_Model->get_center_Notification($_SESSION['center_id']);
      $data['notification']=  $Notifications2 ;
      
      header("Location: " . URLROOT . "/centermanagers/$url");        

   }
  }

  public function view_customer_complaints(){
 
    $center=$this->center_model->getCenterById($_SESSION['center_id']); 
    $customer_complaints = $this->customer_complaints_model->get_customer_complaints_by_region($center->region);
    $notifications = $this->notification_Model->get_center_Notification($_SESSION['center_id']);

    $data =[
      'customer_complaints' => $customer_complaints,
      'notification'=> $notifications

    ];

    $this->view('center_managers/view_customer_complaints', $data);
  }



  }

  


?>