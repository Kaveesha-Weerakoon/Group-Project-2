<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="Collector-sidebar">
    <div class="Collector_assistants-add">
    <div class="main">
    <div class="main-left">
        <div class="main-left-top">
            <img src="<?php echo IMGROOT?>/Logo_No_Background.png" alt="">
            <h1>Eco Plus</h1>
        </div>
        <div class="main-left-middle">
            <a href="<?php echo URLROOT?>/Collectors">
                <div class="main-left-middle-content">
                    <div class="main-left-middle-content-line1"></div>
                    <img src="<?php echo IMGROOT?>/Home.png" alt="">
                    <h2>Dashboard</h2>
                </div>
            </a>
            <a href="../Collector_Requests/Collector_Requests.html">
                <div class="main-left-middle-content">
                    <div class="main-left-middle-content-line1"></div>
                    <img src="<?php echo IMGROOT?>/Request.png" alt="">
                    <h2>Requests</h2>
                </div>
            </a>
            <a href="">
                <div class="main-left-middle-content Collector current">
                    <div class="main-left-middle-content-line"></div>
                    <img src="<?php echo IMGROOT?>/CollectorAssis.png" alt="">
                    <h2>Collector Assistants</h2>
                </div>
            </a>
            <a href="../Collector_Edit_Profile/Collector_EditProfile.html">
                <div class="main-left-middle-content">
                    <div class="main-left-middle-content-line1"></div>
                    <img src="<?php echo IMGROOT?>/EditProfile.png" alt="">
                    <h2>Edit Profile</h2>
                </div>
            </a>
        </div>
        <div class="main-left-bottom">
            <a href="<?php echo URLROOT?>/Collectors/logout">
               <div class="main-left-bottom-content">
                   <img src="<?php echo IMGROOT?>/logout.png" alt="">
                   <p>Log out</p>
                </div>
            </a>
        </div>
    </div>
    <div class="main-right">
        <div class="main-right-top">
            <div class="main-right-top-one">
                <img src="<?php echo IMGROOT?>/Search.png" alt="">
                <input type="text" placeholder="Search">
                <div class="main-right-top-one-content">
                    <p><?php echo $_SESSION['collector_name']?></p>
                    <img src="<?php echo IMGROOT?>/Profile2.png" alt="">
                </div>
            </div>
            <div class="main-right-top-two">
                <h1>Collector Assistants</h1>
            </div>
            <div class="main-right-top-three">
                <a href="<?php echo URLROOT?>/collectors/collector_assistants">
                    <div class="main-right-top-three-content">
                        <p>View</p>
                        <div class="line1"></div>
                    </div>
                </a>
                <a href="">
                    <div class="main-right-top-three-content">
                        <p><b style="color: #1B6652;">Add</b></p>
                        <div class="line"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="main-right-bottom">
            <hr width="100%">
            <h1>Add Collector Assistants</h1>
            <div class="main-right-bottom-content">
                <div class="main-right-bottom-content-content">
                    <h2>Name</h2>
                    <input type="text">
                </div>
                <div class="main-right-bottom-content-content">
                    <h2>NIC</h2>
                    <input type="text">
                </div>
                <div class="main-right-bottom-content-content">
                    <h2>Address</h2>
                    <input type="text">
                </div>
                <div class="main-right-bottom-content-content">
                    <h2>Contact No</h2>
                    <input type="text">
                </div>
                <div class="main-right-bottom-content-content">
                    <h2>DOB</h2>
                    <input type="date">
                </div>
                <div class="main-right-bottom-content-content a">
                    <button>ADD</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
