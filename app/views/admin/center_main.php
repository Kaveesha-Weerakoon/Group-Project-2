<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Admin_Main">
    <div class="Admin_Center_Main">
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Google_API ?>&callback=initMap" async defer>
        </script>
        <div class="main">
            <?php require APPROOT . '/views/admin/admin_sidebar/side_bar.php'; ?>

            <div class="main-right">
                <div class="main-right-top">
                    <a href="<?php echo URLROOT?>/admin/center">
                        <div class="main-right-top-back-button">
                            <i class='bx bxs-chevrons-left'></i>
                        </div>
                    </a>
                    <div class="main-right-top-search">
                        <i class='bx bx-search-alt-2'></i>
                        <input type="text" placeholder="Search">
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

                <!-- <div class="main-top">
            <a href="<?php echo URLROOT?>/admin/center">
              <img class="back-button" src="<?php echo IMGROOT?>/Back.png" alt="" />
            </a>
            <div class="main-top-component">
              <p>Admin</p>
              <img src="<?php echo IMGROOT?>/Requests Profile.png" alt="" />
            </div>
          </div> -->
                <div class="main-bottom">
                    <div class="header-title-wrapper">
                        <div class="header-title">
                            <h1>Analytics</h1>
                            <p>
                                Display analytics about center
                            </p>
                        </div>
                        <div class="center-details-card">
                            <img src="<?php echo IMGROOT?>/facility.png" alt="" />
                            <div class="center-details-card-content">
                                <h2><?php echo $data['center']->region?></h2>
                                <p>Center ID: CEN<?php echo $data['center']->id?></p>
                            </div>
                        </div>
                    </div>

                    <div class="analytics-boxes">
                        <h3 class="section-head">Overview</h3>
                        <div class="analytics">
                            <a
                                href="<?php echo URLROOT?>/Admin/center_main_collectors/<?php echo $data['center']->id?>">
                                <div class="analytic first-analytic">
                                    <div class="analytic-icon">
                                        <img src="<?php echo IMGROOT?>/blue_collector.png" class="icon-image" alt="" />
                                    </div>
                                    <div class="analytic-info">
                                        <h4>Garbage Collectors</h4>
                                        <h1><?php echo $data['no_of_collectors']?></h1>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo URLROOT?>/Admin/center_main_workers/<?php echo $data['center']->id?>">
                                <div class="analytic second-analytic">
                                    <div class="analytic-icon">
                                        <img src="<?php echo IMGROOT?>/red_worker.png" class="icon-image" alt="" />
                                    </div>
                                    <div class="analytic-info">
                                        <h4>Center workers</h4>
                                        <h1><?php echo $data['no_of_workers']?></h1>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo URLROOT?>/Admin/incoming_requests/<?php echo $data['center']->region?>">
                                <div class="analytic third-analytic">
                                    <div class="analytic-icon">
                                        <img src="<?php echo IMGROOT?>/green_requests.png" class="icon-image" alt="" />
                                    </div>
                                    <div class="analytic-info">
                                        <h4>Total Requests</h4>
                                        <h1><?php echo $data['total_requests']?></h1>
                                    </div>
                                </div>
                            </a>

                            <a href="">
                                <div class="analytic fourth-analytic">
                                    <div class="analytic-icon">
                                        <img src="<?php echo IMGROOT?>/brown_bin.png" class="icon-image" alt="" />
                                    </div>
                                    <div class="analytic-info">
                                        <h4>Garbage Stock</h4>
                                        <h1>20.7 <small>kg</small></h1>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>

                    <div class="details-cards">
                        <div class="details-cards-right">
                            <div class="center-manager-card">
                                <h3 class="section-subhead">Center Manager</h3>
                                <div class="center-manager-content">
                                    <img src="<?php echo IMGROOT?>/img_upload/center_manager/<?php echo $data['center_manager']->image?>"
                                        alt="" />
                                    <div class="center-manager-info">
                                        <h3><?php echo $data['center']->center_manager_name?></h3>
                                        <h1>Manager ID: CM<?php echo $data['center']->center_manager_id?></h1>
                                    </div>
                                    <div class="center-manager-change">
                                        <a
                                            href="<?php echo URLROOT?>/admin/center_main_change_cm/<?php echo $data['center']->id?>">
                                            <button>Change</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details-cards-left">
                            <div class="graph-card">
                                <h3 class="section-subhead">Requests Statistics</h3>
                                <div class="graph-container">
                                    <div class="donut-chart-block">
                                        <div class="donut-chart">
                                            <div id="part1" class="portion-block">
                                                <div class="circle"></div>
                                            </div>
                                            <div id="part2" class="portion-block">
                                                <div class="circle"></div>
                                            </div>
                                            <div id="part3" class="portion-block">
                                                <div class="circle"></div>
                                            </div>
                                            <div id="part4" class="portion-block">
                                                <div class="circle"></div>
                                            </div>
                                            <p class="donut-chart-center"></p>
                                        </div>
                                    </div>
                                    <div class="donut-chart-details">
                                        <ul class="donut-chart-details-list">
                                            <li class="incoming">
                                                <hr />
                                                incoming
                                            </li>
                                            <li class="assigned">
                                                <hr />
                                                assigned
                                            </li>
                                            <li class="completed">
                                                <hr />
                                                completed
                                            </li>
                                            <li class="cancelled">
                                                <hr />
                                                cancelled
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="location-map-card">
                                <h3 class="section-subhead">Center Location</h3>
                                <div class="center-map-container">
                                    <div class="center-location-map-container">
                                        <!-- <img src="<?php echo IMGROOT?>/center-location.png" alt=""> -->
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>


        </div>
        <?php if($data['change_cm']=='True') : ?>
        <div class="change_center_manager_pop">
            <div class="change_center_manager">
                <form action="<?php echo URLROOT;?>/admin/center_main_change_cm/<?php echo $data['center']->id?>"
                    method="post">
                    <label for="centerManager">Selelct Center Manager</label>
                    <select name="centerManager" id="centerManager">
                        <?php
                $centerManagers = $data['not_assigned_cm'];
                if (!empty($centerManagers)) {
                    foreach ($centerManagers as $manager) {
                        echo "<option value=\"$manager->id\">CM $manager->id</option>";
                    }
                } else {
                    echo "<option value=\"default\">No Center Managers Available</option>";
                }
                ?>
                    </select>
                    <button type="submit">Change Center Manager</button>
                    <a
                        href="<?php echo URLROOT?>/admin/center_main/<?php echo $data['center']->id?>/<?php echo $data['center']->region?>"><button
                            type="button" class="cancel">Cancel</button></a>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
<script>
function initMap(latitude, longitude) {
    var mapCenter = {
        lat: latitude,
        lng: longitude
    };

    var map = new google.maps.Map(document.querySelector('.center-location-map-container'), {
        center: mapCenter,
        zoom: 10
    });

    var marker = new google.maps.Marker({
        position: {
            lat: parseFloat(latitude),
            lng: parseFloat(longitude)
        },
        map: map,
        title: 'Marked Location'
    });
}

document.addEventListener('DOMContentLoaded', function() {

    var latitude = <?php echo $data['center']->lat; ?>;
    var longitude = <?php echo $data['center']->longi; ?>;

    if (typeof google === 'object' && typeof google.maps === 'object') {
        // If loaded, call initMap immediately
        initMap(latitude, longitude);
    } else {
        // If not loaded, set a callback to initMap when the API is loaded
        window.initMap = function() {
            initMap(latitude, longitude);
        };
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>