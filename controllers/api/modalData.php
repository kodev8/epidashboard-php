<?php


class AbstractModalData  {
    // Parent class for modal data

    // main store for retrieving data for building and validating modals
    // before opening
    protected $modalType; // edit/ delete
    protected $title; // tile to display on modal
    protected $handler; // url to validated form

    function __construct($modalType) {
        //modal add ot delte
        $this->modalType = $modalType;
    }

    public function getExpectedFields() {
        // expects to be a function which returns the expected fielsd that should eb included in the post request
        
    }
}

class StudentModalData extends AbstractModalData {

    function __construct($modalType)
    {
        parent::__construct($modalType);
        $this->title = title($modalType) . ' Student';
        $this->handler = '/api/student/' . lower($modalType);
    }

    public function getExpectedFields() {

       return [
        'student_epita_email'
       ];
    }

    
    // attributes for building custom form inputs; same set up for all 3
    public function getFormData($student_contact, $population_sudo) { 

        $data = [
            'title' => $this -> title,
            'handler' => $this -> handler,
        ];

        if ($this->modalType == 'delete') {

            return array_merge(

                $data, [
                'formInputs' => [
                        [
                            'label' => 'Population',
                            'name' => 'population',
                            'readonly' => true,
                            'required' => true,
                            'defaultValue'=> $population_sudo,
                            'classes' => ['span2'],
                            'order' => 0
                        ], 
                        
                        [
                            'label' => 'Student Email',
                            'readonly' => true,
                            'required' => true,
                            'name' => 'student_epita_email',
                            'classes' => ['span2'],
                            'defaultValue' => $student_contact['student_epita_email'],
                            'order' => 1
                        ],
                        
                        [
                            'label' => 'First Name',
                            'name' => 'fname',
                            'required' => true,
                            'defaultValue' => $student_contact['fname'],
                            'order' => 2
                        ],
                        
                        [
                            'label' => 'Last Name',
                            'name' => 'lname',
                            'required' => true,
                            'defaultValue' => $student_contact['lname'],
                            'order' => 3
                        ], 

                    
                    ],
                ]
            );
        }
        
        else if( $this->modalType == 'add') {
          
            return array_merge(
                $data, [
                    'formInputs' => [
                        'population' => [
                            'label' => 'Population',
                            'name' => 'population',
                            'readonly' => true,
                            'required' => true,
                            'classes' => ['span2'],
                            'defaultValue' => $population_sudo,
                        ], 

                        'student_epita_email' => [
                            'label' => 'EPITA Email',
                            'name' => 'student_epita_email',
                            'required' => true,
                            'classes' => ['span2'],

                        ],

                        'fname' =>  [
                            'label' => 'First Name',
                            'name' => 'fname',
                            'required' => true,
                        ],

                        'lname'=> [
                            'label' => 'Last Name',
                            'name' => 'lname',
                            'required' => true,
                        ], 

                        'personal_email' => [
                            'label' => 'Personal Email',
                            'name' => 'personal_email',
                            'required' => true,
                            'classes' => ['span2'],
                        ],

                        'dob' => [
                            'label' => 'Date of Birth',
                            'name' => 'dob',
                            'required' => true,
                            'type' => 'date'
                        ],

                        'enrolment_status' => [
                            'label' => 'Enrolment Status',
                            'name' => 'enrolment_status',
                            'required' => true,
                            'id' => 'enrolment',
                            'options'=> [
                                'title' => 'Select an enrolement type...',
                                'data' => []   

                                ]   
                        ],

                        'address' => [
                            'label' => 'Address',
                            'name' => 'address',
                            'classes' => ['span2'],
                        ],

                        'city' => [
                            'label' => 'City',
                            'name' => 'city',
                        ],

                        'country' => [
                            'label' => 'Country',
                            'name' => 'country',
                        ],
                    ]
                ]   
            );
        } 
    }
}




class CourseModalData extends AbstractModalData {

    function __construct($modalType)
    {
        parent::__construct($modalType);
        $this->title = title($modalType) . ' Course';
        $this->handler = '/api/course/' . lower($modalType);
    }

    public function getExpectedFields() {

       return [
        'population_code',
        'population_intake',
        'population_year',
        'population_course_code', 
       
       ];
    }

