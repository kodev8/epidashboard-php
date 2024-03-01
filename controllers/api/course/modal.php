<?php 
// 

//imports
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
    $actionValidator = new ActionValidator();
    $actionValidator->validateModal($_arr);

    //get the modal data based on the modal type
    $courseModalData = new CourseModalData($_arr['modalType']);
    $expectedFields = $courseModalData->getExpectedFields();

    $course_session = null;

    // decrypt token
    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), 
    associative:true);


    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

  

    $db = App::resolve(DB::class);



    // validate the fields if deleting
    if ($_arr['modalType'] == 'delete'){
        $actionValidator->validateFields((array) $expectedFields, $_arr);

        
        $course_session = $db -> query(
            require controller('queries/courses/SELECT_course.php'), [
                'population_code' => $decryptData['population_code'],
                'population_year' => $decryptData['population_year'],
                'population_intake' => $decryptData['population_intake'],
                'course_code' => $decryptData['population_course_code'],
            ]
        ) -> fetchOne();

        if (empty($course_session)){
    
            throw new Exception('Unable to fetch course error');
        }
    }


$formData = $courseModalData->getFormData($course_session, $decryptData['population_sudo']);

if ($_arr['modalType'] == 'add'){

    // get all course whhich do not belong to the current population
    $existing_courses  = $db->query(require controller('queries/courses/SELECT_complement_populataion_courses.php'), [
        'population_code' => $decryptData['population_code']
    ])->fetchAll();

    $existing_course_otpions = [];

        foreach ($existing_courses as $item) {  
                $existing_course_otpions[] = [
                    'display' => $item['course_name'],
                    'value' => $item['course_code']
                ];
        }

    // update complement course optiond for existing courses
    $formData['formInputs']['complement_course']['options']['data']= $existing_course_otpions ?? [

        'display' => 'There are no other existing courses that can be added',
        'value' => null
    ];

    $teachers  = $db->query(require controller('queries/UniqueSelectors/SELECT_unique_teachers.php'))->fetchAll();

    // allows for changing dispplay and maybe put names instead of emails eventually ??
    $teacher_options = [];

        foreach ($teachers as $item) {  
                $teacher_options[] = [
                    'display' => $item['teacher_epita_email'],
                    'value' => $item['teacher_epita_email']
                ];
        } 

    $formData['formInputs']['teacher_epita_email']['options']['data']= $teacher_options;   

}
    // get the form data for that modal
    $actionModal= new ActionModal();

    $modalBuilder =  $actionModal->createActionModal($_arr['modalType']);


    $modalForm = $modalBuilder(
        title: $formData['title'],
        inputs: (array) $formData['formInputs'],
        handler: $formData['handler'],  
        token: $_arr['tokenData'],

    );

    // Encode the response as JSON and send it back to the client
    header('Content-Type: text/html');
    echo $modalForm;

    }
    catch (Exception $err){
        
        (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();

    }