// skips animations when clicking on body on login page for quicker login
const body = document.querySelector('body')
body.addEventListener('click', () => {
        const terminal_text = document.querySelectorAll('.terminal-text')
        terminal_text.forEach((e) => e.classList.add('skip-animation'))
    }
    
    )



// handles all custom inputs and their labels
handleCustomInputs();

// handle model Actions for all
showActionModal('add')
showActionModal('delete')

// ==============================
// handle all inline edits
handleEdit()

