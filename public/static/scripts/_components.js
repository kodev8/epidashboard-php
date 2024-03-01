// TOKEN INPUT: to allow for encrypted data
function tokenInput(token) { 
  return `<input type='hidden' name='tokenData' value="${token}" data-id="token" >`
}

// TITLE fucntion to tiliese text
function title(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }


// COMPONENTS TO REBUILD TABLES ON FETCH REQUEST FOR SEARCHING
// component for when no search returns a value
window.noResults =  () => {
        row = document.createElement('tr') 
        row.innerHTML = `<td class="no-results" colspan='100%'> No Results Found! </td>`
       
        return row
    }


// all popualtions component
PopulationsTable =  {

    createRow: function (population) {

        let row = document.createElement('tr')
        row.innerHTML = `
        
        <td><a href='/populations/${population['slug']} '> ${population['population_code']} </a></td>
        <td> ${population['_year']} </td> 
        <td> ${population['intake'].trim()}</td>
        <td>${population['population_count']}</td>
        <td>${population['attendance_rate']}%</td>
        
        `

        return row
    }
}

//all courses component
CoursesTable =  {

    createRow: function (course) {

        let row = document.createElement('tr')
        row.innerHTML = `
        <td><a href='/populations/${course['population_slug']}' > ${course['population_code']}</a></td>
        <td>${title(course['intake'])} </td>
        <td> ${course['_year']} </td>
        <td style="text-align: justify;"><a href='/populations/${course['course_url']}'> ${title(course['course_name'])} </a></td>
        <td style="text-align: justify;">${title(course['course_description'])}</td>
        `
        return row
    }
}

// all students table component
StudentsTable  =   {

  createRow: function (student) {

      let row = document.createElement('tr')

      row.innerHTML = `
                     <td><a href='/student?email=${student['student_epita_email']}'>${student['student_epita_email']}</a></td>
                    <td>${student['fname']}</td>
                    <td>${student['lname']}</td>
                    <td><a href=''>${student['population_code']} </a></td>
                    <td> ${title(student['intake'])}</td>
                    <td>${student['_year']}</td>
      `
      return row
  }
}

// population student performance component (top table of a single population page)
PopulationStudentPerformance = {

    createRow: function (student) {
      let row = document.createElement('tr')
      row.className = 'interactive'

      row.innerHTML = `
                    <td  name="student_epita_email" >
                    <a href='/student?email=${student['student_epita_email']}' >
                        ${student['student_epita_email']} 
                    </a>
                </td>

                <td>
                    <div class="custom-input-group table-input disabled">
                      <input class="custom-input disabled" type="text" name="fname" autocomplete="off" value="${student['fname']}" readonly>
                  </div>
                </td>

                <td> 
                  <div class="custom-input-group table-input disabled">
                      <input class="custom-input disabled" type="text" name="lname" autocomplete="off" value ="${student['lname']}" readonly>
                  </div>
                </td>

                <td> 
                    ${student['passed_courses']}/${student['total_courses']}
                </td>

                <td class='icon-cell'>

                  <i class='fa-regular fa-pen-to-square fa-sm edit' ></i>

                  <i class='fa-solid fa-trash-can fa-sm delete'></i> 
                  
                </td>

      ` + tokenInput(student['token'])

      return row
    }
}
// population student performance component (bottom table of a single population page)
PopulationCourseSession = {

  createRow: function (course_session) {
    let row = document.createElement('tr')
    row.className = 'interactive'

    row.innerHTML = `
                  <td name="course_code">
                  <a href=${course_session['course_url']}> 
                      ${title(course_session['course_name'])}
                  </a>
              </td>
              
              <td>${course_session['session_count']}</td>

            <td class='icon-cell' style="visibility: hidden"></td>

    `
    return row
  }
}

// studnet individual grades component (bottom of student page)
StudentIndividualGrade = {

  createRow: function (student_grade) {
    (student_grade)
    let row = document.createElement('tr')
    row.className = 'interactive'

    row.innerHTML = `<td name='course_code'>
        
                        <a href=${student_grade['course_url']}>
                        ${title(student_grade['course_name'])}
                        </a>
                      </td>

                        <td name='exam_type' >
                            ${student_grade['exam_type'] }
                        </td>

                    <td class=${student_grade['grade_class']} >
                                          
                        <div class="custom-input-group table-input disabled">
                          <input class="custom-input disabled" type="number" name='grade' autocomplete="off" value ="${student_grade['grade']}" placeholder='N/A' readonly min="1" max="20">
                        </div>
                    </td>

                    <td class='icon-cell'>

                        <i class='fa-regular fa-pen-to-square fa-sm edit' ></i>

                        <i class='fa-solid fa-trash-can fa-sm delete'></i> 

                  </td>

                   ` + tokenInput(student_grade['token'])
    return row
  }
}

