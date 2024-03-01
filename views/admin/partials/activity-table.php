
<?php
    require component('table-header.php'); 
?>

<h2 id="activity" class="section-header">Admin Activity</h2>


    <div>

      <!-- start top tab -->
        <div class="activity-card activity-card-small activity-comments">
            <div class="activity-card-header border-bottom">


            <div class="tab-container">

                <div id='activity' class="tabs">
                    <input type="radio" id="activity-your" name="tabs" value="your" 

                    <?php if($_SESSION['active_activity_tab'] == 'your'):
                        
                    ?> 
                    checked
                    <?php
                    endif
                    ?>

                    >
            
                    <label class="tab" for="activity-your">Your Activity</label>

                <?php if(!empty($_SESSION['admin']['superuser']) &&  $_SESSION['admin']['superuser']): ?>

                    
                    <input type="radio" id="activity-other" name="tabs" value='other' 

                    <?php if($_SESSION['active_activity_tab'] == 'other'):
                        
                        ?> 
                        checked
                        <?php
                        endif
                        ?>

                    >


                    <label class="tab" for="activity-other">Other Activity</label>

                    <input type="radio" id="activity-registration" name="tabs" value='registrations'

                    <?php if($_SESSION['active_activity_tab'] == 'registrations'):
                        
                        ?> 
                        checked
                        <?php
                        endif
                        ?>
                    >
                    <label class="tab" for="activity-registration">Registrations</label>
                 <?php endif; ?>

                    
                 <span class="glider"></span>

                 
                </div>

            </div>


            </div>

            <div   style='padding: 0' class="card-body">


                <div id='feed-container'></div>
 
                <!-- show more footer -->
                    <div class="activity-card-footer border-top">
                            <div  style="text-align: center" class="view-report">
                                <button id='show-more'  class="activity-btn">
                                    <i class="fa-solid fa-angles-down"></i>
                                    Show More
                                </button>
                            </div>
                    </div>
            </div>

        </div>
       
    </div>


<script src= <?= static_url('scripts/_activity.js') ?> > </script>


<script>
document.addEventListener('DOMContentLoaded', fetchAndPopulateData('<?= $_SESSION['active_activity_tab'] ?>'));
</script>