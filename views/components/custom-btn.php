<?php 

function customBtn($title, $icon=null, $color=null, $as_submit=false, $id=null){
        ?>
        <button 
            class='modal-btn <?= $color ?? ''?>'  
            <?php if($as_submit): ?> type='submit' <?php  endif ?> 
            <?php if($id): ?> id = <?= $id ?> <?php  endif ?> 
            
            >
            <i class="text <?= $icon ?? ''?>   "></i>
            <?= $title ?>
        </button>
        
<?php  } ?>
