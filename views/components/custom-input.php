<?php
//custom form input to handle inputs of any type with good
function customInput(
                string $label, 
                string $name, 
                array $input_classes=[],  
                string $type="text", 
                $id = null, 
                bool $readonly = false,
                string $defaultValue = null,
                bool $required=false,
                int $min = 1,
                int $max = 20,
                bool $withLabel = true,
                array $options = [],
                bool $checked= null,
                string $placeholder= null,
                $divid = null
                ){
?>
  <div 

  <?php 
  
  $classes = 'custom-input-group' .' '  . implode(' ', $input_classes);
  ?>
  class="<?= trim($classes) ?>"
  id = "<?= $divid ?>"
  >

  <?php 
  if (!empty($options)): 
    
?>
  
  <select 
    <?=  $required ? 'required' : null?> 
    name="<?= $name ?>"
    id="<?= $id ?? $name ?>" 
    class="custom-input" 
  >
    
      <option ><?= title($options['title']) ?></option>

      <!-- options  -->
      <?php foreach($options['data'] as $option) : ?>
          <option value="<?= $option['value'] ?> "><?= title($option['display']) ?></option>
      <?php endforeach; ?>
      
  </select>

  
  <?php
  elseif ($type=='textarea'):
    $tmax = $max ?? 200 ;
  ?>
  
    <div class="custom-input">
      <textarea maxlength="<?= $tmax ?>" name="<?= $name ?>" id="<?= $name ?>" cols=10" rows="5" class="textarea"></textarea>
      <section>
        <span class="text-count">0</span>  <span> <?= "/{$tmax}" ?> </span>
      </section>
    </div>

  <?php
  else:
    ?>
      
        <input 

        class="<?= trim('custom-input'  .  ($readonly ? ' '.'disabled' : '')) ?>"
        <?=  $required ? 'required' : null?>

        type=<?= $type ?>
        name=<?= $name ?>
        id=<?= $id ?? $name ?>
        autocomplete="off"

        
        <?php
        // if ($defaultValue):
        ?>

        value = "<?= $defaultValue ?>"
        <?php 
        // endif 
        ?>

        <?php if($readonly): ?>
          readonly
        <?php endif ?>

        <?php if($type=='number'): ?>
          min = <?= $min ?>
          max = <?= $max ?>

        <?php endif; ?>


        <?php if($type=='radio' && $checked): ?>
         checked

        <?php endif; ?>

        <?php if($placeholder): ?>
         placeholder="<?= $placeholder ?>"

        <?php endif; ?>
          >

      
        
        

        <?php if($type=='password'): ?>
          <i class="fa-regular fa-eye eye-icon " ></i>
        <?php endif; ?>

        
<?php endif; ?>
        <?php if ($withLabel): 
                if ($type!='radio'):
          ?>
            
            <label class="user-label" for="<?=$name?>" ><?= $label ?><?=$required ? " * " : ''?></label>
          <?php  
              else:
            ?>
              <label for="<?= $id ?>" ><?= $label ?></label>
            <?php

                endif;
              endif; ?>


  </div>
<?php } ?>