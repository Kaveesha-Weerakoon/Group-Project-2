<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="Collector_Main">

    <div class="Collector_Dashboard">

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
                        <?php if (!empty($data['notification'])) : ?>
                        <div class="dot"></div>
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

                                    <?php echo $notification->notification?>

                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>
                        <form class="mark_as_read" method="post" action="<?php echo URLROOT;?>/collectors/">
                            <i class="fa-solid fa-check"> </i>
                            <button type="submit">Mark all as read</button>
                        </form>

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
                <div class="main-right-bottom">
                    <div class="main-right-bottom-one">
                        <div class="main-right-bottom-one-left">
                            <div class="left">
                                <h1>Assigned Requests</h1>
                                <h3><?php echo $data['assinged_Requests_count']?></h3>
                                <p>Last Update</p>
                                <button onclick="redirectToAssignedRequests()">view </button>

                            </div>

                            <div class="right">
                                <div class="icon_container">
                                    <i class='bx bxs-bank' style='font-size: 50px;'></i>
                                </div>
                                <h1><?php echo $data['collector']->center_name?></h1>
                                <h5>Center ID: CEN <?php echo $data['collector']->center_id?> </h5>
                            </div>
                        </div>
                        <div class="main-right-bottom-one-right">

                            <canvas id="myChart" width="688" height="300"></canvas>
                        </div>
                    </div>
                    <div class="main-right-bottom-two">
                        <div class="main-right-bottom-two-cont A" id="credit_per_waste_quantity">
                            <div class="icon_container">
                                <i class='bx bx-dollar-circle'></i>
                            </div>
                            <h3>Credits per Waste Quantity</h3>
                        </div>
                        <div class="main-right-bottom-two-cont A">
                            <div class="icon_container">
                                <i class='bx bx-money-withdraw'></i>
                            </div>
                            <h3>Eco Credit Value</h3>
                        </div>
                        <div class="main-right-bottom-two-cont A" onclick="redirect_complains()">
                            <div class="icon_container">
                                <i class='bx bxs-bank'></i>
                            </div>
                            <h3>Complaints</h3>
                        </div>
                        <div class="main-right-bottom-two-cont A" onclick="redirect_complains_history()">
                            <div class="icon_container">
                                <i class='bx bx-donate-heart'></i>
                            </div>
                            <h3>Complaints History</h3>
                        </div>

                    </div>
                    <div class="main-right-bottom-three">
                        <div class="main-right-bottom-three-left">
                            <h1>Recently Completed Request</h1>

                            <?php
                                    $req_completed_history = $data['req_completed_history'];
                            
                                    // Sort the completed history by completion date (assuming completion_datetime property)
                                    usort($req_completed_history, function ($a, $b) {
                                        $dateA = strtotime($a->completed_datetime);
                                        $dateB = strtotime($b->completed_datetime);
                            
                                        return $dateB - $dateA; // Sort in descending order (most recent first)
                                    });
                            
                                    // Extract the first three elements after sorting
                                    $limited_completed_history = array_slice($req_completed_history, 0, 3);

                                    foreach ($limited_completed_history as $completion):
                                        ?>
                            <div class="main-right-bottom-three-left-cont">
                                <img class="td-pro_pic"
                                    src="<?php echo (empty($completion->customer_image) || !file_exists('C:/xampp/htdocs/ecoplus/public/img/img_upload/customer/'. $completion->customer_image) ) ? IMGROOT . '/img_upload/customer/Profile.png': IMGROOT . '/img_upload/customer/' . $completion->customer_image; ?>"
                                    alt="">
                                <h3>
                                    C <?php echo $completion->customer_id; ?>
                                </h3>
                                <h3>
                                    R <?php echo $completion->req_id; ?>
                                </h3>
                                <h2>
                                    Eco <?php echo $completion->credit_amount;?>
                                </h2>
                            </div>
                            <?php endforeach; ?>
                            <!-- <div class="map" id="map"></div> -->
                        </div>
                        <div class="main-right-bottom-three-right">
                            <div class="main-right-bottom-three-right-left">
                                <h1>Requests Completed</h1>
                                <div class="main-right-bottom-three-right-cont">
                                    <div class="circular-progress">
                                        <span class="progress-value">0%</span>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="main-right-bottom-three-right-right">
                                <h1>Assigned Requests</h1>
                                <div class="map" id="map">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="eco_credit_per_quantity" id="eco_credit_per_quantiy_pop">
                <img src="<?php echo IMGROOT?>/close_popup.png" alt="" id="close_eco_credit_per_quantiy_pop">
                <h1>Eco Credits per Waste Qunatity</h1>
                <div class="Eco_Credit_Per_Cont">
                    <div class="Cont">
                        <h3>Plastic</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->plastic?></p>
                    </div>
                    <div class="Cont">
                        <h3>Polythene</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->polythene?></p>
                    </div>
                    <div class="Cont">
                        <h3>Metal</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->metal?></p>
                    </div>
                    <div class="Cont">
                        <h3> Glass</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->glass?></p>
                    </div>

                    <div class="Cont">
                        <h3>Paper</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->paper?></p>
                    </div>
                    <div class="Cont">
                        <h3>Electronic</h3>
                        <i class='bx bx-purchase-tag'></i>
                        <p><?php echo $data['eco_credit_per']->electronic?></p>
                    </div>
                </div>
                <h2>Per Kg</h2>

            </div>
            <div class="overlay" id="overlay">


            </div>


        </div>

        <script>
        var notification = document.getElementById("notification");
        var notification_pop = document.getElementById("notification_popup");
        notification_pop.style.height = "0px";

        var color = "#47b076";
        var textColor = "#414143"
        let circularProgress = document.querySelector(".circular-progress");
        let progressValue = document.querySelector(".progress-value");
        let progressStartValue = 0;
        let progressEndValue = <?php echo intval($data['percentage']); ?>;
        let speed = 30;
        console.log(progressEndValue);

        var credit_per_waste_quantity = document.getElementById("credit_per_waste_quantity");

        document.getElementById("credit_per_waste_quantity").addEventListener("click", function() {
            document.getElementById("eco_credit_per_quantiy_pop").classList.add('active');
            document.getElementById('overlay').style.display = "flex";
        });

        document.getElementById("close_eco_credit_per_quantiy_pop").addEventListener("click", function() {
            document.getElementById("eco_credit_per_quantiy_pop").classList.remove('active');
            document.getElementById('overlay').style.display = "none";
        });

        function redirectToAssignedRequests() {
            console.log('as');
            var linkUrl = "<?php echo URLROOT?>/collectors/request_assinged"; // Replace with your desired URL
            window.location.href = linkUrl;
        }

        function redirect_complains() {
            var linkUrl = "<?php echo URLROOT?>/collectors/complains";
            window.location.href = linkUrl;
        }

        function redirect_complains_history() {
            var linkUrl = "<?php echo URLROOT?>/collectors/complains_history";
            window.location.href = linkUrl;
        }


        notification.addEventListener("click", function() {
            var isNotificationEmpty = <?php echo json_encode(empty($data['notification'])); ?>;

            if (!isNotificationEmpty) {
                var notificationArraySize = <?php echo json_encode(count($data['notification'])); ?>;
                if (notification_pop.style.height === "0px") {
                    if (notificationArraySize >= 3) {
                        notification_pop.style.height = "205px";
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

        function createOrUpdateChart(color, textColor) {
            var Total_Garbage = <?php echo $data['total_garbage']?>;
            const ctx = document.getElementById('myChart').getContext('2d');

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Plastic', 'Polythene', 'Metal', 'Glass', 'Paper', 'Electronic'],
                    datasets: [{
                        label: 'Kilograms',
                        data: [Total_Garbage.total_plastic,
                            Total_Garbage.total_polythene,
                            Total_Garbage.total_metals,
                            Total_Garbage.total_glass,
                            Total_Garbage.total_paper_waste,
                            Total_Garbage.total_electronic_waste
                        ],
                        backgroundColor: color,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 14,
                                }
                            },
                            barPercentage: 0.5, // Adjust to decrease the width of the bars
                            categoryPercentage: 0.3 // Adjust to control the space between bars
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max.apply(null, [Total_Garbage.total_plastic,
                                Total_Garbage.total_polythene,
                                Total_Garbage.total_metals,
                                Total_Garbage.total_glass,
                                Total_Garbage.total_paper_waste,
                                Total_Garbage.total_electronic_waste
                            ]) + 1, // Add some padding to the maximum value
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Overall Collection Total',
                            color: textColor,
                            font: {
                                size: 18
                            },
                            padding: {
                                bottom: 25
                            }
                        }
                    },
                    elements: {
                        bar: {
                            borderRadius: 10,
                        }
                    },
                    animation: {
                        duration: 700, // Set the duration of the animation in milliseconds
                        easing: 'easeIn' // Set the easing function for the animation
                    }
                }
            });
        }
        createOrUpdateChart(color, textColor);
        console.log('as');



        function initMap() {
            var defaultLatLng = {
            lat: <?= !empty($data['lattitude']) ? $data['lattitude'] : 6 ?>,
            lng: <?= !empty($data['longitude']) ? $data['longitude'] : 81.00 ?>
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLatLng,
                zoom: 12.5,
                styles: [{
                        featureType: 'all',
                        elementType: 'labels.text',
                        stylers: [{
                                visibility: 'on'
                            },
                            {
                                fontSize: '10px'
                            }
                        ]
                    },
                    {
                        featureType: 'poi',
                        elementType: 'labels.icon',
                        stylers: [{
                                visibility: 'off'
                            } // Hide the icons for points of interest
                        ]
                    },
                    {
                        featureType: 'poi',
                        elementType: 'labels.text',
                        stylers: [{
                                visibility: 'off'
                            } // Hide text labels for points of interest
                        ]
                    },
                    {
                        featureType: 'transit',
                        elementType: 'labels.icon',
                        stylers: [{
                                visibility: 'off'
                            } // Hide the icons for transit stations
                        ]
                    },
                    {
                        featureType: 'transit',
                        elementType: 'labels.text',
                        stylers: [{
                                visibility: 'off'
                            } // Hide text labels for transit stations
                        ]
                    },
                    {
                        featureType: 'all',
                        elementType: 'all',
                        stylers: [{
                                saturation: -35
                            } // Adjust the saturation to make the map darker
                        ]
                    }
                ]
            });

            var customColoredMarkerIcon = {
                url: 'https://maps.google.com/mapfiles/ms/micons/green-dot.png',
                size: new google.maps.Size(31, 31),
                scaledSize: new google.maps.Size(19, 18)
            };

            var points = <?php echo $data['assigned_requests']; ?>;;
            points.forEach((point) => {
                var marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(point.lat),
                        lng: parseFloat(point.longi)
                    },
                    map: map,
                    title: point.region,
                    icon: customColoredMarkerIcon
                });

                marker.addListener('click', function() {
                    var infowindow = new google.maps.InfoWindow({
                        content: point.region
                    });
                    infowindow.open(map, marker);
                });
            });
        }

        let progress = setInterval(() => {
            if (progressStartValue == progressEndValue) {
                clearInterval(progress);
            }
            progressStartValue++;
            progressValue.textContent = `${progressStartValue}%`;
            circularProgress.style.background =
                `conic-gradient(#47b076, ${progressStartValue * 3.6}deg, #ededed 0deg)`;


            if (progressStartValue == progressEndValue) {
                clearInterval(progress);
            }
        }, speed);

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, config);

            const chartContainer = document.getElementById('chart');
            actions.forEach(action => {
                const button = document.createElement('button');
                button.textContent = action.name;
                button.addEventListener('click', () => action.handler(myChart));
                chartContainer.appendChild(button);
            });
        });

        function getDarkModeSetting() {
            const storedValue = localStorage.getItem("darkMode");
            return storedValue ? JSON.parse(storedValue) : true;
        }

        isDarkMode = getDarkModeSetting();
        if (getDarkModeSetting()) {

            color = "white"
            textColor = " white";
            circularProgress.style.background =
                `conic-gradient(${color}, ${progressStartValue * 3.6}deg, ${isDarkMode ? "#001f3f" : "#ededed"} 0deg)`;
        }

        checkbox.addEventListener("change", function() {

            if (getDarkModeSetting()) {
                color = "white";
                textColor = "white";
                circularProgress.style.background =
                    `conic-gradient(${color}, ${progressStartValue * 3.6}deg, ${isDarkMode ? "#001f3f" : "#001f3f"} 0deg)`;
            } else {
                color = "#47b076";
                textColor = "#414143";
                circularProgress.style.background =
                    `conic-gradient(${color}, ${progressStartValue * 3.6}deg, #ededed 0deg)`;
            }
            if (myChart) {
                myChart.destroy();
            }
            createOrUpdateChart(color, textColor);
        });

        createOrUpdateChart(color, textColor);
        </script>

        <?php require APPROOT . '/views/inc/footer.php'; ?>