// course weighted graded (top of course page)
CourseWeightedGrade = {

  createRow: function (population_course) {
    let row = document.createElement('tr')

    row.innerHTML = `
 
                        
          <td>
            <a href='/student?email=${population_course['student_epita_email']}'>
              ${population_course['student_epita_email'] }
            </a>
          </td>
          <td>${population_course['fname'] }</td>
          <td>${population_course['lname'] }</td>
    
          <td class=${population_course['grade_class']} >
              ${population_course['w_grade']} 
          </td>

          <td class='icon-cell' style="visibility: hidden">
          </td>
              

    `
    return row
  }
}


// course detailed grade component (bottom of course page)
CourseDetailedGrade = {

  createRow: function (population_detailed_course) {
    let row = document.createElement('tr')
    row.className = 'interactive'

    row.innerHTML = `
          <td name="student_epita_email" >
          <a href='/student?email=${population_detailed_course['student_epita_email']}'>
              ${population_detailed_course['student_epita_email']}
          </a>
      </td>

      <td>${population_detailed_course['fname']}</td>
      <td>${population_detailed_course['lname']}</td>

      <td name='exam_type'>
          ${population_detailed_course['exam_type']}
      </td>

      <td class=${population_detailed_course['grade_class']} >
          <div class="custom-input-group table-input disabled">
            <input class="custom-input disabled" type="number" name='grade' autocomplete="off" value ="${population_detailed_course['grade']}" readonly min="1" max="20" placeholder="N/A">
          </div>
      </td>
      <td class='icon-cell'>

          <i class='fa-regular fa-pen-to-square fa-sm edit' ></i>

          <i class='fa-solid fa-trash-can fa-sm delete'></i> 

      </td>


    ` + tokenInput(population_detailed_course['token'] )
    return row
  }
}


// ACTICTIY FEEED

const activityFeedComponent  = (avatar, 
  name, 
  action,
  desc, 
  time, 
  registration=false
  ,token=null) => {

let feedItem = document.createElement('div')
feedItem.className = "activity-comments__item"
let url = `${window.location.origin}/static/assets/avatars/${avatar}.png`

var final_card =  `
<div class="activity-comments__avatar" >
<img src='${url}' alt="User avatar"> 
</div>


<div class="activity-comments__content">
<div class="activity-comments__meta text-muted">
<span class="text-secondary" href="#">${name}</span> ~
<span class="text-secondary" href="#">${action}:</span>
<span class="text-muted"> @ ${time} </span>
</div>
<p  style="margin-bottom: 1rem" class="text-muted ">${desc}</p>
`
if(registration == true) {

final_card += `<div class="activity-comments__actions">
<div class="activity-btn-group activity-btn-group-sm">
  <input type='hidden' name='tokenData' value="${token}" data-id="token" >

      <button   type="button" class="activity-btn confirm_superuser">
          <i class="fa-solid fa-check-double regicon text-super"></i>
          Approve -  super user 
      </button>

      <button   type="button" class="activity-btn confirm_request">
          <i class="fa-solid fa-check text-success regicon text-approve"></i>
          Approve 
      </button>

      <button type="button" class="activity-btn reject_request">
              <i class="fa-solid fa-xmark regicon text-danger"></i>
          Reject 
      </button>

      
</div>
</div>
</div>

`
}

if(action?.toLowerCase()?.includes("request password")) {

final_card += `<div class="activity-comments__actions">
<div class="activity-btn-group activity-btn-group-sm">
<input type='hidden' name='tokenData' value="${token}" data-id="token" >
  <button   type="button" class="activity-btn send_reset">
      <i class="fa-solid fa-check text-success regicon text-approve"></i>
      Send Reset
  </button>
  
</div>
</div>
</div>

`
}

feedItem.innerHTML = final_card

return feedItem

}

const noFeed = () => {

  noFeedComponent = document.createElement('div') 
  noFeedComponent.className = 'no-activity-feed';
  noFeedComponent.innerHTML = ` <h4> No activity here !</h4>`


return noFeedComponent

}


// edit and delete component
class ED { 
  // edit class component: used to help maintain internal state of each button

