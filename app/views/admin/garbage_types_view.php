<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Admin_Main">
    <div class="Admin_Center_Top">
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Google_API ?>&callback=initMap" async defer>
        </script>

        <div class="Admin_Garbage_Types_View">
            <div class="main">
                <?php require APPROOT . '/views/admin/admin_sidebar/side_bar.php'; ?>

                <div class="main-right">

                    <div class="main-right-top">
                        <div class="main-right-top-one">
                            <div class="main-right-top-search">
                                <i class='bx bx-search-alt-2'></i>
                                <input type="text" id="searchInput" placeholder="Search">
                            </div>
                            <div class="main-right-top-notification" style="visibility: hidden;" id="notification">
                                <i class='bx bx-bell'></i>
                                <div class="dot"></div>
                            </div>
                            <div id="notification_popup" class="notification_popup">
                                <h1>Notifications</h1>
                                <div class="notification">
                                    <div class="notification-green-dot">

                                    </div>
                                    Request 1232 Has been Cancelled
                                </div>
                                <div class="notification">
                                    <div class="notification-green-dot">

                                    </div>
                                    Request 1232 Has been Assigned
                                </div>
                                <div class="notification">
                                    <div class="notification-green-dot">

                                    </div>
                                    Request 1232 Has been Cancelled
                                </div>
                            </div>
                            <div class="main-right-top-profile">
                                <img src="<?php echo IMGROOT?>/profile-pic.jpeg" alt="">
                                <div class="main-right-top-profile-cont">
                                    <h3>Admin</h3>
                                </div>
                            </div>
                        </div>
                        <div class="main-right-top-two">
                            <h1>Garbage Types</h1>
                        </div>
                        <div class="main-right-top-three">
                            <a href="">
                                <div class="main-right-top-three-content">
                                    <p><b style="color:#1ca557;">View</b></p>
                                    <div class="line"  style="background-color: #1ca557;"></div>
                                </div>
                            </a>
                            <a href="">
                                <div class="main-right-top-three-content">
                                    <p>Add</p>
                                    <div class="line"></div>
                                </div>
                            </a>

                        </div>
                    </div>
                    
                    <div class="main-right-bottom">
                        <div class="main-right-bottom-top ">
                            <table class="table">
                                <tr class="table-header">
                                    <th>Garbage ID</th>
                                    <th>Garbage Type</th>
                                    <th>credits per waste quantity</th>
                                    <th>Approximate Amount</th>
                                    <th>Minimum Amount</th>
                                    <th>Selling Price</th>
                                    <th>Update</th>
                                    
                                </tr>
                            </table>
                        </div>
                        <div class="main-right-bottom-down">
                            <table class="table">
                                <?php foreach($data['garbage_types'] as $garbage_type) : ?>
                                <tr class="table-row">
                                    <td><?php echo $garbage_type->ID?></td>
                                    <td><?php echo $garbage_type->name?></td>
                                    <td><?php echo $garbage_type->credits_per_waste_quantity?></td>
                                    <td><?php echo $garbage_type->approxiamte_amount?></td>
                                    <td><?php echo $garbage_type->minimum_amount?></td>
                                    <td><?php echo $garbage_type->selling_price?></td>
                                    <td><i class='bx bx-refresh' style="font-size: 30px; font-weight:1000px;"
                                        onclick="open_update_popup()"></i></td>
                                    
                                </tr>
                                <?php endforeach; ?>

                        </div>
                    </div>
                  
                </div>
                

                <div class="overlay" id="overlay"></div>

                <div class="update_click" id="update_popup">
                    <div class="popup-form" id="popup">
                        <a href="<?php echo URLROOT?>/admin/garbage_types"><img
                                src="<?php echo IMGROOT?>/close_popup.png" class="update-popup-img" alt=""></a>
                        <h2>Update Details</h2>
                        <center>
                            <div class="update-topic-line"></div>
                        </center>
                        <form class="updatePopupform" method="post"
                            action="<?php echo URLROOT;?>/centermanagers/center_workers_update/<?php echo $data['id'];?>">
                            <div class="updateData A">
                                <label>Name</label><br>
                                <input type="text" name="name" placeholder="Enter name"
                                    value="<?php echo $data['name']; ?>"><br>
                                <div class="error-div" style="color:red">
                                    <?php echo $data['name_err']?>
                                </div>
                            </div>
                            <div class="updateData">
                                <label>NIC</label><br>
                                <input type="text" name="nic" placeholder="Enter NIC"
                                    value="<?php echo $data['nic']; ?>"><br>
                                <div class="error-div" style="color:red">
                                    <?php echo $data['nic_err']?>
                                </div>
                            </div>
                            <div class="updateData">
                                <label>Address</label><br>
                                <input type="text" name="address" placeholder="Enter Address"
                                    value="<?php echo $data['address']; ?>"><br>
                                <div class="error-div" style="color:red">
                                    <?php echo $data['address_err']?>
                                </div>
                            </div>
                            <div class="updateData">
                                <label>Contact No</label><br>
                                <input type="text" name="contact_no" placeholder="Enter Contact No"
                                    value="<?php echo $data['contact_no']; ?>"><br>
                                <div class="error-div" style="color:red">
                                    <?php echo $data['contact_no_err']?>
                                </div>
                            </div>
                            <div class="updateData B">
                                <label>DOB</label><br>
                                <input type="date" name="dob" placeholder="Enter DOB"
                                    value="<?php echo $data['dob']; ?>"><br>
                                <div class="error-div" style="color:red">
                                    <?php echo $data['dob_err']?>
                                </div>
                            </div>

                            <div class="btns1">
                                <button type="submit" class="updatebtn">Update</button>
                                <a href="<?php echo URLROOT?>/centermanagers/center_workers"><button type="button"
                                        class="cancelbtn1">Cancel</button></a>
                            </div>

                        </form>

                    </div>
                </div>

                
            </div>
        </div>

    </div>
</div>
<script>

function open_update_popup(){
    var updatePopup = document.getElementById('update_popup');
    updatePopup.classList.add('active');
    document.getElementById('overlay').style.display = "flex";

}

</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>