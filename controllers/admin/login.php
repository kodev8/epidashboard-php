<?php

// set up random terminal text for intro
$WELCOMES = [
        [
        'shell' => "E:\Epita\Admin\Welcome> " ,
        'command'=>'more' 
        ]
    ,
    
        [
        'shell'=> "admin@epitafr:~$" ,
        'command'=> 'cat'
        ]
    ,
            [
            'shell' => "admin@epitafr~%" ,
            'command' => 'less'
            ]
    
    ];

$random_welcome = $WELCOMES[rand(0, count($WELCOMES)-1)];
$SCRIPTS = [
            [
                "language" => "python3",
                "ext" => ".py"
            ],
            [
                "language"=> "bash",
                "ext"=>".sh"
            ],
            [
                "language"=>"sh",
                "ext"=>".sh"
            ],
            [
                "language"=>"gcc",
                "ext"=>".c"
            ],
            [
                "language"=>"ruby",
                "ext"=>".rb"
            ],
            [
                "language"=>"java",
                "ext"=>".java"
            ]
        ] ;  
    

$random_script = $SCRIPTS[rand(0, count($SCRIPTS)-1)];
$full_script = $random_script['language'] . " login". $random_script['ext']; 


require  view('admin/login.view.php');