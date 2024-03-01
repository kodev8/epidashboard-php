<div  id="group" class="card-container">
    <?php
    require component('small-card.php');
    require component('large-card.php');

    
    //population card

    small_card(
            'Populations', 
            'Get general information for each Group.', 
            '/populations',
            'fa-solid fa-database'
            );

    // course card
     small_card(
                    'Courses', 
                    'Click here for all the courses offered.', '
                    /courses','
                    fa-solid fa-file-text'
                );

    // gradebook card
    small_card(
                    'Gradebook', 
                    'All the grades for every single student!', 
                    '/gradebook','
                    fa-solid fa-book'
                );
    

    //student card
    large_card(
            'Students', 
            'Click here to get info on all the brilliant minds currently enrolled at EPITA.',
            '/students',
            'fa-solid fa-graduation-cap',
            "<img src='static/assets/code-group.png' alt='CartoonCodeGroup'/>"
            );
            

    ?>

</div>

