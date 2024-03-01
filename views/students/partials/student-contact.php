
<?php require component('search-bar.php') ?>

<div class='table-header-container'>
        <h2 class='section-sub-header'>Contact Details</h2>
</div>

<div class=" contact-container">
    
    <div class='card-small-p student-contact'>

        <h3 class="p2 underlined card-label"> Basic Info </h3>

        <div class='student-item'>
            <h3 class="field">First Name </h3>
            <div class="value"> <?= title($student_contact['fname']) ?> </div> 
        </div>

        <div class='student-item'>
            <p class="field">Last Name </p>
            <p class="value"> <?= title($student_contact['lname']) ?> </p>
        </div>
        
        <div class='student-item'>
            <p class="field">Date of Birth</p>
            <p class="value"> <?= $student_contact['dob'] ?> </p>
        </div>
    
    </div>

    <div class='card-small-p student-contact'>

        <h3 class="p2 underlined card-label"> Academic Info </h3>

        <div class='student-item'>
            <p class="field">Population</p>
            <p class="value"><?= $population['sudo'] ?> </p>
        </div>
        
        <div class='student-item'>
            <p class="field">Enrolment Status </p>
            <p class="value"> <?= title($student_contact['enrol_status']) ?> </p>
        </div>

        <div class='student-item email'>
            <p class="field">Epita Email </p>
            <p class="value"> <?= $student_contact['student_epita_email'] ?> </p>
        </div>
    
    </div>
    
    
    <div class='card-small-p student-contact'>
        <h3 class="p2 underlined card-label"> Contact Details</h3>

        
        
        <div class='student-item'>
            <p class="field">Address </p>
            <div class="value"><p> <?= (title($student_contact['address']) ?? 'N/A' ) . (title($student_contact['city']) ?? 'N/A' )?></p> </div>
        </div>
      
        
        
        <div class='student-item'>
            <p class="field">Country </p>
            <p class="value"> <?= title($student_contact['country'])?? 'N/A' ?> </p>
        </div>

        <div class='student-item email'>
            <p class="field">Personal Email</p>
            <p class="value"><a href='mailto:<?= $student_contact['student_personal_email'] ?>'> <?= $student_contact['student_personal_email'] ?> </a></p>
        </div>
       
    </div>
</div>

    


