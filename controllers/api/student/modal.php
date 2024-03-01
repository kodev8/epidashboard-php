<?php
// 

use Core\App;
use Core\DB;
use Core\ActionValidator;
use Core\Validator;
require component('actionModal.php');
require controller('/api/modalData.php');

try {


    $content = trim(file_get_contents("php://input"));
    $_arr = json_decode($content, true); 

    // validate the modal type
    $modalValidator = new ActionValidator();
    $modalValidator->validateModal($_arr);

    //get the modal data 
    $studentModalData = new StudentModalData($_arr['modalType']);
    $expectedFields = $studentModalData->getExpectedFields();

    $student_contact = null;
    // decrypt data 
    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }
    $db = App::resolve(DB::class);


    // validate the fields if editing or deleting
if ($_arr['modalType'] == 'delete'){
       
        $modalValidator->validateFields((array) $expectedFields, $decryptData);

        // get a student from the request

        $student_contact = $db -> query(
            require controller('queries/students/SELECT_student_contact.php'), [
                'student_epita_email' => $decryptData['student_epita_email']
            ]
            ) -> fetchOne();

            // validate the student 
        if (empty($student_contact)){

            throw new Exception('Fetch Student error');

        }

    }

    // get the general for daya
    $formData = $studentModalData->getFormData($student_contact, $decryptData['population_sudo']);

    if ($_arr['modalType'] == 'add'){
    
        // get unique enrolment statuses for form dropdown
        $enrolments  = $db->query(require controller('queries/UniqueSelectors/SELECT_unique_enrolment_status.php'))->fetchAll();
    
        // set up display/value pairs for the options and add the to the overall form inputs
        $values =[];
        foreach ($enrolments as $item) {  
            
            $values[] =[
                'display' => title($item['enrolment_status']),
                'value' => title( $item['enrolment_status'])
            ];
        }    
            // set up enrolment options

            if(!empty($values)){
                $formData['formInputs']['enrolment_status']['options']['data'] = $values;
            }
    }
    

    // get the form data for that modal
    $actionModal= new ActionModal();

    // get which builder 
    $modalBuilder =  $actionModal->createActionModal($_arr['modalType']);

    // build the mdoal amd send back to the client
    $modalForm = $modalBuilder(
        title: $formData['title'],
        inputs: (array) $formData['formInputs'],
        handler: $formData['handler'],  
        token: $_arr['tokenData']
    );

    header('Content-Type: text/html');
    echo $modalForm;

} catch (Exception $err){
    (new Validator())->addError(message: $err -> getMessage()) -> sendAPIErrors();
}