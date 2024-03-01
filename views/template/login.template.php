
<?php
require component('html-head.php') ;
 ?>
<link rel="stylesheet" type="text/css" href=<?= static_url("styles/terminal.css") ?> />

</head>
<body>

    <ul class="notifications"></ul>

    <div id="main-default">
        <!-- Navigation Bar -->
        <nav class='top-nav default'>
            <img id="logo"  src="/static/assets/epita-logo.png" alt="Epita Logo"/>
            <h1 class="portal-header">EPITA International Admin Portal</h1>
            
        </nav>

        <!-- body of page -->
        <div id="main-login" class="gradient">
        
            <div class="terminal-container">

                <!-- Terminal Here -->
                    <div class="menu">
                        <p class="menu-text">EPITA\<?= $intro_folder ?>\terminal.exe</p>

                        <div class="menu-btns">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            <i class="fa fa-window-maximize" aria-hidden="true"></i>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </div>
                    </div>

                    
                    <div id="terminal">

                        <?= $terminal_content ?>

                    </div>


                    
                <div class='btn-container'>
                    <?= $button_content ?>
                </div>
                    
            </div>
            


        </div>
    </div>
    
</body>
</html>