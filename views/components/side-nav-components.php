<?php


function sidebar_population_component($population){

    $intake_first_letter = first_letter($population['intake']);
    $year = trim($population['_year']);
    
    ?> 

    <a href='/populations/<?= $population['slug'] ?>'>
    <div class='nav-link' >

        <li class='nav-text'>
            <?= "{$population['population_code']} - {$intake_first_letter}{$year}"  ?>
        </li>

        <div class='stats-container'>
            <p class='stats'>Population: <?= $population['population_count'] ?></p>
            <p class='stats'>Attendance: <?= $population['attendance_rate'] ?>%</p>
        </div>

    </div>
     
    </a>
    
<?php } 


function sidebar_population_component_dropdown($year, $populations){

// $intake_first_letter = first_letter($population['intake']);
// $year = trim($population['_year']);

?> 

<button class='nav-link'>
    <?= $year ?>
</button>
<div class="wrapper">
    <?php foreach($populations as $population): ?>
        <a href='/populations/<?= $population['slug'] ?>'>
            <div class='sub-nav-link' >
                <p class='nav-text'>
                    <?= "{$population['population_code']}"  ?>
                </p>

                <div class='stats-container'>
                    <p class='stats'>Population: <?= $population['population_count'] ?></p>
                    <p class='stats'>Attendance: <?= $population['attendance_rate'] ?>%</p>
                </div>

            </div>
     
    </a>

    <?php endforeach ?>
    
</div>

 

<?php } ?>