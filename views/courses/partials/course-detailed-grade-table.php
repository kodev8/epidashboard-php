<?php

    
    require_once component('table-header.php');
    require_once component('search-bar.php');
    require_once component('edit-delete.php');
    require_once component('custom-input.php');
    require_once component('token-input.php');


    if (!empty($population_detailed_courses)):
?>
    
    <div class='table-header-container'>
        <h2 class='section-sub-header'>Detailed Grades</h2>

        <div class="icon-container">
            <?php 
            searchBar(
                searchFilters: $DGSearch, 
                categoryFilters: $DGCategory,
                formId: $DGFormID
            );
            ?>
        </div>
    </div>

    <div class="table-wrapper">

        <table id="<?= $DGTableID ?>" class='can-export interactive-t' data-handler='grade'>
        <?=
            table_header([
                'Email',
                'First Name',
                'Last Name',
                'Exam Type',
                'Grade (/20)',
            ])
        ?>

        <tbody>
            <?php
                foreach ($population_detailed_courses as $population_detailed_course):
            ?>

                    <tr class='interactive'>
                        <td name="student_epita_email" >
                            <a href='/student?email=<?= $population_detailed_course['student_epita_email'] ?>'>
                                <?= $population_detailed_course['student_epita_email'] ?>
                            </a>
                        </td>

                        <td><?= $population_detailed_course['fname'] ?></td>
                        <td><?= $population_detailed_course['lname'] ?></td>

                        <td name='exam_type'>
                            <?= $population_detailed_course['exam_type'] ?>
                        </td>

                        <td class=<?= $population_detailed_course['grade_class'] ?> >

                        <?php
                             customInput(
                            label: '', 
                            name: 'grade',   
                            type: "number", 
                            defaultValue :$population_detailed_course['grade'],
                            input_classes: ['table-input disabled'],
                            required: false,
                            readonly: true,
                            withLabel: false, 
                            min: 1,
                            max: 20,
                            placeholder: 'N/A'
                            )
                            ?>
                        </td>

                        <?php 

                            editDelete();
                            tokenInput($population_detailed_course['token']);
                            
                        ?>
                        

                    </tr>
                    
            <?php 
            
                //end grade foreach
                endforeach;
            ?>
            </tbody>
        </table>
    </div>
<?php endif ?>

<script type='text/javascript' defer>

useSearchBar('<?= $DGFormID ?>' , 
            '<?= $DGTableID ?>' , 
          'population/<?=$active_population['slug']?>/<?= $url_mapping['course'] ?>/filterDG', 
          window.CourseDetailedGrade
          )
</script>
