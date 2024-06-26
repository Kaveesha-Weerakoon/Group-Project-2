<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Admin_Main">
    <div class="Admin_Center_Manager">
        <div class="Admin_Center_Manger_View">
            <div class="main">
                <?php require APPROOT . '/views/admin/admin_sidebar/side_bar.php'; ?>

                <div class="main-right">
                    <div class="main-right-top">
                        <div class="main-right-top-one">
                            <div class="main-right-top-search">
                                <i class='bx bx-search-alt-2'></i>
                                <input type="text" id="searchInput" placeholder="Search">
                            </div>


                            <?php require APPROOT . '/views/admin/admin_profile/adminprofile.php'; ?>

                        </div>
                        <div class="main-right-top-two">
                            <h1>Center Managers</h1>
                        </div>
                        <div class="main-right-top-three">
                            <a href="<?php echo URLROOT?>/admin/center_managers">
                                <div class="main-right-top-three-content">
                                    <p><b style="color:#1ca557;">View</b></p>
                                    <div class="line" style="background-color: #1ca557;"></div>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT?>/admin/center_managers_add">
                                <div class="main-right-top-three-content">
                                    <p>Register</p>
                                    <div class="line"></div>
                                </div>
                            </a>

                        </div>
                    </div>


                    <div class="main-right-bottom">
                        <div class="main-right-bottom-top ">
                            <table class="table">
                                <tr class="table-header">
                                    <th>Center Manager ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Assigned</th>
                                    <th>Assigned Center ID</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </tr>
                            </table>
                        </div>
                        <div class="main-right-bottom-down">
                            <table class="table">
                                <?php foreach($data['center_managers'] as $center_manager) : ?>
                                <tr class="table-row">
                                    <td> <?php echo $center_manager->user_id?></td>
                                    <td><img onclick="openpersonaldetails((<?php echo htmlspecialchars(json_encode($center_manager), ENT_QUOTES, 'UTF-8') ?>))"
                                            src="<?php echo IMGROOT?>/img_upload/center_manager/<?php echo $center_manager->image?>"
                                            alt="" class="manager_img"></td>
                                    <td><?php echo $center_manager->name?></td>
                                    <td><?php echo $center_manager->email?></td>
                                    <td> <?php echo $center_manager->assinged?></td>
                                    <td> <?php echo $center_manager->assigned_center_id?></td>

                                    <td class="cancel-open"><a
                                            href="<?php echo URLROOT?>/admin/center_managers_update/<?php echo $center_manager->user_id ?>"><i
                                                class='bx bx-refresh'
                                                style="font-size: 30px; font-weight:1000px;"></i></a></td>
                                    <td class="cancel-open"><a
                                            href="<?php echo URLROOT?>/admin/center_managers_delete_confirm/<?php echo $center_manager->user_id?>"><i
                                                class='bx bxs-trash' style="font-size: 29px;"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>


                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php if($data['confirm_delete']=='True') : ?>
        <div class="delete_confirm">
            <div class="popup" id="popup">
                <img src="<?php echo IMGROOT?>/trash.png" alt="">
                <?php
                        if ($data['assigned']=='No') {
                        echo "<h2>Delete this center manager?</h2>";
                        echo "<p>This action will permanently delete this center manager</p>";
                        }
                        else{
                        echo "<h2>This Action is prohibited</h2>";
                        echo "<p>Center Manager is assisgned to a center</p>";
                        }
                    ?>
                <div class="btns">
                    <?php
                            if ($data['assigned']=='No') {
                                echo '<a href="' . URLROOT . '/Admin/center_managers_delete/' . $data['center_manager_id'] . '"><button type="button" class="deletebtn">Delete</button></a>';
                            }
                        ?>
                    <a href="<?php echo URLROOT?>/Admin/center_managers"><button type="button"
                            class="cancelbtn">Cancel</button></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if($data['success']=='True') : ?>
        <div class="center_manager_success">
            <div class="popup" id="popup">
                <img src="<?php echo IMGROOT?>/check.png" alt="">
                <h2>Success!!</h2>
                <p>Center Manager has been deleted successfully</p>
                <a href="<?php echo URLROOT?>/admin/center_managers"><button type="button">OK</button></a>
            </div>
        </div>
        <?php endif; ?>

        <?php if($data['click_update']=='True') : ?>
        <div class="update_click">
            <div class="popup-form" id="popup">
                <a href="<?php echo URLROOT?>/admin/center_managers"><img src="<?php echo IMGROOT?>/close_popup.png"
                        class="update-popup-img" alt=""></a>
                <h2>Update Details</h2>
                <center>
                    <div class="update-topic-line"></div>
                </center>
                <form class="updatePopupform" method="post"
                    action="<?php echo URLROOT;?>/admin/center_managers_update/<?php echo $data['id'];?>">
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
                        <input type="text" name="nic" placeholder="Enter NIC" value="<?php echo $data['nic']; ?>"><br>
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
                        <input type="date" name="dob" placeholder="Enter DOB" value="<?php echo $data['dob']; ?>"><br>
                        <div class="error-div" style="color:red">
                            <?php echo $data['dob_err']?>
                        </div>
                    </div>

                    <div class="btns1">
                        <button type="submit" class="updatebtn">Update</button>
                        <a href="<?php echo URLROOT?>/admin/center_managers"><button type="button"
                                class="cancelbtn1">Cancel</button></a>
                    </div>

                </form>

            </div>
        </div>

        <?php endif; ?>

        <?php if($data['update_success']=='True') : ?>
        <div class="center_manager_update_success">
            <div class="popup1" id="popup1">
                <img src="<?php echo IMGROOT?>/check.png" alt="">
                <h2>Success!!</h2>
                <p>Center Manager details updated successfully</p>
                <a href="<?php echo URLROOT?>/admin/center_managers"><button type="button">OK</button></a>

            </div>
        </div>
        <?php endif; ?>

        <div class="overlay" id="overlay"></div>

        <div class="personal-details-popup-box" id="personal-details-popup-box">
            <div class="personal-details-popup-form">
                <img src="<?php echo IMGROOT?>/close_popup.png" alt="" class="personal-details-popup-form-close"
                    id="personal-details-popup-form-close">
                <center>
                    <div class="personal-details-topic">Personal Details</div>
                </center>

                <div class="personal-details-popup">
                    <div class="personal-details-left">
                        <!-- <img src="<?php echo IMGROOT?>/img_upload/collector/<?php echo $data['image']?>" class="profile-pic"
                    alt=""> -->
                        <img src="" id="collector_profile_pic" alt="">
                        <p>CM ID: <span id="collector_id"></span></p>
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
                            <span id="collector_name"></span><br>
                            <span id="collector_email"></span><br>
                            <span id="collector_nic"></span><br>
                            <span id="collector_address"></span><br>
                            <span id="collector_contact_no"></span><br>
                            <span id="collector_dob"></span><br>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
