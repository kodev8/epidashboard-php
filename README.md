# Kalev Keil - EPITA International BSc Semester 2: Final Project 

----------
## 🌟 Overview
----------

This project serves to create a full-stack dynamic website with PHP, HTML, CSS, JS and MYSQL.
A working demo can be found at: https://kodev-epita.000webhostapp.com/
The goal of this project is to expand on the previous one. As such, the aim was to build a fully functional, responsive website with all the required features.  These include:

- Adding, removing, and editing different resources
- Logging activity
- Implementation of Live Search with the backend
- Controlling modals 
- A fully responsive layout

and many more

The main project structure is similar to the MV* pattern, where there are general controllers that process data on the backend and then forward this to the views to display the data accordingly. To avoid the limitation of PHP’s need to refresh since it only handles the server side, the Fetch API in javascript was heavily used to create a smoother overall user experience as it is asynchronous. 


    🗃️ kalev_keil_final_project_sem2
    ├─ 📂 src
    │  ├─  🌍 Core
    │  ├─  🎮 Controllers
    │  │   ├─ 🌐API
    │  │   ├─ 🔍Queries
    │  └─  👓 Views
    └─ 📃 doc
    │  ├─ references.txt
    │  └─ README.MD + .pdf
    └─ 🎥 video
        └─ sem2.mp4

*main project structure*

As shown above, the major folders are the Core, Controllers - which encompasses the Queries and API which handles all fetch requests and Views.


----------
## 🧰 **Requirements**
----------

Global Dependencies: 

- PHP version 8
- Javascript
- ChartJS
- Font Awesome 6
- SheetJS
- DropBox Paper
- MySQL

 
*  One change was made to the database for a better understanding of the flow of data. On the populations table, the population periods were altered to reflect the correct data i.e. instead of all periods being SPRING, those with the year 2020 were set to FALL. The main reason for this is that in the previous version of this project, the data was static hence it was feasible to rely on the student table to get the necessary data. However, with dynamic data, adding and removing students makes that approach no longer viable.


## 🌍 Core
----------

The core folder stands as the backbone of this project with many classes and helper functions to create the desired output. This directory includes aspects of the project such as the Router, Database Handler, Validator, LiveSearch, and a suite of helper functions.

📨 **Router**
The Router class acts as a central hub for directing traffic to different controllers which would then render their respective views. By configuring my ht.access file to have one central point of entry to the application, it was convenient to handle routing as necessary using predefined routes. The intention here is to use a map of routes to controllers to create a more natural feel for the website. To facilitate this, dynamic routing using a combination of the mentioned predefined routes and regex was implemented. For example, instead of routing to “… /2020/FALL/AIS.php”, with the implemented approach, routing can be made to “slugified” routes. i.e. /ais-fall-2020. This kept a clean and readable route pattern. The same goes for courses of a program i.e … /ais-spring-2021/pg-python. The route also helped to maintain a separation of concerns for the controllers. With this, the server method was embedded/attached to the route already. This maintained more readability but did lead to some duplication.

