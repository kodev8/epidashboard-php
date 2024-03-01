<?php
    require_once component('edit-delete.php');
    require_once component('table-header.php');
    require_once component('custom-input.php');
    require_once component('token-input.php');
    if (!empty($student_grades)):
?>
    <div class='table-header-container'>    
        <h2 class='section-sub-header'>Individual Detailed Grades</h2>
        <div class="icon-container">
            <?php 
            searchBar( searchFilters: $searchFilters,
                        categoryFilters: $categoryFilters,
                        hiddenFilters: $hiddenFilters,
                        formId: $SIG_FormID)
            ?>
        </div>
    </div>
    <div class="table-wrapper">
        <table id='<?= $SIG_TableID ?>' class='can-export interactive-t' data-handler='grade'>
            <?=
                table_header([
                    'Course',
                    'Exam Type',
                    'Grade (/20)',
                ])
            ?>
            <tbody>
                <?php
                foreach($student_grades as $student_grade):
                ?>
                    <tr class='interactive'>

                        <td name='course_code'?>
                            <a href=<?= $student_grade['course_url']?> >
                                <?= title($student_grade['course_name']) ?>
                            </a>
                        </td>

                        <td name='exam_type' >
                            <?= $student_grade['exam_type'] ?>
                        </td>
                        <td class=<?= $student_grade['grade_class'] ?? ''?> >
                        <?php
                        customInput(
                            label: '', 
                            name: 'grade',   
                            type: "number", 
                            defaultValue : $student_grade['grade'],
                            input_classes: ['table-input disabled'],
                            required: false,
                            readonly: true,
                            withLabel: false,
                            placeholder: 'N/A'
                            )
                            ?> 
                        </td>
                        <?php 
                        editDelete();
                        tokenInput($student_grade['token']);
                        ?>
                    </tr>
            <?php 
                endforeach ?>
            </tbody>
            <tr>
                <td colspan="2">Final Weighted Grade</td>
                <td class=<?=  $student_overall_grade["w_grade_class"] ?> >
                    <?=  $student_overall_grade["overall_weighted_grade"] ?> 
                </td>         
            </tr>
        </table>
        <input id="spreadsheet" type='hidden' value='' data-title='<?= $format_student . ' Detailed Grades'?>'- >
    </div>
<?php endif ?>
<script type='text/javascript' defer>

useSearchBar('<?=$SIG_FormID ?>' , 
            '<?= $SIG_TableID ?>', 
          'student', 
          window.StudentIndividualGrade
          )

</script>