    public function getFormData($course_session=null, $population_sudo) { 

        $data = [
            'title' => $this -> title,
            'handler' => $this -> handler,
        ];

        if ($this->modalType == 'delete') {

            return array_merge(

                $data, [
                'formInputs' => [
                
                        'population_details' =>  [
                            'label' => 'Population',
                            'name' => 'population',
                            'classes' => ['span2'],
                            'defaultValue' => $population_sudo,
                            'readonly' => true,
                            'required' => true,

                        ],

                        'course_name' => [
                            'label' => 'Course Name',
                            'name' => 'course_name',
                            'defaultValue' => title($course_session['course_name']), 
                            'classes' => ['span2'],
                            'readonly' => true,
                            'required' => true,

                        ],

                        'course_code' => [
                            'label' => 'Course Code',
                            'readonly' => true,
                            'name' => 'course_code',
                            'defaultValue' => $course_session['course_code'],
                            'required' => true,

                        ],  
                    ],
                ]
            );
        }
        
        else if( $this->modalType == 'add') {

            return array_merge(
                $data, [
                    'formInputs' => [

                        'population' => [
                            'label' => 'Population',
                            'name' => 'population',
                            'defaultValue' =>$population_sudo,
                            'classes' => ['span2'],
                            'readonly' => true,
                            'required' => true
                        ],

                        'new'=> [
                            'label' => 'New Course',
                            'name' => 'course_type',
                            'type' => 'radio', 
                            'id' => 'new',
                            'defaultValue'=>'new',
                            'checked'=> true,
                            'required' => true,
                        ], 

                        'existing'=> [
                            'label' => 'Existing Course',
                            'name' => 'course_type',
                            'type' => 'radio', 
                            'id' => 'existing',
                            'defaultValue'=>'existing',
                            'required' => true,
                        ], 

                        'course_code' =>  [
                            'label' => 'Course Code',
                            'name' => 'course_code',
                            'classes' => ['with_new'],
                            'required' => true,
                        ],

                        'complement_course' => [
                            'label' => 'Existing Course Name',
                            'name' => 'existing_course_code',
                            'id' => 'complement_course',
                            'classes' => ['hide'],
                            'required' => '',
                            'options' => [
                                'title' => 'Select an existing course...',
                                'data' => []
                            ]
                        ],

                        'course_name'=> [
                            'label' => 'Course Name',
                            'name' => 'course_name',
                            'classes' => ['with_new'],
                            'required' => true,
                        ], 
                        
                        'course_desc' =>  [
                            'label' => 'Course Description',
                            'name' => 'course_desc',
                            'classes' => ['span2', 'with_new'],
                            'type' => 'textarea',
                            'max' => 200
                        ],

                        'duration'=> [
                            'label' => 'Duration (hrs)',
                            'name' => 'duration',
                            'type' => 'number',
                            'classes' => ['with_new'],
                            'required' => true,
                            
                        ], 

                        'teacher_epita_email'=> [
                            'label' => 'Teacher',
                            'name' => 'teacher_epita_email',
                            'required' => true,
                            'options' => [
                                'title' => 'Select a teacher for this course...',
                                'data' => []
                            ]
                        ], 
                    ]
                ]   
            );
        } 
    }
}


class GradeModalData extends AbstractModalData {

    function __construct($modalType)
    {
        parent::__construct($modalType);
        $this->title = title($modalType) . ' Grade';
        $this->handler = '/api/grade/' . lower($modalType);
    }

    public function getExpectedFields() {
        $expectedFields = [ 'exam_type' ];

       return  $expectedFields;
    }

    public function getFormData($decrypted) { 

        $data = [
            'title' => $this -> title,
            'handler' => $this -> handler,
        ];

        if ($this->modalType == 'delete')  {

            return array_merge(

                $data, [

                    'formInputs' => [
                
                        'population' =>  [
                            'label' => 'Population',
                            'name' => 'population',
                            'defaultValue' => $decrypted['population_sudo'],
                            'classes' => ['span2'],
                            'readonly' => true,
                            'required' => true,
                            'order' => 0
                        ],

                        'student_epita_email' => [
                            'label' => 'Student Email',
                            'readonly' => true,
                            'name' => 'student_epita_email',
                            'classes' => ['span2'],
                            'defaultValue' => $decrypted['student_epita_email'],
                            'required' => true,
                            'order' => 1
                        ],

                        'course_code' => [
                            'label' => 'Course Name',
                            'name' => 'course_code',
                            'classes' => ['span2'],
                            'defaultValue' => title($decrypted['course_name']),
                            'readonly' => true,
                            'required' => true,
                            'order' => 2
                        ],

                        'exam_type' => [
                            'label' => 'Exam Type',
                            'readonly' => true,
                            'name' => 'exam_type',
                            'defaultValue' => $decrypted['exam_type'],
                            'required' => true,
                            'order' => 3
                        ],
                
                        'grade' =>  [
                            'label' => 'Grade ( / 20 )',
                            'name' => 'grade',
                            'type' => 'number',
                            'min' => 1,
                            'max' => 20,
                            'required' => true,
                            'defaultValue' => $decrypted['grade'],
                            'order' => 4
                        ],
                    ]
                ]
            );
        }
    }
}