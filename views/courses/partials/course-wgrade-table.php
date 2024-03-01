<?php

    
    require component('table-header.php');
    require_once component('search-bar.php');
    require_once component('edit-delete.php');




    if (!empty($population_courses)):
?>
    
    <div class='table-header-container'>
        <h2 class='section-sub-header'>Weighted Grades</h2>
        <div class="icon-container">
            
            <?php 
            
            searchBar(
                searchFilters: $WGSearch,
                formId: $WGFormID
            );

            ?>
        </div>
        
    </div>

    <div class="table-wrapper">

        <table  id="<?= $WGTableID ?>" class='can-export'>
        <?=
            table_header([
                'Email',
                'First Name',
                'Last Name',
                'Grade (/20)',
            ])
        ?>
        <tbody>
            <?php
                foreach ($population_courses as $population_course):
            ?>

            <tr>
                <td>
                    <a href='/student?email=<?= $population_course['student_epita_email']?>'>
                         <?= $population_course['student_epita_email'] ?>
                    </a>
                </td>
                <td><?= $population_course['fname'] ?></td>
                <td><?= $population_course['lname'] ?></td>

                <td class=<?= $population_course['grade_class']?> >
                    <?= $population_course['w_grade'] ?? 'N/A' ?> 
                </td>
                <?php editDelete(edit: false, delete: false, hidden: true); ?>
                
            </tr>
                <?php 
                    //end grade foreach
                    endforeach;
                ?>
            </tbody>
        </table>
        <input id="spreadsheet" type='hidden' value='' data-title='<?= $XLFileName ?>'>
        
    </div>
<?php endif ?>

<script type='text/javascript' defer>

useSearchBar('<?= $WGFormID ?>' , 
            '<?= $WGTableID ?>' , 
          'population/<?=$active_population['slug']?>/<?= $url_mapping['course'] ?>/filterWG', 
          window.CourseWeightedGrade
          )

</script>
