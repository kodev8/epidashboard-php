<?php

function searchBar(array $searchFilters=[], array $categoryFilters=[], $hiddenFilters=[], $formId=null, ){

?>



  <div class="button-container">
    <div class="search-icon">
        <i class="fa-solid fa-magnifying-glass "></i>
    </div>

    <form id="<?=$formId?>" >
        <input 
        placeholder="Search..." 
        class="search-input"
        name="query"
        type="text" 
        autocomplete="off"
        >
    
    <?php

     if (!empty($searchFilters)  || !empty($categoryFilters)): ?>
        <div class="filter-icon">
            <i class="fa-solid fa-sliders"></i>

            <div class='filter-container'>
                <ul>

                <?php 

                if (!empty($searchFilters)):
                    ?>
                    <h5>  Search  </h5>


                    <?php
                
                    foreach ($searchFilters as $filter): 

                ?>
                    <li> 
                        <input type='checkbox' 
                        checked 
                        <?php if (!empty($filter['default']) && $filter['default'] == true): ?> 
                           class='enabled-always'
                        <?php endif; ?> 
                        value=<?= $filter['key']?> name="searchFilterBy[]">
                        <?= $filter['display']?>
                    </li>

                <?php endforeach; 

                endif; 

                if (!empty($categoryFilters)):
                
                    foreach ($categoryFilters as $filter): 
                ?>

                    <div class='category-container'> 
                        <h5> <?= $filter['display'] ?> </h5>

                        <?php foreach($filter['fields'] as $field): ?> 
                    
                            <li> 
                                <input type='checkbox' class='category' value=<?= $field ?> name="categoryFilterBy[<?=$filter['key'] ?>][]">
                                <label> <?= $field ?> </label>
                            </li>

                         <?php endforeach; ?>
                    </div>
                    
                    <?php endforeach; 

                endif;
                ?>
                </ul>
            </div>

        </div>
    <?php endif;
    if (!empty($hiddenFilters)):
                
        foreach ($hiddenFilters as $key => $value): 
    ?>
                
            <input type='hidden' value=<?= $value ?> name="<?= $key ?>">

    
            <?php endforeach;
            endif;
        ?>

    </form>

  </div>


<?php } ?>
