<?php

namespace Core;

use Core\Validator;

// class to validate expected fields when opening a modal vs the ones actually received
// initially this class was created to distinguish 3 types of modals since adding was also done 
// via modal but later change to inline
class ActionValidator extends Validator{


    public function validateModal(array $ajax_fields){
        if ( 
            empty($ajax_fields['modalType']) || 
            !in_array(lower($ajax_fields['modalType']), ['add', 'delete']) 
            ){

            $this->addError(message: 'Invalid Modal Type');

        }else if (empty($ajaxFields['tokenData'])) {
            
            $this->addError(message: 'Token Not Found');
        }   
    }

    public function validateFields ($expected_fields, $ajax_fields) {
        foreach($expected_fields as $ef) {
            if(!array_key_exists($ef, $ajax_fields)){
                $this->addError(message: 'Modal Error: Missing Field');
            }
        }
    }



    // public function validateModal(array $expectedFields, array $ajaxFields) {
    //         if (!empty($ajaxFields['modal']) && $ajaxFields['modal'] == true ){

    //             $this->validateFields($expectedFields, $ajaxFields);

    //         }else{
    //             $this->addError(message: 'Error: Bad Modal Config');
    //         }
    // }
//     public function validateModal(array $expectedFields, array $ajaxFields) {
//         if (!empty($ajaxFields['modal']) && $ajaxFields['modal'] == true ){

//             $this->validateFields($expectedFields, $ajaxFields);

//         }else{
//             $this->addError(message: 'Error: Bad Modal Config');
//         }
// }


    // private function validateFields(array $expectedFields, array $ajaxFields){
        
    //     if (empty($ajaxFields['fields'])){
    //         // check if fields are set
    //         $this->addError(message: 'Error: Error Creating Modal');

    //     }

    //     // check if sent fields matches expected 
    //     foreach ($ajaxFields['fields'] as $key => $value){
    
    //         if (!in_array($key, $expectedFields)){
                
    //             $this->addError(message: 'Error: Missing Creating Modal'. $key);
    
    //         }elseif (empty($value['value']) || empty($value['id']) || !validateID($value['value'], $value['id'])){
                
    //             $this->addError(message: 'Error: ID error');
    //         }
    //     }
    

    // }

    // public function sortInputs(array $formInputs) {

    //     foreach($formInputs as $input){
    //         if (!array_key_exists('order', $input)){
    //             return null;
    //         }
    //     }

    //     $sortedInputs = usort($formInputs, function ($item1, $item2) {
    //         return $item1['order'] <=> $item2['order'];
    //     });

    //     return $sortedInputs;
    // }

    

    

}
