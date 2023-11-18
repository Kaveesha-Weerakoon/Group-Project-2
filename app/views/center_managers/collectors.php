<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="CenterManager_Collector">
<div class="main">
            <div class="main-top">
                <a href="<?php echo URLROOT?>/centermanagers">
                    <img class="back-button" src="<?php echo IMGROOT ?>/Back.png" alt="">
                </a>

                <div class="main-top-component">
                <p><?php echo $_SESSION['center_manager_name']?></p>
                    <img src="<?php echo IMGROOT ?>/Requests Profile.png" alt="">
                </div>
            </div>
            <div class="main-bottom">
                <div class="main-bottom-top">
                    <div class="main-right-top-two">
                        <h1>Collectors</h1>
                    </div>
                    <div class="main-right-top-three">
                        <a href="">
                            <div class="main-right-top-three-content">
                                <p><b style="color: #1B6652;">View</b></p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/centermanagers/collectors_add">
                            <div class="main-right-top-three-content">
                                <p>Register</p>
                                <div class="line1"></div>
                            </div>
                        </a>
                        <a  href="<?php echo URLROOT?>/centermanagers/collectors_complains">
                            <div class="main-right-top-three-content">
                                <p>Complaints</p>
                                <div class="line1"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="main-bottom-down">
                    <div class="main-right-bottom-top ">
                        <table class="table">
                            <tr class="table-header">
                                <th>Collector ID</th>
                                <th>Profile Pic</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Personal Details</th>
                                <th>Vehicle Details</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </table>
                    </div>
                    <div class="main-right-bottom-down">
                        <table class="table">
                        <?php foreach($data['collectors'] as $collector) : ?> 
                            <tr class="table-row">
                                <td><?php echo $collector->user_id?></td>
                                <td class="collector_image"><img src="<?php echo IMGROOT ?>/img_upload/collector/<?php echo $collector->image?>" alt=""></td>
                                <td><?php echo $collector->name?></td>
                                <td><?php echo $collector->email?></td>
                                <td><a href="<?php echo URLROOT?>/centermanagers/personal_details_view/<?php echo $collector->user_id ?>"><img src="<?php echo IMGROOT ?>/resume.png" alt=""></a></td>
                                <td><a href="<?php echo URLROOT?>/centermanagers/vehicle_details_view/<?php echo $collector->user_id ?>"><img src="<?php echo IMGROOT ?>/car.png" alt=""></a></td>
                                <td><a href="<?php echo URLROOT?>/centermanagers/collectors_update/<?php echo $collector->user_id ?>"><img src="<?php echo IMGROOT ?>/update.png" alt=""></a></td>
                                <td class="delete"><a href="<?php echo URLROOT?>/centermanagers/collector_delete_confirm/<?php echo $collector->user_id ?>"> <img src="<?php echo IMGROOT ?>/delete.png" alt=""></a></td>

                            </tr>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>


    <?php if($data['click_update']=='True') : ?>
                <div class="update_click">
                    <div class="popup-form" id="popup">
                    <a href="<?php echo URLROOT?>/centermanagers/collectors"><img src="<?php echo IMGROOT?>/close_popup.png"  class="update-popup-img" alt=""></a>
                        <h2>Update Details</h2>
                        <center><div class="update-topic-line"></div></center>
                        <form class="updatePopupform" action="<?php echo URLROOT;?>/centermanagers/collectors_update/<?php echo $data['id'];?>" method="post">
                            <div class="updatePopupform-div">
                                <div class="personal-details">Personal Details</div>
                                <div class="top-personal-details"> 
                                    <div class="updateData">
                                        <label>Name</label><br>
                                        <input type="text" name="name" placeholder="Enter name" value="<?php echo $data['name']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['name_err']?>
                                        </div>
                                    </div>
                                    <div class="updateData">
                                        <label>NIC</label><br>
                                        <input type="text" name="nic" placeholder="Enter NIC" value="<?php echo $data['nic']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['nic_err']?>
                                        </div>
                                    </div>
                                    <div class="updateData">
                                        <label>Address</label><br>
                                        <input type="text" name="address" placeholder="Enter Address" value="<?php echo $data['address']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['address_err']?>
                                        </div>
                                    </div>
                                    <div class="updateData">
                                        <label>Contact No</label><br>
                                        <input type="text" name="contact_no" placeholder="Enter Contact No" value="<?php echo $data['contact_no']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['contact_no_err']?>
                                        </div>
                                    </div>
                                    <div class="updateData">
                                        <label>DOB</label><br>
                                        <input type="date" name="dob" placeholder="Enter DOB" value="<?php echo $data['dob']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['dob_err']?>
                                        </div>
                                    </div>
                                </div>
                                <div class="vehicle-details">Vehicle Details</div>
                                <div class="bottom-vehicle-details">
                                    <div class="updateData">
                                        <label>Vehicle Plate No</label><br>
                                        <input type="text" name="vehicle_no" placeholder="Enter Vehicle Plate No" value="<?php echo $data['vehicle_no']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['vehicle_no_err']?>
                                        </div>
                                    </div>
                                    <div class="updateData">
                                        <label>Vehicle Type</label><br>
                                        <input type="text" name="vehicle_type" placeholder="Enter Vehicle Type" value="<?php echo $data['vehicle_type']; ?>"><br>
                                        <div class="error-div" style="color:red">
                                            <?php echo $data['vehicle_type_err']?>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="btns1">
                                    <button type="submit" class="updatebtn" >Update</button>
                                    <button type="button" class="cancelbtn1" ><a href="<?php echo URLROOT?>/centermanagers/collectors">Cancel</a></button>
                                </div>

                            </div>
                            
                            
                        </form>
                    </div>
                </div>

    <?php endif; ?> 
    
        
    <?php if($data['confirm_delete']== 'True') : ?>
        <div class="delete_confirm">
                <div class="popup" id="popup">
                    <img src="<?php echo IMGROOT?>/trash.png" alt="">
                    <h2>Delete this collector?</h2>
                    <p>This action will permanently delete this collector</p>
                    <div class="btns">
                        <a href="<?php echo URLROOT?>/centermanagers/collector_delete/<?php echo $data['collector_id'] ?>"><button type="button" class="deletebtn" >Delete</button></a>
                        <a href="<?php echo URLROOT?>/centermanagers/collectors ?>"><button type="button" class="cancelbtn">Cancel</button></a>
                    </div>
                </div>
        </div>
    <?php endif; ?>

    <?php if($data['update_success']=='True') : ?>
        <div class="success_popup_box">
            <div class="popup1" id="popup1">
                <img src="<?php echo IMGROOT?>/check.png" alt="">
                <h2>Success!!</h2>
                <p>Collector details has updated successfully</p>
                <a href="<?php echo URLROOT?>/centermanagers/collectors"><button type="button" >OK</button></a>

            </div>
        </div>
    <?php endif; ?>

    <?php if($data['delete_success']=='True') : ?>
        <div class="success_popup_box">
            <div class="popup1" id="popup1">
                <img src="<?php echo IMGROOT?>/check.png" alt="">
                <h2>Success!!</h2>
                <p>Collector has deleted successfully</p>
                <a href="<?php echo URLROOT?>/centermanagers/collectors"><button type="button" >OK</button></a>
            </div>
        </div>
    <?php endif; ?>

    <?php if($data['personal_details_click']=='True') : ?>
        <div class="personal-details-popup-box">
            <div class="personal-details-popup-form" id="popup">
                <a href="<?php echo URLROOT?>/centermanagers/collectors"><img src="<?php echo IMGROOT?>/close_popup.png" alt="" class="personal-details-popup-form-close"></a>
                <center><div class="personal-details-topic">Personal Details</div></center>
                
                <div class="personal-details-popup" >
                    <div class="personal-details-left">
                        <img src="<?php echo IMGROOT?>/img_upload/collector/<?php echo $data['image']?>" class="profile-pic" alt="">
                        <p>Collector ID: <span>C<?php echo $data['id']?></span></p>
                    </div>
                    <div class="personal-details-right"> 
                        <div class="personal-details-right-labels">
                            <span>Name</span><br>
                            <span>Email</span><br>
                            <span>NIC</span><br>
                            <span>Address</span><br>
                            <span>Contact No</span><br>
                            <span>DOB</span><br>
                        </div>
                        <div class="personal-details-right-values">
                            <span><?php echo $data['name']?></span><br>
                            <span><?php echo $data['email']?></span><br>
                            <span><?php echo $data['nic']?></span><br>
                            <span><?php echo $data['address']?></span><br>
                            <span><?php echo $data['contact_no']?></span><br>
                            <span><?php echo $data['dob']?></span><br>

                        </div>   
                    </div>   
                </div>
            </div>

        </div>
        
    <?php endif; ?>

    <?php if($data['vehicle_details_click']=='True') : ?>
        <div class="vehicle-details-popup-box">
            <div class="vehicle-details-popup-form" id="popup">
                <a href="<?php echo URLROOT?>/centermanagers/collectors"><img src="<?php echo IMGROOT?>/close_popup.png" alt="" class="vehicle-details-popup-form-close"></a>
                <center><div class="vehicle-details-topic">Vehicle Details</div></center>
                
                <div class="vehicle-details-popup" >
                    <div class="vehicle-details-labels">
                        <span>Collector ID</span><br>
                        <span>Name</span><br>
                        <span>Vehicle Plate No</span><br>
                        <span>Vehicle Type</span><br>
                    </div>
                    <div class="vehicle-details-values">
                        <span>C<?php echo $data['id']?></span><br>
                        <span><?php echo $data['name']?></span><br>
                        <span><?php echo $data['vehicle_no']?></span><br>
                        <span><?php echo $data['vehicle_type']?></span><br>
                    </div>
                </div>
            </div>
        </div>
    
    <?php endif; ?>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>