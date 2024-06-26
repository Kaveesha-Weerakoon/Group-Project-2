<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Customer_Main">

    <div class="Customer_Request_Main">
        <div class="Customer_Request_Ongoing">
            <div class="main">

                <?php require APPROOT . '/views/customers/Customer_SideBar/side_bar.php'; ?>


                <div class="main-right">
                    <?php require APPROOT . '/views/customers/customer_request/customer_request_top.php'; ?>

                    <?php if(!empty($data['request'])) : ?>
                    <div class="main-right-bottom">
                        <div class="main-right-bottom-top">
                            <table class="table">
                                <tr class="table-header">
                                    <th>Req ID</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Center</th>
                                    <th>Collector</th>
                                    <th>Location</th>
                                    <th>Verify</th>
                                    <th>Cancel</th>
                                </tr>
                            </table>
                        </div>
                        <div class="main-right-bottom-down">
                            <table class="table" id="dataTable">
                                <?php foreach($data['request'] as $request) : ?>
                                <tr class="table-row">
                                    <td><?php echo $request->request_id?></td>
                                    <td>
                                        <?php
                                          $typeContent = ($request->type === 'incoming') ? 
                                         '<i class="fa-solid fa-spinner processing"></i><p class="bold1" style="color:#414143"">Pending</p>' :
                                         ($request->status === 'ontheway' ?
                                            '<i class="fa-solid fa-truck-arrow-right on-the-way"></i><p class="bold3">On the way</p>' :
                                            '<i class="fa-solid fa-truck-arrow-right assigned"></i><p class="bold2">Assigned</p>');
 
                                        echo $typeContent;
                                    ?>
                                    </td>

                                    </td>
                                    <td><?php echo $request->date?></td>
                                    <td><?php echo $request->time?></td>
                                    <td><?php echo $request->region?></td>

                                    <td>
                                        <?php
                                             $typeContent = ($request->type === 'assigned') ? 
                                            '<img class="collector_img" src="' . IMGROOT . '/img_upload/collector/' .$request->image . '" alt="collector image"
                                             onclick="view_collector(\'' . $request->image . '\',
                                             \'' . $request->user_id . '\', \'' . $request->name . '\',
                                             \'' . $request->contact_no . '\', \'' . $request->vehicle_no . '\',
                                             \'' . $request->vehicle_type . '\')">' :
                                             '<i class="fa-solid fa-user-large"></i>';
                                             echo $typeContent;
                                             ?>

                                    </td>
                                    <td>

                                        <i onclick="viewLocation(<?php echo $request->lat; ?>, <?php echo $request->longi; ?>)"
                                            class='bx bx-map' style="font-size: 29px"></i>
                                    </td>

                                    <td>

                                        <?php
                                            $typeContent = ($request->code === 0 || $request->code === null) ?
                                            '<i class="fa-solid fa-ban" style="font-size: 21px;"></i>' :
                                            '<i class="fa-regular fa-circle-check" style="font-size: 21px;" onclick="verify(' . htmlspecialchars(json_encode($request), ENT_QUOTES, 'UTF-8') . ')"></i>';
                                            echo $typeContent;
                                        ?>

                                    </td>
                                    <td class=" cancel-open">
                                        <?php
                                                if ($request->type === 'incoming') {
                                                    echo '   <i class="bx bx-x-circle" style="font-size: 29px; color:#DC2727;" onclick="cancel_request(\'' . $request->request_id . '\')"></i>';
                                                } else {
                                                    echo '<i class="fa-solid fa-triangle-exclamation" style="font-size: 24px; color:#DC2727;" onclick="cancel_request2(\'' . $request->request_id . '\')"></i>';

                                                }
                                                ?>
                                    </td>

                                </tr>
                                <?php endforeach; ?>

                            </table>

                        </div>
                    </div>
                    <?php else: ?>
                    <div class="main-right-bottom-two">
                        <div class="main-right-bottom-two-content">
                            <i class='bx bx-data' style="font-size: 150px"></i>
                            <h1>You Have No Ongoing Requests</h1>
                            <p>Request a Collect Now!</p>
                            <a href="<?php echo URLROOT?>/customers/request_collect"><button>Request</button></a>

                        </div>
                    </div>
                    <?php endif; ?>


                </div>

                <div class="delete_confirm" id="cancel_confirm">
                    <div class="popup" id="popup">
                        <img src="<?php echo IMGROOT?>/exclamation.png" alt="">
                        <h2>Cancel the Request?</h2>
                        <p>This action will cancel the request </p>
                        <div class="btns">
                            <a id="cancelLink"><button type="button" class="deletebtn">Confirm</button></a>
                            <a id="close_cancel"><button type="button" class="cancelbtn">Cancel</button></a>
                        </div>
                    </div>
                </div>
                <div class="delete_confirm" id="cancel_confirm2">
                    <div class="popup" id="popup">
                        <img src="<?php echo IMGROOT?>/exclamation.png" alt="">
                        <h2>Cancel the Request?</h2>
                        <p>Canceling incurs a fine. Minimize frequent actions for uninterrupted service</p>

                        <div class="btns">
                            <a id="cancelLink2"><button type="button" class="deletebtn">Confirm</button></a>
                            <a id="close_cancel2"><button type="button" class="cancelbtn">Cancel</button></a>
                        </div>
                    </div>
                </div>
                <div class="verify" id="verify">
                    <div class="popup" id="popup">
                        <h2>Verification Code</h2>
                        <i class="fa-solid fa-circle-check"></i>
                        <h3 id="verify_code">36253431</h3>
                        <p>Please review all the details entered by the collector and provide the verification code</p>
                        <p>If any errors occurred, please refresh the page and try again to provide the verification
                            code.</p>

                        <div class="btns">
                            <button type="button" id="close_verify" class="cancelbtn">Okay</button>
                        </div>
                    </div>
                </div>

                <div class=" personal-details-popup-box" id="personal-details-popup-box">
                    <div class="personal-details-popup-form" id="popup">
                        <img src="<?php echo IMGROOT?>/close_popup.png" alt="" class="personal-details-popup-form-close"
                            id="personal-details-popup-form-close">
                        <center>
                            <div class="personal-details-topic">Collector Details</div>
                        </center>

                        <div class="personal-details-popup">
                            <div class="personal-details-left">
                                <img id="collector_profile_img" src="<?php echo IMGROOT?>/img_upload/collector/?>"
                                    class="profile-pic" alt="">
                                <p>Collector ID: <span id="collector_id">C<?php?></span></p>
                            </div>
                            <div class="personal-details-right">
                                <div class="personal-details-right-labels">
                                    <span>Name</span><br>
                                    <span>Contact No</span><br>
                                    <span>Vehicle No</span><br>
                                    <span>Vehicle Type</span><br>

                                </div>
                                <div class="personal-details-right-values">
                                    <span id="collector_name"></span><br>
                                    <span id="collector_conno"></span><br>
                                    <span id="collector_vehicle_no"></span><br>
                                    <span id="collector_vehicle_type"></span><br>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="overlay" id="overlay"></div>

            </div>
        </div>
    </div>
    <script>
    function verify($request) {
        var verifyPop = document.querySelector('.verify');
        verifyPop.classList.add('active');
        document.getElementById('verify_code').textContent = $request.code;
        document.getElementById('overlay').style.display = "flex";
    }

    function view_collector(image, col_id, name, contact_no, type, vehno) {
        var locationPop = document.querySelector('.personal-details-popup-box');
        locationPop.classList.add('active');
        document.getElementById('overlay').style.display = "flex";

        document.getElementById('collector_profile_img').src = '<?php echo IMGROOT ?>/img_upload/collector/' + image;
        document.getElementById('collector_id').innerText = col_id;
        document.getElementById('collector_name').innerText = name;
        document.getElementById('collector_conno').innerText = contact_no;
        document.getElementById('collector_vehicle_no').innerText = vehno;
        document.getElementById('collector_vehicle_type').innerText = type;
    }

    function cancel_request(id) {
        var newRequestId = id;
        var newURL = "<?php echo URLROOT?>/customers/cancel_request/" + newRequestId;
        document.getElementById('cancelLink').href = newURL;
        document.getElementById('overlay').style.display = "flex";

        document.getElementById('cancel_confirm').classList.add('active');
    }

    function cancel_request2(id) {
        var newRequestId = id;
        var newURL = "<?php echo URLROOT?>/customers/cancel_request/" + newRequestId;
        document.getElementById('cancelLink2').href = newURL;
        document.getElementById('overlay').style.display = "flex";

        document.getElementById('cancel_confirm2').classList.add('active');
    }

    function initMap(latitude, longitude) {
        var mapCenter = {
            lat: latitude,
            lng: longitude
        };

        var map = new google.maps.Map(document.querySelector('.location_pop_map'), {
            center: mapCenter,
            zoom: 12.5
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

    function viewLocation($lattitude, $longitude) {
        initMap($lattitude, $longitude);
        var locationPop = document.querySelector('.location_pop');
        locationPop.classList.add('active');
        document.getElementById('overlay').style.display = "flex";
    }

    function closemap() {
        var locationPop = document.querySelector('.location_pop');
        locationPop.classList.remove('active');
        document.getElementById('overlay').style.display = "none";
    }

    function searchTable() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var rows = document.querySelectorAll('.table-row');
        rows.forEach(function(row) {
            var id = row.querySelector('td:nth-child(1)').innerText.toLowerCase();
            var status = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
            var date = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
            var time = row.querySelector('td:nth-child(4)').innerText.toLowerCase();
            var center = row.querySelector('td:nth-child(5)').innerText.toLowerCase();

            if (center.includes(input) || id.includes(input) || status.includes(input) || date.includes(
                    input) || time.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });

    }

    document.getElementById('searchInput').addEventListener('input', searchTable);
    document.addEventListener("DOMContentLoaded", function() {
        const close_collector = document.getElementById("personal-details-popup-form-close");
        const collector_view = document.getElementById("personal-details-popup-box");
        const close_cancel = document.getElementById("close_cancel");
        const close_cancel2 = document.getElementById("close_cancel2");
        const close_verify = document.getElementById("close_verify");

        close_collector.addEventListener("click", function() {
            collector_view.classList.remove('active');
            document.getElementById('overlay').style.display = "none";
        });

        close_cancel.addEventListener("click", function() {
            document.getElementById('cancel_confirm').classList.remove('active');
            document.getElementById('overlay').style.display = "none";
        });

        close_cancel2.addEventListener("click", function() {
            document.getElementById('cancel_confirm2').classList.remove('active');
            document.getElementById('overlay').style.display = "none";
        });

        close_verify.addEventListener("click", function() {
            document.getElementById('verify').classList.remove('active');
            document.getElementById('overlay').style.display = "none";
        });

    });

    /* Notification View */
    document.getElementById('submit-notification').onclick = function() {
        var form = document.getElementById('mark_as_read');
        var dynamicUrl = "<?php echo URLROOT;?>/customers/view_notification/request_main";
        form.action = dynamicUrl; // Set the action URL
        form.submit(); // Submit the form

    };
    /* ----------------- */
    </script>
    <?php require APPROOT . '/views/inc/footer.php'; ?>