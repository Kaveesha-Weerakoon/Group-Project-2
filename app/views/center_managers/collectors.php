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
                                <p>Add</p>
                                <div class="line1"></div>
                            </div>
                        </a>
                        <a  href="<?php echo URLROOT?>/centermanagers/collectors_complains">
                            <div class="main-right-top-three-content">
                                <p>Complains</p>
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
                                <th>Name</th>
                                <th>NIC</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>DOB</th>
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
                                <td><?php echo $collector->name?></td>
                                <td><?php echo $collector->nic?></td>
                                <td><?php echo $collector->email?></td>
                                <td><?php echo $collector->address?></td>
                                <td><?php echo $collector->contact_no?></td>
                                <td><?php echo $collector->dob?></td>
                                <td><img src="<?php echo IMGROOT ?>/View.png" alt=""></td>
                                <td><img src="<?php echo IMGROOT ?>/update.png" alt=""></td>
                                <td class="delete"><a href="<?php echo URLROOT?>/centermanagers/collector_delete_confirm/<?php echo $collector->user_id ?>"> <img src="<?php echo IMGROOT ?>/delete.png" alt=""></a></td>

                            </tr>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
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

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>