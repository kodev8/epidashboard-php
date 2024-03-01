<?php

require_once component('table-header.php');
require_once component('search-bar.php');
require_once component('edit-delete.php');
require_once component('token-input.php');


if (!empty($courses_and_sessions)):

?>
<div class='table-header-container'>
    <h2 class='section-sub-header'>Courses and Sessions</h2>

        <div class="icon-container">
            
        <?php 
            
            require component('add.php');
            tokenInput($population_token);

    
            searchBar(searchFilters: $CoSeSearch, 
                        formId: $CoSeFormID
            );

            ?>

        </div>
        
    </div>

    <div class="table-wrapper">

        <table  id="<?= $CoSeTableID ?>" class='can-export interactive-t' data-handler='course'>
        <?= 
            table_header(
                [
                    'Course',
                    'Sessions',
                ]
            )
        ?>

            <tbody>
                <?php 
                    foreach($courses_and_sessions as $course_session):
                ?>

                        <tr class='interactive'>

                            <td name="course_code" >
                                <a href=<?= $active_population['slug'] . '/'. $course_session['course_code'] ?> > 
                                    <?= title($course_session['course_name'])  ?> 
                                </a>
                            </td>
                            
                            <td><?= $course_session['session_count'] ?></td>

                             
                            <?php 

                                editDelete(edit: false, delete: false, hidden: true);

                            ?>
                            
                            
                        </tr>
                    
                    <?php endforeach ?>

            </tbody>
        </table>
    
    </div>
<?php endif;

?>

<script type='text/javascript' defer>

useSearchBar(
                '<?=$CoSeFormID?>', 
                '<?= $CoSeTableID ?>',  
                'filterCoSe/' + '<?= $active_population['slug'] ?>' , 
                window.PopulationCourseSession
                )

  </script>