  constructor(component){

      // icon cell
      this.component = component.parentElement
    
      // row to get data
      this.row = component.closest('tr')
    

      // buttons when want to edit/ delete
      this.editComponent = this.component.innerHTML

      // buttons when wanting to confirm/cancel an edit
      this.confirmComponent = `
          <i class='fa-solid fa-check fa-sm confirm-edit' ></i>
          <i class='fa-solid fa-xmark fa-sm cancel-edit'></i> 
      `
      // inpuuts concerneed 
      this.tableInputs = this.row?.querySelectorAll('.table-input')
     
      this.handler = this.row.closest('table').dataset.handler
      this.canSwap=false
               
  }

  // resets the input vlaues if canceled
  resetFields = (fields) => {

     Object.keys(fields).forEach(field => {
        if (field !='tokenData'){
        let input = this.row.querySelector(`input[name=${field}]`)
        input.value = fields[field]
      }
      })
  }

  // creates "formData" (key value pairs to be sent via fetch)
  createFields = () => {
      let body = {}
      let inputs = this.row.querySelectorAll('input')
      inputs.forEach(input => {
        let name = input.name
        let value = input.value
        body[name] = value
      })

    return body

  }

  // for inline edit set swap to false so it cannot switch back until end of async in finally block
  updateFields = () => {

    this.canSwap = false
    
    fetch(`/api/${this.handler}/edit`, {

      method: 'PATCH',
      body: JSON.stringify(this.createFields())

    }) 
    .then(response => {

      if (!response.ok) {
          return handleAPITypeErrors(response)
      }
      return response.json()

  })
  .then(updateResponse => {

      if (updateResponse == null){
          throw new Error('Server Error: Invalid Data');
      }
          // for successful grade edit, page is refereshed to show color changes
          if (this.handler == 'grade' && updateResponse.type == 'success') {
            sessionStorage.setItem('updateGrade', JSON.stringify({'type': 'grade-edit-success', 'message': updateResponse.message}));              
            window.location.reload()
          }else{
            createToast(updateResponse.type, updateResponse.message)
            this.canSwap = true

          }


  })
  .catch(error =>  {
    if (error.message != 'handled'){
        createToast('error', error.message ? error.message : error);
    }
})
  .finally(() => {
    if (this.canSwap) {
      // This will only execute when this.canSwap is true
      this.swapToEdit();
      
    }
  });;


}
  
  // swaps classes of all table inputs to show the text input in a table cell
  showInputs = () => { 
      if(this.tableInputs) { 
          this.tableInputs.forEach(hiddenInput => {
                    hiddenInput.querySelector('input').readOnly = false
                      hiddenInput.classList.remove('disabled')   
                  })
          
          return this.createFields();
          }
  }
  // swaps classes of all table inputs to hide the text input in a table cell
  hideInputs = () => { 
          if(this.tableInputs) { 

          this.tableInputs.forEach(hiddenInput => {
            hiddenInput.querySelector('input').readOnly = true
            hiddenInput.classList.add('disabled')
                 
          })
      }
  }

  swapToConfirm =  () =>  {
      // when swapping to the confirm state, we show the inputs
      let initial_values = this.showInputs()
      
      // the inner html is changed to the confirm component
      this.component.innerHTML = this.confirmComponent

      // and we add the evenet listeners to confirm/cancel an edit
      this.component.querySelector('.confirm-edit').addEventListener('click', () => { 
          //here we fetch to maake the edit and show any notifications
          // can swap is used to cehck if the fetch was successfull or not
          //to determin if to switch back to edit mode
          this.updateFields()
          
      })

      
      this.component.querySelector('.cancel-edit').addEventListener('click', () => { 
          
        // fields are reset
           this.resetFields(initial_values)
          // edit is canceled and we go back to edit mode
          this.swapToEdit()

      })
      
  }

  swapToEdit= () => { 

      // first, inputs are hidden
      this.hideInputs()

      // we swap back to the edit component (which is the initial component when initialized)
      this.component.innerHTML = this.editComponent

      // re-add edit event listener to allow for swapping to confirm state
      this.component.querySelector('.edit').addEventListener('click', () => { 
          this.swapToConfirm()
      })

      // re-add event listener for opening a delete modal on the delete button since it is removed from the dom
      window.showActionModal('delete')
  }

  handleClick = () => { 

      // function to set up initial state and event handlers
      this.component.querySelector('.edit').addEventListener('click', this.swapToConfirm)


  }
}

const handleEdit = () => { 
  const edits = []

  document.querySelectorAll('.edit').forEach(edit => {
      edits.push(new ED(edit))
})
// handleClicks for all edit buttons
edits.forEach(edit => edit.handleClick())

}


