// search bar front end js for presentation // handles no api calls
    // get all search icons
document.querySelectorAll('.search-icon').forEach(searchIcon => {
    
        // if there is a search icon
        if (searchIcon){
    
        // get all the elements within, searchicon, search input, filter-icon
        var buttonContainer =  searchIcon.parentNode
        var search = buttonContainer.querySelector('input')
        var searchForm = buttonContainer.querySelector('form')
        var filter = buttonContainer.querySelector('.filter-icon')


    
        // filter container to open filter options to search by
        if (filter) {var filterContainer = filter.querySelector('.filter-container')}
    
        // change color of search based on if it is open or closed
        searchIcon.addEventListener('mouseenter', ()=> {
            if (search.classList.contains('opened')){
                searchIcon.querySelector('i').style.color='#ff0000'
            }else{
                searchIcon.querySelector('i').style.color='#00ff00'
            }
    
        })
    
        // always reset to white when hover out
        searchIcon.addEventListener('mouseleave', ()=> {
                searchIcon.querySelector('i').style.color='#ffffff'
            
        })
    
        // add an event listener to the search icon
        searchIcon.addEventListener('click',() =>{
            if(search){
    
                // if search is already opened then close it and clear value
                if (search.classList.contains('opened') ){
                    search.classList.remove('opened')
                    search.value = ""
                    search.blur()
                }else{
    
                // if it is closed, open and focus it
                    search.classList.add('opened')
                    search.focus()
                }   
            }
        })
    
        if (search){
            // close when clicked outside if the input is empty
            search.addEventListener('blur', () => {
                if (search.value === ""){
                    search.classList.remove('opened')
                    if (filter && filterContainer) {
                        filterContainer.style.display = ''
                        filter.querySelector('i').style.color = '#ffffff'
                    }
                }

            })
        }       

        if (filter)  {
            filter.addEventListener('mousedown', (event) => {
                if (search.classList.contains('opened') ){
                    // prevent the search from bluring
                    event.preventDefault()
                }
                
            })  
    
            // control color and dipslay of filter icon based on when it is active or not
            filter.querySelector('i').addEventListener('click', () => {
                if (search.classList.contains('opened') ){
    
                    if (filterContainer.style.display === '') {
                    filterContainer.style.display = 'flex' 
                    filter.querySelector('i').style.color = '#00ff00'
                     }else{
                    filterContainer.style.display = ''
                    filter.querySelector('i').style.color = '#ffffff'
    
                }}}
    
            )
                
            
        }
    }})

// prevent add buttons from bluring the search
document.querySelectorAll('.add').forEach(addIcon =>
    addIcon.addEventListener('mousedown',
        (event) =>{ 
            event.preventDefault()
        })
        )