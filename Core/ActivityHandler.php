<?php
namespace Core;
use Core\Validator;
use Core\DB;
use Exception;
use DateTime;




class ActivityHandler extends Validator {

    // this class creates defines a set of allowed actions to be used for logging data 
    // to the activity tab on the profile page
    //it is responible for formatting and submitting activity data to the db 
    // extneds Validator to have some access in ending API errors and validating some fields

    private const ALLOWED_ACTIONS = [
        'edit'=>'Update', 
        'delete'=>  'Removal',
        'deny_register' => 'Registration Denied',
        'confirm_register'=> 'Registration Confirmed',
        'add'=> 'Addition',
        'request' => 'Request Password Reset'
    ];

    private const adminPath = 'queries/admin/';
    
    
    public function submitActivity(
                    DB $db,
                    string $admin, // expects to use email
                    string $action, 
                    string $message ){

        
         //activity should only come from logged in users
        if(!$this -> validateActivity($action) || !$this -> validate_epita_email($admin)){
            $this ->addError(message: 'Invalid action: action could not be logged') -> sendAPIErrors();
        }

        // format descriptions
        $desc  = trim($message);

        //insert activity to db
        try {

        if (!empty($_SESSION['_LOGGING']) && $_SESSION['_LOGGING'] ) {
            

            $db -> query(require controller($this::adminPath . 'INSERT_new_activity.php'), [
                'admin_email' => $admin,
                'action' => $action, 
                'description' => $desc
            ]);

        }
    }
     catch (Exception){
        $this ->addError(message: 'Logging failed: action could not be logged') -> sendAPIErrors();
     }


    }

    // validates an action based on predefined set of actions
    private function validateActivity($action) {

        return in_array($action, array_keys($this::ALLOWED_ACTIONS));

    }

    public function getActivity($action) {
        // returns a longer text to display in the activity feed based on the action
        if(!$this -> validateActivity($action)){
            return;
        }
        return $this::ALLOWED_ACTIONS[$action];

    }

    private function formatActivity($activity) {
        if (!empty($activity)){

            // formats time and get display text
            foreach($activity as &$act){
                $act['action'] = $this->getActivity($act['action']);
                $act['time']  = (new DateTime($act['time'] ))->format('H:i ~ M d, Y');
            }
            unset($act);
        }
        return $activity;
    }

    

    // gets the current user activity
    public function getYourActivityData($db, $admin)  {
        $your_activity =  $db -> query(require controller($this::adminPath . 'SELECT_your_activity.php'), [
            'admin_email' => $admin
        ]) ->fetchAll();

        return $this->formatActivity($your_activity);


    }

    // gets any other admins activity
    public function getOtherActivityData($db, $admin)  {
        $other_activity =  $db -> query(require controller($this::adminPath . 'SELECT_other_activity.php'), [
            'admin_email' => $admin
        ]) ->fetchAll();

        if (!empty($other_activity)){
            foreach($other_activity as &$act){
               
                $act['tokenData'] =   Validator::encryptData(json_encode([
                    'activity_id' => $act['id'],
                    'admin_email' => $act['admin']
                ]));
            }
            
            unset($act);
        }

        return $this->formatActivity($other_activity);
    }

    //get all registrations for superusers to approve or reject
    public function getRegistrations($db)  {
        $registrations = $db -> query(require controller($this::adminPath . 'SELECT_registrations.php')) ->fetchAll();
        
        if (!empty($registrations)){
            foreach($registrations as &$act){
                // format date
                $act['request_at']  = (new DateTime($act['request_at']))->format('H:i ~ M d, Y');

                // encrypt email to ensure it is unchanges 
                $act['tokenData'] =   Validator::encryptData(json_encode([
                    'admin_email' => $act['admin_email']
                ]));
            }
            
            unset($act);
        }
        return $registrations;
            
    }

    
}
