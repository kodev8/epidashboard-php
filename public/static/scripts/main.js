// debounce: execute function with delay to not bombard server for live serarch or to ease transition of edit delete componenents
function debounce(callback, timeout, maxTimeout=null){

    let timer, maxTimer;

    return function(...args) {
        if (timer) clearTimeout(timer);
        
        timer = setTimeout(()=> {

          clearTimeout(maxTimer)
          maxTimer = null;
          callback(...args)
        
        }, timeout);

        if (maxTimeout && !maxTimer) {
            maxTimer = setTimeout(()=> {
                
                // clear timer to prevent both timers from going off
                clearTimeout(timer);

                // prevents the timer from running multiple times when not necessary
                maxTimer = null;

                callback(...args);
             },maxTimeout);
        }
    
    }
}

// swaps to classes
window.swapClasses = (element, remove, add) => {
    if (element.className.includes(remove)){
        element.classList.remove(remove);
        element.classList.add(add);
        return true
    }
}


handleCustomInputs = () => {

  // check for custom inputs
const customInputs = document.querySelectorAll('.custom-input')
if (customInputs) {
  
    customInputs.forEach(customInput => {
        customInput.addEventListener('input', (event) => {
            if (customInput.value === "") {
              // if text is is empty then it is not valid and the label can be centered
                customInput.classList.remove('valid')
            }else {
              // itherwise float label
                customInput.classList.add('valid')
            }
        })

        // let eye ico control if text input is password or text
        let eyeIcon = customInput.parentNode.querySelector('.eye-icon')
        if(eyeIcon){
          eyeIcon.addEventListener('click', (e)=> {
            e.preventDefault();
              if(customInput.type == 'password') {
                  eyeIcon.classList.add('active')
                  customInput.type='text'
                  customInput.focus()
              }else {
                eyeIcon.classList.remove('active')
                customInput.type='password'
                customInput.focus()

              }
            })
          }
        })
    }
}


function handleJson(res){
if (res == null){
  throw new Error('Server Error: Invalid Data');
  }

  if (res.type == 'success'){
      sessionStorage.setItem('modalToast',JSON.stringify({
          'type':'modal-success',
          'message': res.message
      })
      );
  
      window.location.reload();
  }
  else{
      createToast(res.type, res.message)
  }}
  
function useSearchBar (
                formID, 
                tableToReplace, 
                apiHandler, 
                trComponent
                ) {
    
      
    // get the respective searchForm
    const searchForm = document.getElementById(formID);

    // get the input for the form
    let search = searchForm.querySelector('input')

    // get the  body of the table
    const tableBody = document.querySelector(`#${tableToReplace} tbody`)


    function handleTbodyChange(mutationsList, observer) {
      // re-add event listners on newly built table rows and cells
      handleEdit()
      showActionModal('delete')
    }

    const observer = new MutationObserver(handleTbodyChange);

    // Configure the observer to watch for child list changes in the tbody
    const config = { childList: true };

    // Start observing the tbody for changes
    observer.observe(tableBody, config);

    // save the intital table 
    const ini = tableBody.innerHTML

    // prevent submitting the table via enter button
    searchForm.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
              e.preventDefault()
        }
      }
    )

    // provide the api url 
    const url = new URL(`/api/${apiHandler}`, window.location.origin )

    searchForm.querySelectorAll('input[type="checkbox"]').forEach(check => {
      check.addEventListener('click', (event) => {

        // if always enabled prevent turning off but also keep in get request which is why we cannot use disbaled
        if  (check.className.includes('enabled-always')){
          check.checked = true
        }

        // if it is a category, we filter using api call
        if (check.className.includes('category')) {
          searchTable(url)
        }
        
      })
    })

    const categories = Array.from(searchForm.querySelectorAll('.category'))

    // resetTable converts the table back to the original html if the search is empty and all the checkboxes are not checked
    const resetTable = (search) => {
      if (search.value.trim() === ''){

        
        if (categories && categories.every(c => !c.checked)) {
        tableBody.parentElement.classList.remove('no-results')

          tableBody.innerHTML = ini
          return

        } else if (!categories) {
        tableBody.parentElement.classList.remove('no-results')

            tableBody.innerHTML = ini
            return
        }
      }
    }
      // on text input call to the api if (checks if to reset first)
    search.addEventListener('input', debounce(() => {

      resetTable(search)

      // if the search is not empty, send request to APi
      //or if search is empty and ther are categories and at least one chategor is chekced
      if (search.value.trim() != '' || ((search.value.trim() == ''  && categories && categories.some(c => c.checked)))){
        searchTable(url)
      }

    }, 800, 1200)
    )

    // Make a GET request with the search term as a query parameter
    const searchTable = (url) => {

      let searchFormData = new FormData(searchForm)
      let searchFormQuery = new URLSearchParams(searchFormData)
      url.search = searchFormQuery.toString()

      fetch(url, {
          method: 'GET',
      })
      .then(response => {
          if (!response.ok){
            handleAPITypeErrors(response)
          }
          return response.json()
          
    })
      .then(data => {


          if (data.results == null){
            tableBody.innerHTML = ''
            tableBody.parentElement.classList.add('no-results')
            tableBody.insertAdjacentElement('beforeend', noResults())
            return
          }

          // Handle the filtered data
          tableBody.innerHTML = ''
          tableBody.parentElement.classList.remove('no-results')
          data.results.forEach( result => {

            let row = trComponent.createRow(result)
            tableBody.insertAdjacentElement('beforeend', row)

          })

      })
      .catch(error =>  {
        if (error.message != 'handled'){
            createToast('error', error.message ? error.message : error);
        }
    })
  }
}

// distinguish beween inline and toast errors
function handleAPITypeErrors(response, inlineError=null) { 

      return response.json()
      .then(customResponse => {
            if (customResponse.type == 'inline-error' && inlineError){

              inlineError.querySelector('span').innerHTML = customResponse.message
              inlineError.classList.remove('hide')
            
            }else {
                throw Error(customResponse.message)
            }
            throw Error('handled')
        })
}
