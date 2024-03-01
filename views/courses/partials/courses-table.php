<?php

    require component('table-header.php');
    require_once component('search-bar.php');
    if (!empty($courses)):

?>

    <div class='table-header-container full'>

        <div class="icon-container">
            
            <?php searchBar( searchFilters: $searchFilters,
                            categoryFilters: $categoryFilters,
                                formId: 'search-all-courses') ?>
        </div>
    </div>


    <div class="table-wrapper">

        
        <table border=1 id='epita_all_courses' class='can-export'>

        <?=
            table_header(
                [
                    'Program',
                    'Intake',
                    'Year',
                    'Course',
                    'Desciption',
                ]
            )
        ?>

            <tbody>

            <?php
            foreach($courses as $course):
            ?>

            <tr>
                    <td><a href='/populations/<?= $course['population_slug'] ?>' > <?= $course['population_code'] ?></a></td>
                    <td><?= title($course['intake']) ?> </td>
                    <td> <?= $course['_year'] ?> </td>
                    <td style="text-align: justify;"><a href='/populations/<?= $course['course_url'] ?>'><?= title($course['course_name']) ?> </a></td>
                    <td style="text-align: justify;"><?= title($course['course_description']) ?></td>
                    
                    
                </tr>
                <?php endforeach ?>
            
            </tbody>
        </table>
        <input id="spreadsheet" type='hidden' value='' data-title='EPITA - All Courses'>
    </div>
      
<?php endif ?>

<script type='text/javascript' defer>

useSearchBar('search-all-courses' , 
          'epita_all_courses' , 
          'courses', 
          window.CoursesTable
          )

</script>