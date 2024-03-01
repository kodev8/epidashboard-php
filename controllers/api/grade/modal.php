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
$modalValidator = new ActionValidator();
$modalValidator->validateModal($_arr);

//get the modal data 
$gradeModalData = new GradeModalData($_arr['modalType']);
$expectedFields = $gradeModalData->getExpectedFields();

// validate the fields if editing or deleting
$modalValidator->validateModal((array) $expectedFields, $_arr);

// decrypt the token
$decryptData = json_decode(Validator::decryptData($_arr['tokenData']), 
    associative:true);

$db = App::resolve(DB::class);
    
if(empty($decryptData)) {
        throw new Exception(message: "Invalid token");
    }

$formData = $gradeModalData->getFormData($decryptData);

// get the form data for that modal
$actionModal= new ActionModal();
$modalBuilder =  $actionModal->createActionModal($_arr['modalType']);

// build the modal via server and send to client
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