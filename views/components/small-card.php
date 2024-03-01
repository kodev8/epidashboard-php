<?php

function small_card($title, $description, $url, $icon){
    ?> 
    <a class='card-small'  href=<?= $url ?> >

       <div class='card-label'>
            <i class='<?= $icon ?>  card-icon icon' aria-hidden='true'></i><h3><?= $title ?></h3>
        </div>

        <div class='card-desc'>    
            <p> <?= $description?> </p>
        </div>
        
        
        <i class='fa fa-chevron-circle-right card-btn' style='font-size: 1.5rem;' aria-hidden='true'></i>
    </a>

<?php } ?>