<?php
require_once component('table-header.php');
require_once component('search-bar.php');
require_once component('edit-delete.php');
require_once component('custom-input.php');
require_once component('token-input.php');

if (!empty($student_performance)):
?>

    <div class='table-header-container'>
        <h2 class='section-sub-header'> Student Performance </h2>
        <div class="icon-container">
            
            <?php   
            require component('add.php'); 
             tokenInput($population_token);
            
            searchBar(searchFilters: $spSearch, 
                        formId: $spFormID
            );
            ?>
        </div>
    </div>

    <div class="table-wrapper">

        <table id="<?= $spTableID ?>" class='can-export interactive-t' data-handler='student' >

            <?=
                table_header([
            
                        'Email',
                        'First Name',
                        'Last Name',
                        'Passed',
                ]);
            
            ?>

            <tbody>
                <?php

                foreach($student_performance as $student):
                
                ?>
                    <tr class='interactive'>

                        <td  name="student_epita_email">
                            <a href='/student?email=<?= $student['student_epita_email'] ?>' >
                                <?= $student['student_epita_email'] ?>  
                            </a>
                        </td>

                        
                        <td>
                            <?php
                             customInput(
                            label: '', 
                            name: 'fname',   
                            type: "text", 
                            id:  lower($student['fname'] . $student['lname']. 'fname'),
                            defaultValue : $student['fname']  ,
                            input_classes: ['table-input disabled'],
                            required: false,
                            readonly: true,
                            withLabel: false
                            )
                            ?>

                        </td>

                        <td> 
                        <?php
                        customInput(
                            label: '', 
                            name: 'lname', 
                            id:  lower($student['fname'] . $student['lname'] . '.lname' ),
                            type: "text", 
                            defaultValue : $student['lname']  ,
                            input_classes: ['table-input disabled'],
                            required: false,
                            readonly: true,
                            withLabel: false
                            )
                            ?>
                
                        </td>

                        <td> 
                            <?= "{$student['passed_courses']}/{$student['total_courses']}" ?>
                        </td>

                        <?php editDelete() ?>

                        <?php tokenInput($student['token']) ?>

                    </tr>
                    
                <?php endforeach ?>
            </tbody>
            
        </table>
        <input id="spreadsheet" type='hidden' value='' data-title='<?= $active_population['sudo'] . ' - Population Data'?>' >

    </div>

<?php endif ;
?>
<script type='text/javascript' defer>

useSearchBar(
            '<?= $spFormID ?>', 
            '<?= $spTableID ?>',  
            'filterSP/' + '<?= $active_population['slug'] ?>' , 
            window.PopulationStudentPerformance
            )

</script>
