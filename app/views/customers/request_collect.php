<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Customer_Main">
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Google_API?>&libraries=places&callback=initMap"
        async defer>
    </script>
    <div class="Customer_Request_collect">
        <script
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo Google_API?>&libraries=places&callback=initMap"
            async defer>
        </script>
        <div class="main">
            <?php require APPROOT . '/views/customers/Customer_SideBar/side_bar.php'; ?>

            <div class="main-right">
                <div class="main-top">
                    <div class="main-right-top-search">
                        <i class='bx bx-search-alt-2'></i>
                        <input type="text" id="searchInput" placeholder="Search">
                    </div>
                    <div class="main-right-top-notification" id="notification">
                        <i class='bx bx-bell'></i>
                        <?php if (!empty($data['notification'])) : ?>
                        <div class="dot"><?php echo count($data['notification'])?></div>
                        <?php endif; ?>
                    </div>
                    <div id="notification_popup" class="notification_popup">
                        <h1>Notifications</h1>
                        <div class="notification_cont">
                            <?php foreach($data['notification'] as $notification) : ?>

                            <div class="notification">
                                <div class="notification-green-dot">

                                </div>
                                <div class="notification_right">
                                    <p><?php echo date('Y-m-d', strtotime($notification->datetime)); ?></p>
                                    <?php echo $notification->notification ?>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>
                        <form class="mark_as_read" method="post"
                            action="<?php echo URLROOT;?>/customers/view_notification/request_collect">
                            <i class="fa-solid fa-check"> </i>
                            <button type="submit">Mark all as read</button>
                        </form>

                    </div>
                    <div class="main-right-top-profile">
                        <img src="<?php echo IMGROOT?>/img_upload/customer/<?php echo $_SESSION['customer_profile']?>"
                            alt="">
                        <div class="main-right-top-profile-cont">
                            <h3><?php echo $_SESSION['user_name']?></h3>
                            <p>ID : C <?php echo $_SESSION['user_id']?></p>
                        </div>
                    </div>
                </div>

                <form id="myForm" class="main-bottom" action="<?php echo URLROOT;?>/customers/request_collect"
                    method="post">

                    <div class="main-bottom-component">
                        <div class="Topic">
                            <h2>Request a Collect</h2>
                            <div class="line"></div>
                        </div>
                        <div class="Content">
                            <div class="Content-Content">
                                <h2>Name</h2>
                                <input value="<?php echo $data['name']?>" name="name" type="text" placeholder="Name">
                                <div class="err"><?php echo $data['name_err']?></div>
                            </div>
                            <div class="Content-Content">
                                <h2>Contact Number</h2>
                                <input value="<?php echo $data['contact_no']?>" name="contact_no" type="text"
                                    placeholder="Contact Number">
                                <div class="err"><?php echo $data['contact_no_err']?></div>
                            </div>
                        </div>
                        <div class="Content">
                            <div class="Content-Content">
                                <input type="hidden" value="<?php echo $data['region_success']?>" name="region_success">
                                <div class="main-bottom-component-right-component">
                                    <h2>Date</h2>
                                    <input value="<?php echo $data['date']?>" name="date" type="date">
                                    <div class="err"><?php echo $data['date_err']?></div>
                                </div>
                            </div>
                            <div class="Content-Content">
                                <h2>Time Slot</h2>
                                <select class="Time" name="time">
                                    <option value="8 am - 10 am"
                                        <?php echo ($data['time'] === '8 am -10 am') ? 'selected' : ''; ?>>8 am -10
                                        am
                                    </option>
                                    <option value="10 am - 12 noon"
                                        <?php echo ($data['time'] === '10 am - 12 noon') ? 'selected' : ''; ?>>10 am
                                        -
                                        12
                                        noon
                                    </option>
                                    <option value="12 noon -2 pm"
                                        <?php echo ($data['time'] === '12 noon -2 pm') ? 'selected' : '12 noon -2 pm'; ?>>
                                        12
                                        noon - 2 pm

                                    </option>
                                    <option value="2 pm - 4 pm"
                                        <?php echo ($data['time'] === '2 pm - 4 pm') ? 'selected' : ''; ?>>2 pm - 4
                                        pm

                                    </option>
                                </select>
                                <div class="err"><?php echo $data['time_err']?></div>
                            </div>
                        </div>
                        <div class="Content">
                            <div class="Content-Content">
                                <h2>Your Region</h2>
                                <input value="<?php echo $data['region']?>" type="text" readonly>
                            </div>
                            <div class="Content-Content">
                                <h2>Pick Up Instructions</h2>
                                <input value="<?php echo $data['instructions']?>" name="instructions" type="Text"
                                    placeholder="Pick Up Instructions">
                                <div class="err"><?php echo $data['instructions_err']?></div>
                            </div>
                        </div>
                        <div class="Content X">
                            <h2>Location</h2>
                            <input type="hidden" id="location_success" value="<?php echo $data['location_success']?>"
                                name="location_success">
                            <?php if ($data['region_success'] == 'True')  ?>
                            <div class="main-bottom-maps" onclick="initMap()">
                                <h4>Maps</h4>
                                <img src="<?php echo IMGROOT; ?>/location2.png" alt="">
                            </div>

                            <?php if ($data['location_success'] == 'Success') : ?>
                            <div class="main-bottom-map-success">
                                <img src="<?php echo IMGROOT; ?>/check.png" alt="">
                                <p>Location Fetched Successfully</p>

                            </div>
                            <?php endif; ?>

                            <?php if ($data['location_err'] == 'Location Error') : ?>
                            <div class="main-bottom-map-success">
                                <img src="<?php echo IMGROOT; ?>/warning.png" alt="">
                                <p>Pick up location Required</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="Waste_Amounts">
                            <div class="Cont">
                                <h3>Plastic</h3>
                                <i class="icon fas fa-box"></i>
                                <p>1 Kg</p>
                            </div>
                            <div class="Cont">
                                <h3>Polythene</h3>
                                <i class="icon fas fa-trash"></i>
                                <p>1 Kg</p>
                            </div>
                            <div class="Cont">
                                <h3>Metal</h3>
                                <i class="icon fas fa-box"></i>
                                <p>1 Kg</p>
                            </div>
                            <div class="Cont">
                                <h3> Glass</h3>
                                <i class="icon fas fa-glass-whiskey"></i>
                                <p>1 Kg</p>
                            </div>

                            <div class="Cont">
                                <h3>Paper</h3>
                                <i class="icon fas fa-file-alt"></i>
                                <p>1 Kg</p>
                            </div>
                            <div class="Cont">
                                <h3>Electronic</h3>
                                <i class="icon fas fa-laptop"></i>
                                <p>1 Kg</p>
                            </div>
                        </div>
                        <div class="Warning_Message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Please have at least one of the above unit
                            of waste for the collection.otherwise,<br>
                            your request
                            will be
                            cancelled with a 50 Eco fine
                        </div>
                        <div class="Submit-botton">
                            <Button type="submit">Request Now</Button>
                        </div>

                        <!-- <div class="main-bottom-component_Main">
                            <div class="main-bottom-component-right-component-topic">
                                <h2>Request a Collect</h2>
                                <div class="line"></div>
                            </div>
                            <div class="main-bottom-component-right-component-main">
                                <div class="main-bottom-component-right-component">
                                    <h2>Name</h2>
                                    <input value="<?php echo $data['name']?>" name="name" type="text"
                                        placeholder="Name">
                                    <div class="err"><?php echo $data['name_err']?></div>
                                </div>
                                <div class="main-bottom-component-right-component" style="margin-left:10px">
                                    <h2>Contact Number</h2>
                                    <input value="<?php echo $data['contact_no']?>" name="contact_no" type="text"
                                        placeholder="Contact Number">
                                    <div class="err"><?php echo $data['contact_no_err']?></div>
                                </div>
                            </div>
                            <div class="main-bottom-component-right-component-main">
                                <input type="hidden" value="<?php echo $data['region_success']?>" name="region_success">
                                <div class="main-bottom-component-right-component">
                                    <h2>Date</h2>
                                    <input value="<?php echo $data['date']?>" name="date" type="date">
                                    <div class="err"><?php echo $data['date_err']?></div>
                                </div>
                                <div class="main-bottom-component-right-component" style="margin-left:10px">
                                    <h2>Time Slot</h2>
                                    <select class="Time" name="time">
                                        <option value="8 am - 10 am"
                                            <?php echo ($data['time'] === '8 am -10 am') ? 'selected' : ''; ?>>8 am -10
                                            am
                                        </option>
                                        <option value="10 am - 12 noon"
                                            <?php echo ($data['time'] === '10 am - 12 noon') ? 'selected' : ''; ?>>10 am
                                            -
                                            12
                                            noon
                                        </option>
                                        <option value="12 noon -2 pm"
                                            <?php echo ($data['time'] === '12 noon -2 pm') ? 'selected' : '12 noon -2 pm'; ?>>
                                            12
                                            noon - 2 pm

                                        </option>
                                        <option value="2 pm - 4 pm"
                                            <?php echo ($data['time'] === '2 pm - 4 pm') ? 'selected' : ''; ?>>2 pm - 4
                                            pm

                                        </option>
                                    </select>
                                    <div class="err"><?php echo $data['time_err']?></div>
                                </div>

                            </div>
                            <div class="main-bottom-component-right-component Y">
                                <h2>Your Region</h2>
                                <input value="<?php echo $data['region']?>" type="text" readonly>
                            </div>
                            <div class="main-bottom-component-right-component Y">
                                <h2>Pick Up Instructions</h2>
                                <input value="<?php echo $data['instructions']?>" name="instructions" type="Text"
                                    placeholder="Pick Up Instructions">
                                <div class="err"><?php echo $data['instructions_err']?></div>
                            </div>
                            <div class="main-bottom-component-right-component Z">
                                <h2>Location</h2>
                                <input type="hidden" id="location_success"
                                    value="<?php echo $data['location_success']?>" name="location_success">
                                <?php if ($data['region_success'] == 'True')  ?>
                                <div class="main-bottom-maps" onclick="initMap()">
                                    <h4>Maps</h4>
                                    <img src="<?php echo IMGROOT; ?>/location2.png" alt="">
                                </div>

                                <?php if ($data['location_success'] == 'Success') : ?>
                                <div class="main-bottom-map-success">
                                    <img src="<?php echo IMGROOT; ?>/check.png" alt="">
                                    <p>Location Fetched Successfully</p>

                                </div>
                                <?php endif; ?>

                                <?php if ($data['location_err'] == 'Location Error') : ?>
                                <div class="main-bottom-map-success">
                                    <img src="<?php echo IMGROOT; ?>/warning.png" alt="">
                                    <p>Pick up location Required</p>
                                </div>
                                <?php endif; ?>



                            </div>
                            <div class="Waste-type-description">
                                a
                            </div>
                            <div class="main-bottom-component-right-component-button">
                                <Button type="submit">Request Now</Button>
                            </div>

                        </div> -->
                    </div>



                    <div class="map_pop" id="mapPopup">
                        <div id="map"></div>
                        <div class="buttons-container" id="submitForm">
                            <button type="button" id="markLocationBtn" onclick="submitForm()">Mark Location</button>
                            <button type="button" id="cancelBtn">Cancel</button>
                            <input type="hidden" id="latitudeInput" value="<?php echo $data['lattitude']?>"
                                name="latitude">
                            <input type="hidden" id="longitudeInput" value="<?php echo $data['longitude']?>"
                                name="longitude">
                        </div>
                    </div>
                    <?php if($data['confirm_collect_pop']=='True') : ?>
                    <div class="confirm_collect_pop">
                        <div class="popup" id="popup">
                            <img src="<?php echo IMGROOT?>/Collector_Dashboard3.jpg" alt="">
                            <h2>Confirm Your Request!</h2>
                            <p> Request will forwarded to our <b><?php echo $data['region']?></b> center</p>
                            <div class="btns">
                                <a href="">
                                    <button type="submit" class="deletebtn"
                                        onclick="document.getElementById('myForm').action='<?php echo URLROOT; ?>/Customers/request_confirm'; document.getElementById('myForm').submit();">Confirm</button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/Customers/request_collect">
                                    <button type="button" class="cancelbtn">Cancel</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($data['success']=='True') : ?>
                    <div class="request_success">
                        <div class="popup" id="popup">
                            <img src="<?php echo IMGROOT?>/check.png" alt="">
                            <h2>Success!!</h2>
                            <p>Request received! Our team is on it.</p>
                            <a href="<?php echo URLROOT?>/customers/request_main"><button type="button">OK</button></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>


            <script>
            $(document).ready(function() {
                $('#centerDropdown').select2();
            });
            </script>

        </div>
    </div>


    <script>
    var map;
    var marker;

    function initMap() {
        var defaultLatLng = {
            lat: <?= !empty($data['lattitude']) ? $data['lattitude'] : 6 ?>,
            lng: <?= !empty($data['longitude']) ? $data['longitude'] : 81.00 ?>
        };

        var centerLatLng = {
            lat: <?= !empty($data['center_lat']) ? $data['center_lat'] : 6 ?>,
            lng: <?= !empty($data['center_long']) ? $data['center_long'] : 81.00 ?>
        };


        var map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLatLng,
            zoom: 12.5
        });

        marker = new google.maps.Marker({
            position: defaultLatLng,
            map: map,
            draggable: true
        });

        var defaultRadius = parseFloat(<?php echo $data['radius']; ?>);
        console.log(defaultRadius);
        var circle = new google.maps.Circle({
            map: map,
            center: centerLatLng,
            radius: defaultRadius,
            fillColor: '#008000',
            fillOpacity: 0.3,
            strokeColor: '#008000',
            strokeOpacity: 0.8,
            strokeWeight: 2
        });

        google.maps.event.addListener(marker, 'dragend', function(event) {
            var newLatLng = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng()
            };
            console.log('New Location:', newLatLng);
        });

    }


    function submitForm() {
        var form = document.getElementById('myForm');
        form.action = "<?php echo URLROOT;?>/customers/request_mark_map";
        form.method = 'post';

        var currentLatLng = {
            lat: marker.getPosition().lat(),
            lng: marker.getPosition().lng()
        };

        document.getElementById('latitudeInput').value = currentLatLng.lat;
        document.getElementById('longitudeInput').value = currentLatLng.lng; //
        document.body.appendChild(form);

        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var mainBottomMaps = document.querySelector('.main-bottom-maps');
        var mapPopup = document.getElementById('mapPopup');

        mainBottomMaps.addEventListener('click', function() {
            mapPopup.style.display = (mapPopup.style.display === 'flex') ? 'none' : 'flex';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var cancelBtn = document.getElementById('cancelBtn');
        var mapPopup = document.getElementById('mapPopup');
        var map = document.getElementById('markLocationBtn');
        cancelBtn.addEventListener('click', function() {
            // Set display property of mapPopup to 'none'
            mapPopup.style.display = 'none';
        });
        map.addEventListener('click', function() {
            // Set display property of mapPopup to 'none'
            mapPopup.style.display = 'none';
        });
    });

    var notification = document.getElementById("notification");
    var notification_pop = document.getElementById("notification_popup");
    notification_pop.style.height = "0px";

    notification.addEventListener("click", function() {
        var isNotificationEmpty = <?php echo json_encode(empty($data['notification'])); ?>;

        if (!isNotificationEmpty) {
            var notificationArraySize = <?php echo json_encode(count($data['notification'])); ?>;
            if (notification_pop.style.height === "0px") {
                if (notificationArraySize >= 3) {
                    notification_pop.style.height = "215px";
                }
                if (notificationArraySize == 2) {
                    notification_pop.style.height = "150px";
                }
                if (notificationArraySize == 1) {
                    notification_pop.style.height = "105px";
                }
                notification_pop.style.visibility = "visible";
                notification_pop.style.opacity = "1";
                notification_pop.style.padding = "7px";
            } else {
                notification_pop.style.height = "0px";
                notification_pop.style.visibility = "hidden";
                notification_pop.style.opacity = "0";
            }
        }
    });
    </script>
    <script src="<?php echo JSROOT?>/Customer.js"> </script>

</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>