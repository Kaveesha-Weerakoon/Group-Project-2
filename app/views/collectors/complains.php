<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Collector_Main">
  <div class="Collector_Complains">
    <div class="main">
      <?php require APPROOT . '/views/collectors/collector_sidebar/side_bar.php'; ?>
            <div class="main-right">
              <div class="main-right-top">
                    <div class="main-right-top-search">
                        <i class='bx bxl-sketch'></i> <input type="text" placeholder="Welcome Back !" id="searchInput"
                            readonly oninput="highlightMatchingText()">
                    </div>
                    <div class="main-right-top-notification" id="notification">
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
                        <img src="<?php echo IMGROOT?>/img_upload/collector/<?php echo $_SESSION['collector_profile']?>"
                            alt="">
                        <div class="main-right-top-profile-cont">
                        <h3><?php echo $_SESSION['collector_name']?></h3>
                            <p>ID : C <?php echo $_SESSION['collector_id']?></p>
                        </div>
                    </div>
                </div>
                <div class="main-bottom">
                    <div class="main-bottom-component">
                        <form class="main-bottom-component-left" action="<?php echo URLROOT;?>/collectors/complains"
                            method="post">
                            <div class="main-bottom-component-left-topic">
                                <h2>Make a Complain</h2>
                                <div class="line"></div>
                            </div>

                            <div class="main-bottom-component-left-component">
                                <h2>Name</h2>
                                <input value="<?php echo $data['name']; ?>" type="text" name="name" placeholder="Name">
                                <div class="err"><?php echo $data['name_err']; ?></div>
                            </div>

                            <div class="main-bottom-component-left-component">
                                <h2>Contact Number</h2>
                                <input value="<?php echo $data['contact_no']; ?>" name="contact_no" type="text"
                                    placeholder="Contact Number">
                                <div class="err"><?php echo $data['contact_no_err']; ?></div>
                            </div>

                            <div class="main-bottom-component-left-component">
                                <h2>Region</h2>
                                <input type="text" value="<?php echo $data['region']; ?>" name="region"
                                    placeholder="Region">
                                <div class="err"><?php echo $data['region_err']; ?></div>
                            </div>

                            <div class="main-bottom-component-left-component">
                                <h2>Subject</h2>
                                <input value="<?php echo $data['subject']; ?>" name="subject" type="text"
                                    placeholder="Subject">
                                <div class="err"><?php echo $data['subject_err']; ?></div>
                            </div>

                            <div class="main-bottom-component-left-component">
                                <h2>Complain</h2>
                                <input value="<?php echo $data['complain']; ?>" name="complain" type="text"
                                    placeholder="Complain">
                                <div class="err"><?php echo $data['complain_err']; ?></div>
                            </div>
                            <div class="main-bottom-component-left-button">
                                <button type="submit">Make Complain</button>
                            </div>
                        </form>
                        <div class="main-bottom-component-right">
                            <img src="<?php echo IMGROOT?>/makeComplaints.png" alt="" />
                        </div>

                    </div>
                </div>
                <?php if($data['completed']=='True') : ?>
                <div class="complain_success" style="">
                    <div class="popup" id="popup">
                        <img src="<?php echo IMGROOT?>/check.png" alt="">
                        <h2>Success!!</h2>
                        <p>Complain has been reported successfully</p>
                        <a href="<?php echo URLROOT?>/collectors/history_complains"><button type="button">OK</button></a>


                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
  </div>
    
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>