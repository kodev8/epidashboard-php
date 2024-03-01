let activityTabs = document.querySelector('.tab-container')
const feed = document.getElementById('feed-container')
let showMore = document.getElementById('show-more')

// Function to fetch and populate data
const fetchAndPopulateData = (tab) => {
    fetch('/activity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'tab':  tab // Use the appropriate active tab value
        })
    })
    .then(resp => {
        if (!resp.ok) {
            return handleAPITypeErrors(resp)
        }
        return resp.json();
    })
    .then(data => {
        

        showMore.disabled =  false
        // reset inner html
        feed.innerHTML = '';

        if( data == null){
            
            showMore.disabled =  true
            
            feed.insertAdjacentElement('beforeend', noFeed())
            return 
        }

        data.forEach((row, index) => {


            var component;
// (avatar, name, action, desc, time,  registration=true, token=null)
        if( tab  == 'your' || tab == 'other'){
            component = activityFeedComponent(
                row.avatar,
                row.admin,
                row.action,
                row.des,
                row.time,
                false,
                row.tokenData ? row.tokenData : ''
            );
                
        }
        else {

            component = activityFeedComponent(
                'user',
                row.fname + ' '  + row.lname,
                'Request',
                'has requested to become an admin.',
                row.request_at,
                true,
                row.tokenData
            )

         }


        feed.insertAdjacentElement('beforeend', component);
       
         })

        handleSendResetLink(tab)
        handleRegistration(tab)

    })
            

    .catch(error =>  {
            if (error.message != 'handled'){
                createToast('error', error?.message);
            
           }
        
    })
};

activityTabs.querySelectorAll('input[type=radio]').forEach(tab => tab.addEventListener('click', ()=> fetchAndPopulateData(tab.value)))


feed.addEventListener('scroll', debounce(() => {
    if (Math.abs(feed.scrollHeight - feed.scrollTop - feed.clientHeight) < 1){
        showMore.disabled = true
    }
    else{
        showMore.disabled = false
    }
}, 600, 1000
))


function getTopVisibleItemIndex() {
var items = feed.querySelectorAll('.activity-comments__item')

  const containerRect = feed.getBoundingClientRect();
  for (let i = 0; i < items.length; i++) {
    const itemRect = items[i].getBoundingClientRect();
    if (itemRect.top >= containerRect.top ) {
      return i;
    }
  }
  return -1; // If no item is found, return -1
}




showMore.addEventListener('click', () => {

var items = feed.querySelectorAll('.activity-comments__item')
    
const currentTopIndex = getTopVisibleItemIndex();
(currentTopIndex)
const targetIndex = currentTopIndex + 3;

// Ensure the target index is within the valid range of items
if (targetIndex >= 0 && targetIndex < items.length) {
// Calculate the cumulative height of items up to the target item, accounting for padding
let cumulativeHeight = 0;
for (let i = 0; i < targetIndex; i++) {
const itemStyle = window.getComputedStyle(items[i]);
const paddingTop = parseFloat(itemStyle.paddingTop);
const paddingBottom = parseFloat(itemStyle.paddingBottom);
cumulativeHeight += items[i].offsetHeight + paddingTop + paddingBottom;

}



// Snap to the top of the target item
feed.scrollTo({
top: cumulativeHeight,
behavior: 'smooth', 
});
}
})

// Call fetchAndPopulateData on initial page load

function handleSendResetLink(tab) {

    if (tab != 'other'){
        return 
    }
    send_reset_buttons = document.querySelectorAll('.send_reset') 

    if (send_reset_buttons) {

        send_reset_buttons.forEach(button => button.addEventListener('click', () => {

            let token = button.parentElement.querySelector('input[name=tokenData]')

            fetch('/reset-password/send', {
                method: "PATCH",
                headers:{
                    'Content-Type':"application/json"
                },
                body : JSON.stringify({
                     tokenData : token.value 
                    })
            })
            .then(resp => {
                if (!resp.ok) {
                return handleAPITypeErrors(resp)
                }
                        return resp.json();

            }).then(res => {

                handleJson(res)

                        
                })  
                .catch(error =>  {
                    if (error.message != 'handled'){
                        createToast('error', error.message);
                    }
                })
            })
        )
    }
}
        

function handleRegistration(tab) {
    if (tab != 'registrations'){
        return 
    }
    addRequestToButtons('confirm_superuser', sendRegisterData, 'accept', {as_super: true}) 
    addRequestToButtons('confirm_request', sendRegisterData, 'accept', {as_super: false})
    addRequestToButtons('reject_request', sendRegisterData, 'reject') 
}

function addRequestToButtons(className, func, route, json={}) {

    let buttons = document.querySelectorAll(`.${className}`)
    if (buttons) {
        buttons.forEach(button => button.addEventListener('click', () => {

            let token = button.parentElement.querySelector('input[name=tokenData]')
             func(route,  {tokenData: token.value, ...json})
            
             })
        )
    }
}



function sendRegisterData (handler, json_body) {
    
    fetch(`/admin/${handler}`, {
                method: 'POST',
                body: JSON.stringify(
                    json_body
                )
                })
                .then(resp => {

                    if (!resp.ok) {
            return handleAPITypeErrors(resp)
             }
                    return resp.json()

            }).then(res => {

                handleJson(res)
                    
                })  
                .catch(error =>  {
                if (error.message != 'handled'){
                    createToast('error', error.message);
                }
            })
        }