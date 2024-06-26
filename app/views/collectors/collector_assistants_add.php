<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="Collector_Main">

    <div class="Collector_assistant_add">
        <div class="main">
            <?php require APPROOT . '/views/collectors/collector_sidebar/side_bar.php'; ?>
            <div class="main-right">
                <div class="main-right-top">
                    <div class="main-right-top-one">
                        <div class="main-right-top-search">
                            <i class='bx bx-search-alt-2'></i>
                            <input type="text" id="searchInput" placeholder="Search">
                        </div>
                        <?php require APPROOT . '/views/collectors/collector_notification/collector_notification.php'; ?>
                    </div>
                    <div class="main-right-top-two">
                        <h1>Collector Assistants</h1>
                    </div>
                    <div class="main-right-top-three">
                        <a href="<?php echo URLROOT?>/collectors/collector_assistants">
                            <div class="main-right-top-three-content" id="view">
                                <p>View</p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="">
                            <div class="main-right-top-three-content" id="add">
                                <p><b style="color: #1ca557;">Register</b></p>
                                <div class="line" style="background-color: #1ca557;"></div>
                            </div>
                        </a>

                    </div>
                </div>

                <div class="main-bottom-down">
                    <div class="form-container">
                        <div class="form-title">Registration form</div>
                        <form action="<?php echo URLROOT;?>/collectors/collector_assistants_add"
                            class="main-right-bottom-content" method="post">
                            <div class="user-details">
                                <div class="main-right-bottom-content-content">
                                    <span class="details">Name</span>
                                    <input name="name" type="text" placeholder="Enter Name"
                                        value="<?php echo $data['name'];?>">
                                    <div class="error-div" style="color:red">
                                        <?php echo $data['name_err']?>
                                    </div>
                                </div>
                                <div class="main-right-bottom-content-content">
                                    <span class="details">NIC</span>
                                    <input name="nic" type="text" placeholder="Enter NIC"
                                        value=<?php echo $data['nic'];?>>
                                    <div class="error-div" style="color:red">
                                        <?php echo $data['nic_err']?>
                                    </div>
                                </div>
                                <div class="main-right-bottom-content-content">
                                    <span class="details">DOB</span>
                                    <input name="dob" type="date" placeholder="Enter DOB"
                                        value="<?php echo $data['dob']?>">
                                    <div class="error-div" style="color:red">
                                        <?php echo $data['dob_err']?>
                                    </div>
                                </div>
                                <div class="main-right-bottom-content-content">
                                    <span class="details">Contact No</span>
                                    <input name="contact_no" type="text" placeholder="Enter Contact No"
                                        value="<?php echo $data['contact_no']?>">
                                    <div class="error-div" style="color:red">
                                        <?php echo $data['contact_no_err']?>
                                    </div>
                                </div>
                                <div class="main-right-bottom-content-content A">
                                    <span class="details">Address</span>
                                    <input name="address" type="text" placeholder="Enter Address"
                                        value="<?php echo $data['address']?>">
                                    <div class="error-div" style="color:red">
                                        <?php echo $data['address_err']?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-button">
                                <button type="submit">REGISTER</button>
                            </div>
                        </form>
                    </div>
                    <?php if($data['registered']=='True') : ?>
                    <div class="center_worker_success">
                        <div class="popup" id="popup">
                            <img src="<?php echo IMGROOT?>/check.png" alt="">
                            <h2>Success!!</h2>
                            <p>Collector Assistant has been registered successfully</p>
                            <a href="<?php echo URLROOT?>/collectors/collector_assistants"><button
                                    type="button">OK</button></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    /* Notification View */
document.getElementById('submit-notification').onclick = function() {
    var form = document.getElementById('mark_as_read');
    var dynamicUrl = "<?php echo URLROOT;?>/collector/view_notification/collector_assistants_add";
    form.action = dynamicUrl; // Set the action URL
    form.submit(); // Submit the form

};
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>