function openpersonaldetails(collector) {
    var personalPop = document.getElementById('personal-details-popup-box');
    personalPop.classList.add('active');
    document.getElementById('overlay').style.display = "flex";

    document.getElementById('collector_id').textContent = collector.user_id;
    document.getElementById('collector_profile_pic').src = "<?php echo IMGROOT?>/img_upload/center_manager/" + collector
        .image;
    document.getElementById('collector_name').textContent = collector.name;
    document.getElementById('collector_email').textContent = collector.email;
    document.getElementById('collector_nic').textContent = collector.nic;
    document.getElementById('collector_address').textContent = collector.address;
    document.getElementById('collector_contact_no').textContent = collector.contact_no;
    document.getElementById('collector_dob').textContent = collector.dob;


}
document.addEventListener('DOMContentLoaded', function() {
    var close_personal_details = document.getElementById('personal-details-popup-form-close');
    close_personal_details.addEventListener('click', function() {
        const personal_details = document.getElementById("personal-details-popup-box");
        personal_details.classList.remove('active');
        document.getElementById('overlay').style.display = "none";

    });
});

function searchTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var rows = document.querySelectorAll('.table-row');
    rows.forEach(function(row) {
        var id = row.querySelector('td:nth-child(1)').innerText.toLowerCase();
        var status = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
        var date = row.querySelector('td:nth-child(4)').innerText.toLowerCase();
        var time = row.querySelector('td:nth-child(5').innerText.toLowerCase();
        var center = row.querySelector('td:nth-child(6)').innerText.toLowerCase();

        if (center.includes(input) || id.includes(input) || status.includes(input) || date
            .includes(
                input) || time.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });

}

document.getElementById('searchInput').addEventListener('input', searchTable);
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>