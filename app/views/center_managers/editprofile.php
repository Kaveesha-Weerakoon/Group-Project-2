<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="CenterManager_Main">
     <div class="CenterManager_EditProfile">
     <div class="main" >
            <?php require APPROOT . '/views/center_managers/centermanager_sidebar/side_bar.php'; ?>

            <div class="main-right">
                <div class="main-right-top">
                    <div class="main-right-top-search">
                        <i class='bx bx-search-alt-2'></i>
                        <input type="text" id="searchInput" placeholder="Search">
                    </div>
                    <?php require APPROOT . '/views/center_managers/centermanager_notifications/centermanager_notifications.php'; ?>
                    

                </div>
                <div class="main-right-bottom">
                    <div class="main-right-bottom-content" >
                        <div class="main-right-bottom-content-top">
                            <h1>Edit Profile</h1>
                            <div class="Edit-Profile-line"></div>
                        </div>
                        <div class="top-profile" >
                            <form action="<?php echo URLROOT;?>/centermanagers/editprofile" method="post" enctype="multipart/form-data">
                                <div class="edit-profile-content-profile">
                                    <div class="edit-profile-content-profile-container">
                                        <img class="edit-profile-main-image" id="profile_image_placeholder" src="<?php echo IMGROOT?>/img_upload/center_manager/<?php echo $_SESSION['cm_profile']?>" alt="">
                                        <img class="edit-profile-second-image" src="<?php echo IMGROOT?>/edit-icon.png" alt="">
                                        <input name='profile_image' type="file" id="profile_image">
                                    </div>
                                </div>
                                <div class="main-right-bottom-content-bottom">
                                    <div class="main-right-bottom-content-bottom-left">
                                        <div class="edit-profile-content">
                                            <h3>Name :</h3>
                                            <input name="name" type="text" value="<?php echo $data['name']?>">
                                            <div class="err"><?php echo $data['name_err']?></div>
                                        </div>
                                        <div class="edit-profile-content">
                                            <h3>Email </h3>
                                            <input name="email" type="text" value="<?php echo $data['email']?>" readonly>
                                            <div class="err"></div>

                                        </div>
                                        <div class="edit-profile-content">
                                            <h3>Address :</h3>
                                            <input name="address"  type="text" value="<?php echo $data['address']?>">
                                            <div class="err"><?php echo $data['address_err']?></div>
                                        </div>
                                        <div class="edit-profile-content">
                                            <h3>Contact Number :</h3>
                                            <input name="contactno" type="text" value="<?php echo $data['contactno']?>">
                                            <div class="err"><?php echo $data['contactno_err']?></div>
                                        </div>
                                                                
                                        <button type="submit">Save</button> 

                                    </div>
                               
                                                                                   
                            </form>

                            <form class="main-right-bottom-content-bottom-right" action="<?php echo URLROOT;?>/centermanagers/change_password" method="post">
                                <div class="edit-profile-content">
                                    <h3>Current Password :</h3>
                                    <input type="password" name="current" value="<?php echo $data['current']?>">
                                    <div class="err"><?php echo $data['current_err']?></div>
                                </div>
                                <div class="edit-profile-content">
                                    <h3>New Password :</h3>
                                    <input type="password" name="new_pw" value="<?php echo $data['new_pw']?>">                       
                                    <div class="err"><?php echo $data['new_pw_err']?></div>

                                </div>
                                <div class="edit-profile-content">
                                    <h3> Re-enter Password :</h3>
                                    <input type="password" name="re_enter_pw" value="<?php echo $data['re_enter_pw']?>">
                                    <div class="err"><?php echo $data['re_enter_pw_err']?></div>

                                </div> <button type=submit>Change Password</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <?php if($data['change_pw_success']=='True') : ?>
               <div class="center_worker_success">
                  <div class="popup" id="popup">
                    <img src="<?php echo IMGROOT?>/check.png" alt="">
                    <h2>Success!!</h2>
                    <p><?php echo $data['success_message']?></p>
                    <a href="<?php echo URLROOT?>/centermanagers/editprofile"><button type="button" >OK</button></a>

                  </div>
               </div>
             <?php endif; ?>
     </div>   
      <script src="<?php echo JSROOT?>/Collector_Edit_Profile.js"> </script>        

</div>
<script>

/*animation*/
document.addEventListener("DOMContentLoaded", function() {
    var mainRightBottomContent = document.querySelector('.main-right-bottom-content');

    function checkSlide() {
        var elementTop = mainRightBottomContent.getBoundingClientRect().top;
        var isHalfShown = elementTop < window.innerHeight;
        var isNotScrolledPast = window.scrollY < elementTop + mainRightBottomContent.clientHeight;

        if (isHalfShown && isNotScrolledPast) {
            mainRightBottomContent.classList.add('slide-in');
        } else {
            mainRightBottomContent.classList.remove('slide-in');
        }
    }

    window.addEventListener('scroll', checkSlide);
    checkSlide(); // Trigger once on page load
});

/* Notification View */
document.getElementById('submit-notification').onclick = function() {
    var form = document.getElementById('mark_as_read');
    var dynamicUrl = "<?php echo URLROOT;?>/centermanagers/view_notification/editprofile";
    form.action = dynamicUrl; // Set the action URL
    form.submit(); // Submit the form

};
/* ----------------- */
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
