<?php

function large_card($title, $description, $url, $icon, $middle_component){
    ?> 

    <a class='card-large'  href=<?= $url ?> >
        
        <div class='col-1'>

            <div class='card-label'>
                <i class='<?= $icon ?>  card-icon icon'></i>
                <span><?= $title ?></span>
            </div>

            <div class='card-desc'>    
                <p> <?= $description?> </p>
            </div>

        </div>  

        <div class='mini-chart-container'>
                <?= $middle_component ?>
        </div>

        <i class='fa fa-chevron-circle-right card-btn' style='font-size: 1.5rem;' aria-hidden='true'></i>


    </a>

<?php } ?>