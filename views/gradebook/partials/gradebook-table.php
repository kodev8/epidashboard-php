<?php

// $edit_remove = require component('edit-remove.php');

require_once component('table-header.php');
require_once component('search-bar.php');

if (!empty($gradebook)):
    ?>

<?php
      foreach ($gradebook as $program => $intakes):
        foreach($intakes as $intake => $years):
            foreach($years as $year => $grades):

?>
                <div class='table-header-container'>
                    <h2 class='section-sub-header'><?= "{$program} - {$intake } {$year}" ?></h2>
                    
                
            </div>
        <div class="table-wrapper">

                <table id=<?= lower("{$program}-{$intake }-{$year}") ?> class='can-export interactive-t'>

                    <?= 
                        table_header(
                            [
                                'Email',
                                'First Name',
                                'Last Name',
                                'Course',
                                'Grades (/20)',
                            ]
                        )
                    ?>
            <?php

                foreach($grades['results'] as $grade):
            ?>
                <tr class='interactive'>
                    <td><a href='/student?email=<?= $grade['student_epita_email'] ?>'> <?= $grade['student_epita_email'] ?> </a></td>
                    <td> <?= $grade['fname'] ?> </td>
                    <td> <?= $grade['lname'] ?> </td>
                    <td><a href='/populations/<?= $grades['slug'] . "/{$grade['course_code']}" ?>' > <?= title($grade['course_name']) ?> </a></td>
            
                    <?php
                    // na *
                    $weighted_grade_value = $grade['w_grade'] ? round((float) $grade['w_grade'], 2) : '';

                    ?>
                    <td class=<?= $weighted_grade_value < 10 ? 'failed' : ($weighted_grade_value > 13 ? 'success' : 'average') ?> >
                        <?= $weighted_grade_value ?> 
                    </td>
                </tr>
            <?php 
                //end grade foreach
                endforeach;
            ?>
            </table>
          <input id="spreadsheet" type='hidden' value='' data-title='EPITA - All Grades'>

            </div>

            <?php
            endforeach;
        endforeach;
    endforeach;
endif;
?>
                



