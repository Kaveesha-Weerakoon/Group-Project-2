<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Admin_Main">
    <div class="Admin_Collectors_Complains">
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
                        <h1>Complaints</h1>
                    </div>
                    <div class="main-right-top-three">
                        <a href="<?php echo URLROOT?>/Admin/complain_customers">
                            <div class="main-right-top-three-content">
                                <p>Customers</p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/Admin/complain_collectors">
                            <div class="main-right-top-three-content">
                                <p><b style="color:#1ca557;">Collectors</b></p>
                                <div class="line"  style="background-color: #1ca557;"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/Admin/complaint_centers">
                            <div class="main-right-top-three-content">
                                <p>Centers</p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="">
                            <div class="main-right-top-three-content">
                                <p>Credit Discount Agents</p>
                                <div class="line"></div>
                            </div>
                        </a>

                    </div>
                </div>
              
                <div class="main-right-bottom">
                    <div class="main-right-bottom-top">
                        <table class="table">
                            <tr class="table-header">
                                <th>Complaint ID</th>
                                <th>Collector ID</th>
                                <th>Center</th>
                                <th>Date</th>
                                <th>Contact No</th>
                                <th>Collector Name</th>
                                <th>Subject</th>
                                <th>Complaint</th>
                                <th>Delete</th>
                            </tr>
                        </table>
                    </div>
                    <div class="main-right-bottom-down">
                    <table class="table">
                                   <?php foreach($data['complains'] as $complaint) : ?>
                                       <tr class="table-row">
                                           <td>CoC<?php echo $complaint->id?></td>
                                           <td>Co<?php echo $complaint->collector_id?></td>
                                           <td>Cen<?php echo $complaint->center_id?></td>
                                           <td><?php echo $complaint->date?></td>
                                           <td><?php echo $complaint->contact_no?></td>
                                           <td><?php echo $complaint->name?></td>
                                           <td><?php echo $complaint->subject?></td>
                                           <td class="cancel-open"><img src="<?php echo IMGROOT?>/view.png" alt=""></td>             
                                           <td class="cancel-open"><img src="<?php echo IMGROOT?>/delete.png" alt=""></td>
                                        </tr>
                                  <?php endforeach; ?>
                        </table>

                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
