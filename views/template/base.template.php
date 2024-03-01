<?php

use controllers\populations\PopulationStore; 
use Core\App;

require component('html-head.php') 
?>

<link rel="stylesheet" type="text/css" href=<?= static_url("styles/table.css"); ?>
 
/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


      
<script src="<?= static_url("scripts/_download.js") ?>"  defer></script>    
<script src="<?= static_url("scripts/_nav-layout.js") ?>"  defer></script>    
<script src="<?= static_url("scripts/_edit-delete.js") ?>" defer></script>

</head>

<body>



        <!-- body of page -->
        <div id="main">
            
        <button id='modal-bg' class='modal-bg'>
        </button>
       
        <!-- absolute notifactions  -->
        <ul class="notifications"></ul>
       

            <!-- Navigation Bar -->
            <div id='side-nav-x' class="">
                            <i class="fa-solid fa-xmark icon"></i>
                        </div>
                            
            <nav id="side-nav" class="closed-1" >

                
                
                <div>

                
                    <a href="/dashboard" id='dashboard' class="nav-header" >

                          
                            <img src= <?= static_url("assets/epitafr-icon.png") ?>>
                            <h2>Dashboard</h2>
                    
                    </a>

                        
                        <a href="/populations#charts" class="nav-header nav-text">
                            
                                <i class="fa-sharp fa-solid fa-chart-area icon" aria-hidden="true"></i>
                                <h2>Charts</h2>

                        </a>
                    <?php if ($_SESSION['admin']['superuser']): ?>
                        <a  href="/profile#activity" class="nav-header nav-text">
                        <i class="fa-solid fa-users icon"></i>
                            <h2 style="text-align:center">Activity</h2>
                        
                        </a>
                    <?php endif; ?>
                    <ul>

                        <?php
                        require component('side-nav-components.php');
                                foreach (App::resolve(PopulationStore::class)->getPopulations(true) as $year => $data){
                                    sidebar_population_component_dropdown($year, $data);
                                }
                    ?>
                        
                    </ul>
                </div>

                
                <div class="btn-container">
                    <?php require_once component('custom-btn.php'); ?>
                    
                    <span id="download">
                        <?= customBtn('Download Tables', "fa-solid fa-cloud-arrow-down", 'yellow'); ?>   
                    </span>


                    <a href='/profile'>
                        <?= customBtn('Profile', "fa-solid fa-user", 'green'); ?>
                    </a>

                    <form method="POST" action='/logout'>
                        <?= customBtn('Logout', "fa-solid fa-right-from-bracket", 'red', as_submit: true); ?>
                    </form>

                </div>
                
                
                
                
           
            </nav>


            <div id="main-content" >



            

                <nav class="top-nav accessed">

                <div id='menu-burger'>
                    <i class="fa-solid fa-bars icon"></i>
                </div>
             

                    <div class="message">
                        <!-- top nav welcome message -->
                        <span>
                        </span>
                    </div>

                    <div class="icon-container">
                        
                        <i id="download" title="Download Current Tables" class="fa-solid fa-cloud-arrow-down icon"></i>
                        <a id="profile" title="Profile"  href="/profile"><i class="fa-solid fa-user icon"></i></a>

                        <form method="POST" action='/logout'>
                            <i id="logout"  title="Log Out"class="fa-solid fa-right-from-bracket icon logout"></i>
                        </form>
                       <script type="text/javascript" defer>

                        document.querySelectorAll('.logout')?.forEach(logout => logout?.addEventListener('click', () => logout.parentElement.submit()))
                       
                        </script>

                    </div>
                    
                </nav>

                <!-- first table or chart -->
                <?php if(!empty($childView1)): ?>
                    <div class="content-container">

                            <?php if(isset($header1)): ?>
                                <h2 class='section-header'><?= $header1 ?></h2>
                            <?php endif;
                            
                            
                            require view($childView1);
                                
                            ?>


                    </div>
                
                <?php endif ?>


                 <!-- second table or chart -->

                 <?php if(!empty($childView2)): ?>
                    <div class="content-container">

                            <?php if(isset($header2)): ?>
                                <h2 class='section-header'><?= $header2 ?></h2>
                            <?php endif;
                            
                            
                            require view($childView2);
                                
                            ?>


                    </div>
                
                    <?php endif ?>
                   
                    
                <footer>
    
                        <p class="footer-text">Created by Kalev Keil </p>
                        
                        
                        <a  class="footer-text" id="mailto" href="mailto:kalev-giovanni.keil@epita.fr">
                               kalev-giovanni.keil@epita.fr
                        </a>
        
                        <p class="footer-text">Last updated: 
                            <?php 
                                $date = date("d/m/Y \- H:i");
                                echo $date;
                            ?>
                        </p>
     
                </footer>  
            </div>

        
        </div>

    
 
      

        <script type="module">

                // Retrieve login toast toast 
                const toastType = sessionStorage.getItem('toastType');
                const message =  document.querySelector('.message span')
                if (toastType === 'login-success') {
                // Display success toast
                message.innerHTML = "Welcome To Your EPI-DashBoard, <?= $_SESSION['admin']['fname'] ?>"
                createToast('success', 'Logged in successfully: <?php echo $_SESSION['admin']['email'] ?>');

                }else {
                    message.innerHTML = "<?= $_SESSION['admin']['fname'] ?>'s EPIDashboard"
                }
                sessionStorage.removeItem('toastType')

      

            // Retrieve grade toast 
            const gradeToast = sessionStorage.getItem('updateGrade');

            if (gradeToast){
                let toastInfo = JSON.parse(gradeToast);
                if (toastInfo.type === 'grade-edit-success') {
            // Display success toast
            createToast('success', toastInfo.message);
            }

            }

            sessionStorage.removeItem('updateGrade')

            //
            const modalToast = sessionStorage.getItem('modalToast');

            if (modalToast){
                let toastInfo = JSON.parse(modalToast);
                if (toastInfo.type === 'modal-success') {
                    // Display success toast
                    createToast('success', toastInfo.message);
                }

            }

            sessionStorage.removeItem('modalToast')

</script>

</body>

</html>