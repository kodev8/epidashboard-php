<?php

    require component('table-header.php');
    require_once component('search-bar.php');



    if (!empty($populations)):
    ?>

  <div class='table-header-container checkboxes'>

        <div id="checklist">
          <div class='check-container'>
            <input id="show-table" type="checkbox" name="r" value="1" checked>
            <label for="show-table">Population Table</label>
          </div>
          
          <div class='check-container'>
            <input id="show-pie" type="checkbox" name="r" value="2" checked>
            <label for="show-pie">Population Pie</label>  
          </div>
          
          <div class='check-container'>
            <input id="show-bar" type="checkbox" name="r" value="3" checked>
            <label for="show-bar">Attendance Bar</label>
          </div>
          
        </div>
          <?php searchBar( searchFilters: $searchFilters,
                          categoryFilters: $categoryFilters,
                            formId: 'search-all-populations') ?>

          
  </div>
    <div class="table-wrapper">

          <table border=1 id='epita_populations' class='can-export'>

            <?= table_header([
                    'Population Code',
                    'Year',
                    'Intake',
                    'Population Count',
                    'Attendance Rate',
                  ]);
              ?>

            <tbody>

            <?php
                foreach($populations as $population):
            ?>
                  <tr>
                    <td><a href='/populations/<?= $population['slug'] ?> '> <?= $population['population_code'] ?> </a></td>
                    <td> <?= trim($population['_year']) ?> </td> 
                    <td><?= title($population['intake']) ?></td>
                    <td><?= $population['population_count'] ?></td>
                    <td><?= $population['attendance_rate'] ?>%</td>
                  </tr>

            <?php
                endforeach;
            ?>

            </tbody>

          </table>

          <input id="spreadsheet" type='hidden' value='' data-title='EPITA - All Programs'>
          
        </div>
    <?php endif ?>

  
  <script type='text/javascript' defer>

      useSearchBar('search-all-populations' , 
                'epita_populations' , 
                'populations', 
                window.PopulationsTable
                )

  </script>
