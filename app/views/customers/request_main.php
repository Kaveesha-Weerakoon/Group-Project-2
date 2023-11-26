<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="Customer_Main">
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Google_API ?>&callback=initMap" async defer></script>
    <div class="Customer_Request_Main">
        <div class="Customer_Request_Ongoing">
            <div class="main">

              <div class="main-left">
                <div class="main-left-top">
                    <img src="<?php echo IMGROOT?>/Logo_No_Background.png" alt="">
                    <h1>Eco Plus</h1>
                </div>
                <div class="main-left-middle">
                    <a href="<?php echo URLROOT?>/customers">
                        <div class="main-left-middle-content">
                            <div class="main-left-middle-content-line2"></div>
                            <img src="<?php echo IMGROOT?>/Customer_DashBoard_Icon.png" alt="">
                            <h2>Dashboard</h2>
                        </div>
                    </a>
                   
                        <div class="main-left-middle-content current">
                            <div class="main-left-middle-content-line"></div>
                            <img src="<?php echo IMGROOT?>/Customer_Request.png" alt="">
                            <h2>Requests</h2>
                        </div>
                   
                    <a href="<?php echo URLROOT?>/customers/history">
                        <div class="main-left-middle-content">
                            <div class="main-left-middle-content-line2"></div>
                            <img src="<?php echo IMGROOT?>/Customer_tracking _Icon.png" alt="">
                            <h2>History</h2>
                        </div>
                    </a>
                    <a href="<?php echo URLROOT?>/customers/editprofile">
                        <div class="main-left-middle-content">
                            <div class="main-left-middle-content-line2"></div>
                            <img src="<?php echo IMGROOT?>/Customer_Edit_Pro_Icon.png" alt="">
                            <h2>Edit Profile</h2>
                        </div>
                    </a>
                </div>
                <div class="main-left-bottom">

                    <a href="<?php echo URLROOT?>/customers/logout">
                        <div class="main-left-bottom-content">
                            <img src="<?php echo IMGROOT?>/Logout.png" alt="">
                            <p>Log out</p>
                        </div>
                    </a>
                </div>
              </div>

              <div class="main-right">
                <div class="main-right-top">
                    <div class="main-right-top-one">
                        <div class="main-right-top-one-input">
                           <img src="<?php echo IMGROOT?>/Search.png" alt="">
                           <input type="text" placeholder="Search" id="searchInput" oninput="searchTable()">
                        </div>
                        
                        <div class="main-right-top-one-content">
                            <p><?php echo $_SESSION['user_name']?></p>
                            <img src="<?php echo IMGROOT?>/img_upload/customer/<?php echo $_SESSION['customer_profile']?>" alt="">
                        </div>
                    </div>
                    <div class="main-right-top-two">
                        <h1>Requests</h1>
                    </div>
                    <div class="main-right-top-three">
                       
                            <div class="main-right-top-three-content">
                                <p><b style="color: #1B6652;">Current</b></p>
                                <div class="line"></div>
                            </div>
                       
                        <a href="<?php echo URLROOT?>/customers/request_completed">
                            <div class="main-right-top-three-content">
                                <p>Completed</p>
                                <div class="line1"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/customers/request_cancelled">
                            <div class="main-right-top-three-content">
                                <p>Cancelled</p>
                                <div class="line1"></div>
                            </div>
                        </a>
                    </div>
                </div>
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
                                <th>Location</th>
                                <th>Collector</th>
                                <th>Collector Info</th>
                                <th>Cancel</th>
                            </tr>
                        </table>
                    </div>
                    <div class="main-right-bottom-down">
                        <table class="table" id="dataTable">
                           <?php foreach($data['request'] as $request) : ?>
                              <tr class="table-row">
                                 <td><?php echo $request->req_id ?></td>
                                 <td>
                                    <?php 
                                        echo $request->type = ($request->type === 'incoming') ? 
                                        '<img class="processing" src="' . IMGROOT . '/process.png" alt="1">'.'<p class="bold1">Pending</p>'  : 
                                        '<img class="assinged" src="' . IMGROOT . '/GarbageTruck.png" alt="1">'.'<p class="bold2">Assigned</p>';          
                                    ?>
                                 
                                 <td><?php echo $request->date?></td>
                                 <td><?php echo $request->time?></td>
                                 <td><?php echo $request->region?></td>
                                 <td class="cancel-open"><img src="<?php echo IMGROOT?>/location.png" alt="" onclick="viewLocation(<?php echo $request->lat; ?>, <?php echo $request->longi; ?>)"></td>
                                 <td class="cancel-open"><img src="<?php echo IMGROOT?>/collectors.png" alt=""></td>
                                 <td class="cancel-open"><img src="<?php echo IMGROOT?>/view.png" alt=""></td>
                                 <td class="cancel-open"><a href="<?php echo URLROOT?>/customers/cancel_request_confirm/<?php echo $request->req_id?>"><img src="<?php echo IMGROOT?>/cancel.png" alt=""></a></td>
                               </tr>
                            <?php endforeach; ?>

                        </table>

                    </div>
                </div> 
                 <?php else: ?>
                     <div class="main-right-bottom-two">
                            <div class="main-right-bottom-two-content">
                                   <img src="<?php echo IMGROOT?>/DataNotFound.jpg" alt="">
                                   <h1>You Have No Ongoing Requests</h1>
                                   <a href="<?php echo URLROOT?>/customers/request_collect"><button>Request a Collect Now</button></a>
                                  
                            </div>
                     </div>
                 <?php endif; ?>
                
                
              </div>
              
             <?php if($data['cancel']=='True') : ?>
                 <div class="delete_confirm">
                        <div class="popup" id="popup">
                            <img src="<?php echo IMGROOT?>/exclamation.png" alt="">
                            <h2>Cancel the Request?</h2>
                            <p>This action will cancel the request </p>
                            <div class="btns">
                                <a href="<?php echo URLROOT?>/customers/cancel_request/<?php echo $data['request_id']?>"><button type="button" class="deletebtn" >Confirm</button></a>
                                <a href="<?php echo URLROOT?>/customers/request_main"><button type="button" class="cancelbtn">Cancel</button></a>
                            </div>
                        </div>
                  </div>
             <?php endif; ?> 
                
              <div class="location_pop">
                 <div class="location_pop_content">
                    <div class="location_pop_map">
                     
                     </div>
                     <div class="location_close">
                        <button onclick="closemap()">Close</button>
                     </div>
                 </div>
                
              </div>
             
         </div>
    </div>
</div>
<script>
    
    function initMap(latitude, longitude) {
      var mapCenter = { lat: 7.4, lng: 81.00000000 };

      var map = new google.maps.Map(document.querySelector('.location_pop_map'), {
         center: mapCenter,
         zoom: 7.4
      });

      var marker = new google.maps.Marker({
        position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
        map: map,
        title: 'Marked Location'
      });
    }

    function viewLocation($lattitude,$longitude){
        initMap($lattitude,$longitude);
        document.querySelector('.location_pop').style.display = 'flex';
    }

    function closemap(){
        document.querySelector('.location_pop').style.display = 'none';

    }

    function cancel_request(){

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

                   if (center.includes(input) || id.includes(input) || status.includes(input) || date.includes(input) ||  time.includes(input)) {
                        row.style.display = '';  
                   } else {
                     row.style.display = 'none';  // Hide the row
                    }
                  });
            
        }

        document.getElementById('searchInput').addEventListener('input', searchTable);

 </script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
