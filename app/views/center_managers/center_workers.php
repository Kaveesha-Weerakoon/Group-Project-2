<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="Center_manager-centerworkers">
    <div class="main">
        <div class="main-top">
            <a href="<?php echo URLROOT?>/centermanagers">
                <img class="back-button" src="<?php echo IMGROOT?>/Back.png" alt="">
            </a>
            <div class="main-top-component">
                <p>Ananda Perera</p>
                <img src="<?php echo IMGROOT?>/Requests Profile.png" alt="">
            </div>
        </div>
        <div class="main-bottom">
            <div class="main-bottom-top">
                <div class="main-right-top-two">
                    <h1>Center Workers</h1>
                </div>
                <div class="main-right-top-three">
                    <a href="Collectors.html">
                        <div class="main-right-top-three-content">
                            <p><b style="color: #1B6652;">View</b></p>
                            <div class="line"></div>
                        </div>
                    </a>
                    <a href="<?php echo URLROOT?>/centermanagers/center_workers_add">
                        <div class="main-right-top-three-content">
                            <p>Add</p>
                            <div class="line1"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="main-bottom-down">
                <div class="main-right-bottom-top">
                    <table class="table">
                        <tr class="table-header">
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>DOB</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </table>
                </div>
                <div class="main-right-bottom-down">
                    <table class="table">
                        <tr class="table-row">
                            <td>Samantha</td>
                            <td>1212121212</td>
                            <td>Colombo</td>
                            <td>077-4567890</td>
                            <td>299.87.23</td>
                            <td><img src="<?php echo IMGROOT?>/update.png" alt=""></td>
                            <td class="delete"><img src="<?php echo IMGROOT?>/delete.png" alt=""></td>
                        </tr>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>
