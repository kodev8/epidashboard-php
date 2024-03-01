<?php

require_once component('custom-input.php');
require_once component('token-input.php');
require_once component('custom-btn.php');
require component('inline-error.php');


class ActionModal {

    //creates the form for a modal
    private function createForm( 
                                $buttons, 
                                $handler, 
                                $inputs=[], 
                                $content=null, 
                                $method="POST",
                                $token = null
                                ){

        ob_start()
    
        ?>

        <form class="modal-form" >

            <?php

            if($content){
                echo $content;
            }

            foreach($inputs as $input){ 
            
            customInput(
                label: $input['label'] ?? '', 
                name: $input['name'] ?? '',
                readonly: (isset($input['readonly']) &&  $input['readonly']) ? 'readonly': false,
                type: $input['type'] ?? 'text',
                id: $input['id'] ?? null,
                input_classes: $input['classes'] ?? [],
                required: $input['required'] ?? false,
                defaultValue: isset($input['defaultValue']) ? $input['defaultValue'] :  null,
                min: $input['min'] ?? 0,
                max: $input['max'] ?? 100,
                options: $input['options'] ?? [],
                checked: $input['checked'] ?? null

            );

            }
            $token ? tokenInput($token) : '';
            echo $buttons;

          
            ?>
            <input type='hidden' id='_request_method' value='<?= $method ?>'> 
            <input type='hidden' id='_request_handler' value='<?= $handler ?>'> 


        </form>

        

    <?php 

    return ob_get_clean();
    
    } 

    public function moddalReset () {

        $inputs  = [
            [
                'label' => 'Old Password',
                'name' => 'old-password',
                'type' => 'password',
                'classes' => ['span2'], 
                'required' => true
            ], 
            [
                'label' => 'New Password',
                'name' => 'new-password',
                'type' => 'password',
                'classes' => ['span2'],
                'required' => true

            ], 
            [
                'label' => 'Confirm Password',
                'name' => 'conf-password',
                'type' => 'password',
                'classes' => ['span2'],
                'required' => true
            ]

        ];  

        ob_start();

        ?>
        <div class='btn-container span2'>

            <?php inlineError(); ?>

            <?= customBtn('Cancel', "fa-solid fa-rectangle-xmark", id: 'negative'); ?>
            <?= customBtn('Confirm Reset', "fa-solid fa-circle-check", 'green',  id: 'positive'); ?>
            

        </div>

        <?php

        $buttons = ob_get_clean();

        return $this->modal(
            title: 'Reset Your Password', 
            content: $this->createForm(
                        inputs: $inputs, 
                        buttons: $buttons, 
                        handler: 'admin/reset-password',
                        method: 'patch'
                    )
        );
        
    }

        
    public function modalAdd(
                                string $title,
                                array $inputs, 
                                string $handler, 
                                string $token,
                                ){
   

        ob_start();

        ?>
        <div class='btn-container span2'>

            <?= customBtn('Cancel', "fa-solid fa-rectangle-xmark", id: 'negative'); ?>
            <?= customBtn('Confirm', "fa-solid fa-circle-check", 'green',  id: 'positive'); ?>
            
        </div>

        <?php

        $buttons = ob_get_clean();

        return $this->modal($title, 

            $this->createForm(
            inputs: $inputs, 
            buttons: $buttons, 
            handler: $handler, 
            method: 'POST', 
            token: $token,
         
            )
        );
        
    }

    //DELETE modal
    public function modalDelete(
                                string $title, 
                                array $inputs=[], 
                                string $handler,
                                string $token
                                ){

        ob_start();

        ?>

        <h2 style="color: white" class="span2"> Are you sure you want to remove the following contents? </h2>
        <div class="delete-content span2">

            <?php
            foreach($inputs as $input):
               
            ?>
                <p> <?= $input['label'] ?? '' ?>:</p>
                <p> <?= $input['defaultValue'] ?? '' ?> </p>
            <?php 
            endforeach;
            ?>

        
        </div>

         <?php

            $content = ob_get_clean();

            //generate delete button
            ob_start(); 
            
            ?>

            <div class='btn-container span2'>

                <?= customBtn(
                    title: 'Cancel', 
                    icon: "fa-solid fa-rectangle-xmark", 
                    id: 'negative'
                    ); ?>

                <?= customBtn(
                    title: 'Confirm Delete', 
                    icon: "fa-solid fa-trash", 
                    color: 'red', 
                    as_submit: true, 
                    id: 'positive'
                    ); ?>

            </div>

            <?php
            $buttons = ob_get_clean();
            
            return $this->modal(
                $title,
                $this->createForm(
                    buttons: $buttons, 
                    handler: $handler, 
                    content: $content, 
                    method: 'delete',
                    token: $token
                    )
                );
    }

    // create modals in genreal 
    public function modal(
                        $title,
                        $content, 
                        ){

        ob_start()

        ?> 

        <div class="modal">
            <i id='close-modal' class="fa-solid fa-xmark"></i>
            <div class="modal_header"> 
                <h1><?= $title ?></h1>
            </div>
                <?= $content ?>
        </div>
        
    
    <?php 
        return ob_get_clean();
    } 


    // resolve creating adding or deleting mdoals
    public function createActionModal($modalType) {

        if ($modalType == 'add'){
            return [$this, 'modalAdd'];
        }else if ($modalType == 'delete'){
            return [$this, 'modalDelete'];
        }
    }
}

?>
                        

