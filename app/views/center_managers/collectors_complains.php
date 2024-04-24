<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="CenterManager_Main">
    <div class="CenterManager_Collector_Complains">
        <div class="main">
            <?php require APPROOT . '/views/center_managers/centermanager_sidebar/side_bar.php'; ?>
            <div class="main-right">
                <div class="main-right-top">
                    <div class="main-right-top-one">
                        <div class="main-right-top-search">
                            <i class='bx bx-search-alt-2'></i>
                            <input type="text" id="searchInput" placeholder="Search">
                        </div>
                        <?php require APPROOT . '/views/center_managers/centermanager_notifications/centermanager_notifications.php'; ?>
                        
                    </div>
                    <div class="main-right-top-two">
                        <h1>Collectors</h1>
                    </div>
                    <div class="main-right-top-three">
                        <a href="<?php echo URLROOT?>/centermanagers/collectors">
                            <div class="main-right-top-three-content">
                                <p>View</p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/centermanagers/collectors_add">
                            <div class="main-right-top-three-content">
                                <p>Register</p>
                                <div class="line"></div>
                            </div>
                        </a>
                        <a href="<?php echo URLROOT?>/centermanagers/collectors_complains">
                            <div class="main-right-top-three-content">
                                <p><b style="color: var(--green-color-one);">Complaints</b></p>
                                <div class="line" style="background-color: var(--green-color-one);"></div>
                            </div>
                        </a>

                    </div>
                </div>

                <?php if(!empty($data['collectors_complains'])) : ?>
                <div class="main-right-bottom">
                    <div class="main-right-bottom-top">
                        <table class="table">
                            <tr class="table-header">
                                <th>Complaint ID</th>
                                <th>Collector ID</th>
                                <th>Date & Time</th>
                                <th>Subject</th>
                                <th>Complaint</th>
                            </tr>
                        </table>
                    </div>
                    <div class="main-right-bottom-down">
                        <table class="table">

                            <?php foreach($data['collectors_complains'] as $post) : ?>
                            <tr class="table-row">
                                <td>CoC <?php echo $post->id?></td>
                                <td>Co <?php echo $post->collector_id?></td>
                                <td><?php echo $post->date?></td>
                                <td><?php echo $post->subject?></td>
                                <td><?php echo $post->complaint?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                        
                    
                </div>
                <?php else: ?>
                <div class="main-right-bottom-two">
                    <div class="main-right-bottom-two-content">
                        <img src="<?php echo IMGROOT?>/DataNotFound.jpg" alt="">
                        <h1>There are no complaints yet</h1>
                        <p>Hope you will continue customer satisfaction</p>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script>
     /* Notification View */
    document.getElementById('submit-notification').onclick = function() {
        var form = document.getElementById('mark_as_read');
        var dynamicUrl = "<?php echo URLROOT;?>/centermanagers/view_notification/collectors_complains";
        form.action = dynamicUrl; 
        form.submit(); 

    };
    /* ----------------- */
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>