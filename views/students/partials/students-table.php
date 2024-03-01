<?php

    require component('table-header.php');
    require_once component('search-bar.php');


    if (!empty($students)):
        
?>
    <div class='table-header-container full'>

    <div class="icon-container">
        
        <?php searchBar( searchFilters: $searchFilters,
                        categoryFilters: $categoryFilters,
                            formId: 'search-all-students') ?>
    </div>
    </div>

    <div class="table-wrapper">

        <table id='epita_all_students' class='can-export'>
        <?=
            table_header([
                'Email',
                'First Name',
                'Last Name',
                'Program',
                'Intake',
                'Year',
            ])
        ?>
        
        <tbody>
            <?php
            foreach($students as $student):

            ?>
                <tr class='interactive'>

                    <td><a href='/student?email=<?= $student['student_epita_email'] ?>'><?= $student['student_epita_email'] ?></a></td>
                    <td><?= $student['fname'] ?></td>
                    <td><?= $student['lname'] ?></td>
                    <td><a href=''><?= $student['population_code'] ?> </a></td>
                    <td> <?= title($student['intake']) ?></td>
                    <td><?= $student['_year'] ?></td>
                    
                    
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <input id="spreadsheet" type='hidden' value='' data-title='EPITA - All Students'>
        
    </div>
<?php endif ?>


<script type='text/javascript' defer>

useSearchBar('search-all-students' , 
          'epita_all_students' , 
          'students', 
          window.StudentsTable
          )

</script>


