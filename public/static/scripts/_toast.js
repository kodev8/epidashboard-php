// toast notifications 
const notifications = document.querySelector(".notifications");

const toastDetails = {
    timer: 5000, // how long the toast will remain on the screen
    success: { // toast types
        icon: 'fa-circle-check',
        text: 'Success: ',
    },
    error: {
        icon: 'fa-circle-xmark',
        text: 'Error: ',
    },
    warning: {
        icon: 'fa-triangle-exclamation',
        text: 'Warning: ',
    },
    info: {
        icon: 'fa-circle-info',
        text: 'Info: ',
    }
}

removeToast = (toast) => {
    toast.classList.add("hide-toast");
    if(toast.timeoutId) clearTimeout(toast.timeoutId); // Clearing the timeout for the toast
    setTimeout(() => toast.remove(), 500); // Removing the toast after 500ms
}

createToast = (toast_type, message) => {
    // type of toast success, error, warning, info
    // Getting the icon and text for the toast based on the id passed
    var createState = true

    var current_toasts = document.querySelectorAll('.toast');
    current_toasts.forEach(toast => {
        
        // make sure not to repeat many of the same toasts
        let current_message = toast.querySelector('span').innerText?.toLowerCase()
        if (toast.getAttribute('class').includes(toast_type) && current_message.includes(message?.toLowerCase())){
            createState = false
            return;
        }
    });

    if (createState){
    const { icon, text } = toastDetails[toast_type];

    const toast = document.createElement("li"); // Creating a new 'li' element for the toast

    toast.className = `toast ${toast_type}`; // Setting the classes for the toast
    // Setting the inner HTML for the toast

    toast.innerHTML = `<div class="column">
                         <i class="fa-solid ${icon}"></i>
                         <span>${message}</span>
                      </div>
                      <i class="fa-solid fa-xmark" onclick="removeToast(this.parentElement)"></i>`;
    
                      notifications.appendChild(toast); // Append the toast to the notification ul

    // Setting a timeout to remove the toast after the specified duration
    toast.timeoutId = setTimeout(() => removeToast(toast), toastDetails.timer);}
}