🗄️ **Database (DB)**
The DB class was essential for executing queries easily throughout the project. Since querying the database is essentially involved in the bulk of the project, it was necessary to maintain easy access to create new database connections when required. To solve this, an *App singleton class was created along with a Container singleton*. The App class provided access to the Container by extending the Container’s resolve method. This way, new database connections could be created on the fly without direct access to the container. The resolve method handled the config for the database each time it was called. Again, this kept in line to maintain readability and separation of concerns in most cases. The DB class is responsible for preparing statements and executing queries using PDO which can help avoid SQL Injection. By using PDO, the default setting to use associative arrays to return fetched data was key in prioritizing readable and convenient naming conventions as opposed to binding params with mysqli_ bind params which required a bit more setup or using positional placeholders. With this DB class, the default fetch method was easily extended for greater functionality when handling queries or null results ( when using fetch Abort). 


    public function fetchOneOrAbort(){
            $results = $this->fetchAbort($this->stmt->fetch());
            
            if (count($results) == 0){
                return null;
            }
            
            return $results;

*snippet from DB:: class*

🔐 **Validation (Validator and Action Validator)**
The validators are simple classes that are used throughout the project when validating forms or even simpler POST requests to ensure that the data requested maintains its integrity. The aim was to be able to transmit data throughout the application and ensure that it could not be altered. This provided quite some challenge as the consensus seems to be to sanitize data and only process on the backend. Keeping this in mind, I opted to take the extra step to tokenize data and always check expected input fields vs. the received input fields, and compare them with each other as well as the token. Several other attempts were made but this seemed the most effective way to approach this issue at the current time. 
Each form request whether, post, get, delete, or patch is validated and uses tokens to validate and transmit data. If a token is tampered with, an error will be raised and a notification will be displayed.

🔍 **Live Search**

![](https://paper-attachments.dropboxusercontent.com/s_53378B1C7CEF531FC62369E4EC3B4FAC60D4ECBDF951A9EADF6DA049A78AEB2F_1694469442991_filter.png)


Live Search was incorporated to enhance the overall experience. Rather than having to enter or submit every time to search, as the user types, the data is updated accordingly. The preference was made towards searching via the Fetch API rather than the front end as this seemed to also be the consensus that it is good practice to search via the backend (although the data here isn't extremely large). To attempt this, the Live Search class implements two important methods:


1. buildSearchQuery
    This is a  private method that generates queries based on fields that should be included in the textual search. In the filter section of the search bar, there are fields by which the user can search optionally. When a field is checked it is automatically included in the new query and vice versa.


2. buildCategoryQuery
    This is another private method that is similar to buildSearchQuery except that these filters are based on the checkbox and the result of checking/unchecking the filter is instant. Once there is a change detected in the check box, 
    the query is automatically built.


    private static function buildSearchQuery($params, $expected) {
            // builds search queries by checking all the fields it can be searched on 
            // and concatenating each by OR
            $paramsAsKey = array_flip($params);
            $queryAccept = array_intersect_key($expected, $paramsAsKey);
            $searchSql = [];
            foreach($queryAccept as $sqlKey){
                $searchSql[] = "$sqlKey LIKE :search";
            }
            
            return " ( " . implode(' OR ', $searchSql) . " ) ";
        }
        private static function buildCategoryQuery($params, $expected) {
            // filter array for any non empty category paramaters sent by the search
            $filtered = array_filter($params, function($param) {
                return !empty($param);
            });
            $queryParams = []; // stores the params sent to the sql query
            $sql = []; // sql generated for :key placeholders
            
            //expected is a key val pairing of the name expected in the form and 
            // the column name in for searching sql
            foreach($expected as $key => $value){
                // checks if the expected key is in the filtered array
                if (array_key_exists($key, $filtered)){
                    // if yes, we take the value (column name) and add IN
                    $tempSql = $value . ' IN (';
                    // a count for generating a unique key name to substiute with 
                    $count = 0;
                    //since we already checked using arraykey exists, we get the values of the form submitted
                    // using the expected key
                    foreach($params[$key] as $formVal){
                        $sqlParam = $key . $count; // we create a unique name for the sql param
                        $queryParams[$sqlParam] = $formVal; // assing the unique name to the value from the form
                        // to build the correct query, we check if item is the first, if it is not, it is prefect with a comma
                        $tempSql .= ($count != 0 ? ', ' : '') . ":{$sqlParam}" ;
                        // increment the count
                        $count++;
                    }
                    // end the query with by clossing the search array
                    $tempSql .= ')';
                    // append the temp sql to the sql array
                    $sql[] = $tempSql;
                }
            }
        return [
            'sql' => implode(' AND ', $sql), // final sql to be be placed in the sql query
            'qv' => $queryParams // the query values that would be replaced in the sql
        ];
        }
        public static function buildQuery($get,
                                        $expectedSearchFields = [],
                                        $expectedCategoryFields = [] ,
                                        $prefix = 'WHERE'   
                                        ){
            $finalParams = [];
            $search = '';
            if (!empty($get['searchFilterBy']) && !empty($get['query'])){
                $search = self::buildSearchQuery($get['searchFilterBy'], $expectedSearchFields);
                $finalParams['search'] = "%{$get['query']}%";
                
            }
            if (!empty($get['categoryFilterBy'])){
                // build the category query
                $catQuery = self::buildCategoryQuery($get['categoryFilterBy'], $expectedCategoryFields);
                // if search is alrready initialized, we add the category query prefoxed by and,
                $search .= !empty($search) ? ' AND ' .  $catQuery['sql']: $catQuery['sql'];
                // merge the params for the query with the query values generated
                $finalParams = array_merge($finalParams, $catQuery['qv']);
            }
            // once we have some final params, it is prefixed by where clause
            if (!empty($finalParams)){
                $search = $prefix . ' '.  $search;
                // $finalParams['searchFS'] = $search;
            }
    
            return [
                'search' => $search,
                'finalParams' => $finalParams
            ];
        }

a *snippet of Live Search functionality*

However, rebuilding the components requires a bit of duplication. To avoid serving all the HTML via the server, the responses from the server are sent via JSON.  Javascript front-end components then rebuild the table based on the JSON response. The duplication here comes because the front-end components are the same as the rows returned in partial views. 

🎮 **Controllers**
Each controller is responsible for handling a view / partial view. Typically the controllers handle all the business logic to control the flow of data to the view. The controllers folder also contains an API folder which is responsible for handling all the asynchronous requests from javascript. The controllers are responsible for setting up database connections, querying the database, and formatting the data to be displayed in the subsequent view.


    $student_overall_grade = $db -> query(
        require controller('queries/grades/SELECT_individual_overall_wgrade_per_student.php'), [
            'student_epita_email' => $email,
        ]
       ) -> fetchOneOrAbort();
    
    $student_contact = $db -> query(
        require controller('queries/students/SELECT_student_contact.php'), [
            'student_epita_email' => $email,
        ]
       ) -> fetchOneOrAbort();
    
    foreach($student_grades as &$student_grade){
        $slug = $popStore->getPopulation($student_grade['population_code'], $student_grade['intake'], $student_grade['_year'])['slug'];
        $student_grade['course_url'] ='/populations/' . $slug . '/' . lower($student_grade['course_code']);
        
        if( !empty($student_grade['grade'])){
        $student_grade['grade_class'] = $student_grade['grade'] < 10 ? 'failed' : ($student_grade['grade']  > 13 ? 'success' : 'average') ;
        //na*
        $student_grade['grade'] = round((float) $student_grade['grade'], 2) ;
        }else{
            $student_grade['grade'] = '';
            $student_grade['grade_class'] = '';
    
    ...
    
    require view('students/student.view.php'); //hands data to view

*s**tudent controller handles business logic before passing it to the view*


    ***API  & QUERIES***
    These are two subdirectories within the controller directory. The API folder is responsible for handling any fetch requests. This was done to prevent cluttering a single file if handling multiple types of requests making the code difficult to understand. The API folder typically receives the relevant request, and processes the data by making one or more queries to create, read, update, and/or delete one or more resources. For example, the live search feature is handled by several different API handlers within the directory based on the corresponding resource. For the API controllers to handle the requests, they all need access to the DB which is why, having the container is also useful. These API handlers are responsible for returning the correct responses to the client such as  (i.e. 404 Not found).  Most queries are wrapped in try.. catch blocks to ensure that if an error occurs when using a query, they will be handled accorordingly.
    

🎨 **Views**
The views are mainly only responsible for displaying data sent to them via their respective controllers. They handle the frontend load of the project. The views are organized by their role and split into partial sections where necessary. Within the views directory, there are many different categories for handling their respective resources. To keep a structured layout, there is a template folder that contains two templates for the pages to be rendered (*they have “.template.php” extensions*). The views (with extension “*.view.php”)* effectively replace sections of content on the template to build pages. This keeps the layout uniform and allows the views to be displayed in their respective locations on the page. Below is the typical structure used throughout the project where a partial view is called by a main view and is then placed into a template.


    <?php
        require_once component('edit-delete.php');
        require_once component('table-header.php');
        require_once component('custom-input.php');
        require_once component('token-input.php');
        if (!empty($student_grades)):
    ?>
        <div class='table-header-container'>    
            <h2 class='section-sub-header'>Individual Detailed Grades</h2>
            <div class="icon-container">
                <?php 
                searchBar( searchFilters: $searchFilters,
                            categoryFilters: $categoryFilters,
                            hiddenFilters: $hiddenFilters,
                            formId: $SIG_FormID)
                ?>
            </div>
        </div>
        <div class="table-wrapper">
            <table id='<?= $SIG_TableID ?>' class='can-export interactive-t' data-handler='grade'>
                <?=
                    table_header([
                        'Course',
                        'Exam Type',
                        'Grade (/20)',
                    ])
                ?>
                <tbody>
                    <?php
                    foreach($student_grades as $student_grade):
                    ?>
                        <tr class='interactive'>
                            <td name='course_code'?>
                                <a href=<?= $student_grade['course_url']?>
                                    ><?= title($student_grade['course_name']) ?>
                                </a>
                            </td>
                            <td name='exam_type' >
                                <?= $student_grade['exam_type'] ?>
                            </td>
                            <td class=<?= $student_grade['grade_class'] ?? ''?> >
                            <?php
                            customInput(
                                label: '', 
                                name: 'grade',   
                                type: "number", 
                                defaultValue : $student_grade['grade'],
                                input_classes: ['table-input disabled'],
                                required: false,
                                readonly: true,
                                withLabel: false,
                                placeholder: 'N/A'
                                )
                                ?> 
                            </td>
                            <?php 
                            editDelete();
                            tokenInput($student_grade['token']);
                            ?>
                        </tr>
                <?php 
                    endforeach ?>
                </tbody>
                <tr>
                    <td colspan="2">Final Weighted Grade</td>
                    <td class=<?=  $student_overall_grade["w_grade_class"] ?> >
                        <?=  $student_overall_grade["overall_weighted_grade"] ?> 
                    </td>         
                </tr>
            </table>
            <input id="spreadsheet" type='hidden' value='' data-title='<?= $format_student . ' Detailed Grades'?>'- >
        </div>
    <?php endif ?>
    <script type='text/javascript' defer>
    useSearchBar('<?=$SIG_FormID ?>' , 
                '<?= $SIG_TableID ?>', 
              'student', 
              window.StudentIndividualGrade
              )
    </script>

*partial view* *(student individual grade)*



    <?php
    $header1 = "Student Information";
    
    $childView1 = 'students/partials/student-contact.php';
    $childView2 = 'students/partials/student-grades.php';
    
    require view('template/base.template.php');
    
    ?>

*student.view* *(calls partial view as its child view and then calls a template base.template.php)*



        <!-- second table or chart -->
                     <?php if(!empty($childView2)): ?>
                        <div class="content-container">
                                <?php if(isset($header2)): ?>
                                    <h2 class='section-header'><?= $header2 ?></h2>
                                <?php endif;
                                require view($childView2);  
                                ?>
                        </div>                
                        <?php endif ?

*childview* *is called within the base template*


    🔨 ***Components***
    Within the views directory, there are also some common reusable components. These components are used within views to render partial views and can be used wherever necessary.  These components have mainly been implemented as PHP functions with parameters to be called, which can customize the component as needed and are displayed when served to the client.


    function large_card($title, $description, $url, $icon, $middle_component){
        ?> 
        <a class='card-large'  href=<?= $url ?> >
            
            <div class='col-1'>
                <div class='card-label'>
                    <i class='<?= $icon ?>  card-icon icon'></i>
                    <span><?= $title ?></span>
                </div>
                <div class='card-desc'>    
                    <p> <?= $description?> </p>
                </div>
            </div>  
            <div class='mini-chart-container'>
                    <?= $middle_component ?>
            </div>
            <i class='fa fa-chevron-circle-right card-btn' style='font-size: 1.5rem;' aria-hidden='true'></i>
    
        </a>
    <?php } ?>

components/*large card snippet*

One of the main components used extensively is the **custom input.** The custom input is a versatile functional component that is used to render almost all form inputs on the website. It handles the input displays on the login page, registers, page, and all modals, including dropdown options, check boxes, and text areas. 


    function customInput(
                    string $label, 
                    string $name, 
                    array $input_classes=[],  
                    string $type="text", 
                    $id = null, 
                    bool $readonly = false,
                    string $defaultValue = null,
                    bool $required=false,
                    int $min = 1,
                    int $max = 20,
                    bool $withLabel = true,
                    array $options = [],
                    bool $checked= null,
                    string $placeholder= null,
                    $divid = null
                    ){
    ?>
      <div 
      <?php 
      
      $classes = 'custom-input-group' .' '  . implode(' ', $input_classes);
      ?>
      class="<?= trim($classes) ?>"
      id = "<?= $divid ?>"
      >
      <?php 
      if (!empty($options)): 
        
    ?>
      
      <select 
        <?=  $required ? 'required' : null?> 
        name="<?= $name ?>"
        id="<?= $id ?? $name ?>" 
        class="custom-input" 
      >
        
          <option ><?= title($options['title']) ?></option>
          <!-- options  -->
          <?php foreach($options['data'] as $option) : ?>
              <option value="<?= $option['value'] ?> "><?= title($option['display']) ?></option>
          <?php endforeach; ?>
          
      </select>
      
      <?php
      elseif ($type=='textarea'):
        $tmax = $max ?? 200 ;
      ?>
      
        <div class="custom-input">
          <textarea maxlength="<?= $tmax ?>" name="<?= $name ?>" id="<?= $name ?>" cols=10" rows="5" class="textarea"></textarea>
          <section>
            <span class="text-count">0</span>  <span> <?= "/{$tmax}" ?> </span>
          </section>
        </div>
      <?php
      else:
        ?>
          
            <input 
            class="<?= trim('custom-input'  .  ($readonly ? ' '.'disabled' : '')) ?>"
            <?=  $required ? 'required' : null?>
            type=<?= $type ?>
            name=<?= $name ?>
            id=<?= $id ?? $name ?>
            autocomplete="off"
    

*custom input handles various types of inputs*

Another is the modal which is responsible for showing the form data for adding or deleting resources. The ActionModal class is mainly responsible for taking an array of inputs and other components and building the modal.  However, its modal function is used to create modals in general (for example the avatar modal) as shown below in the design choices.

🎨 **Design Choices and Additional Features**

----------

Building on the previous project, it was continued with a dashboard-type home page. The Top navigation bar now displays the user name or a welcome message upon logging in. 

There is also now a toast notification system for displaying messages to the client.

The profile button is functional now which takes the user to the profile page. The download icon downloads whatever tables are on the current page. The sidebar menu now has an activity tab which takes the user to the activity tab. If the user is a ‘superuser,’ they have access to viewing not only their recent activity but all other admin activity as well as the ability to confirm or reject registrations. The profile page now has simple data about the admin user as well as an avatar to allow admins to customize it according to their preferences. 


![add course modal and avatar modal](https://paper-attachments.dropboxusercontent.com/s_53378B1C7CEF531FC62369E4EC3B4FAC60D4ECBDF951A9EADF6DA049A78AEB2F_1694468308021_addcoursemodal.png)
![](https://paper-attachments.dropboxusercontent.com/s_53378B1C7CEF531FC62369E4EC3B4FAC60D4ECBDF951A9EADF6DA049A78AEB2F_1694468141532_avatarmodal.png)


The main improvement in terms of layout is that this version is fully responsive. It adapts to whichever screen size, including tables that become scrollable when the screen becomes smaller. The sidebar collapses into a slideable side navigation bar.

The ‘/populations’ page has a checklist for hiding or showing tables/charts. 

Chart js was swapped in place of Google charts since the chart js charts come with an in-built property of being responsive and have a more natural feel to them. They are also interactive and can direct the user to the respective population pages.

*These features are shown in the video**


**Other**

----------
- Chart JS is to display charts. 
- SheetJS to add functionality to the download icon in the top bar. 
- No bootstrap was used.
- Font Awesome 6 is used for all icons.


## ❌Issues Faced
----------


    - Choosing whether to search via the backend or frontend
    - Ensuring data is not modified on the front end which could cause the incorrect resource to be loaded or even trigger unwanted SQL queries at the point in time
    - Refactoring queries from the previous project


## 📝 Project References
----------
- Typewriter effect  in terminal: https://css-tricks.com/snippets/css/typewriter-effect/
- More on regex: https://www.youtube.com/watch?v=K8L6KVGG-7o&t=1335s
- More on HTML and CSS: https://www.w3schools.com/html/
- Table CSS: https://www.youtube.com/watch?v=Oy9K7iz3aa4
- CSS Animations: https://developer.mozilla.org/en-US/docs/Web/CSS/animation
- Form button: https://uiverse.io/kamehame-ha/grumpy-vampirebat-43
- Checklist:  https://uiverse.io/della11/brave-bulldog-67
- PHP tutorial: https://laracasts.com/series/php-for-beginners-2023-edition
- Toast tutorial:  https://www.youtube.com/watch?v=BaakzvsR4UU
- Tab-nav: https://uiverse.io/Pradeepsaranbishnoi/heavy-dragonfly-92
- Debounce: https://egghead.io/lessons/javascript-extending-debounce-with-a-maxwait-option

