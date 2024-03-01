var change_avatar = document.getElementById('change-avatar') // get change avaatr button

//send get request to retrieve modal as it is rendered by server
if (change_avatar){
    change_avatar.addEventListener('click', ()=>{
        fetch('/avatars', {
            method: 'GET'
        })
        .then(resp =>{ 
            if (!resp.ok) {
            return handleAPITypeErrors(resp)
             }
        return resp.text()
    }
        )
        .then(res => {
            if(res == null){
                return 
            }
            

            let functions = [setAvatar]
            showModal(res, functions)
        })
        .catch(error =>  {
            if (error.message != 'handled'){
                createToast('error', error?.message);
            
            }
        })
    })
}


const setAvatar = () => {
    
    
    document.querySelectorAll('.avatar-select-item').forEach(avatar => {
        avatar.addEventListener('click', () => {
            selectAvatar(avatar)
            
        })
    })
}

// handles diplay of background tiles and confirm button
const selectAvatar = (selected) => {
    let confirmAvatar = document.getElementById('confirm-avatar')

    document.querySelectorAll('.avatar-select-item').forEach(avatar => {
        if (avatar.id == selected.id){
            avatar.classList.toggle('selected')
            if (!avatar.classList.contains('selected')){
                confirmAvatar.classList.add('hide')
            }else{
                confirmAvatar.classList.remove('hide')
                confirmAvatar.addEventListener('click', ()=> {
                    updateAvatarAPI(avatar.id)   
                })
            }
        }else{
            avatar.classList.remove('selected')
        }
    })
}

// send a request to update avatar info
const updateAvatarAPI = (avatar_name) => {
    let mainAvatar = document.getElementById('avatar')
    let img = mainAvatar.querySelector('img')

    fetch('/avatars', {
        method: 'PATCH',
        body: JSON.stringify({
            'avatar_name': avatar_name
        })
    }).then(resp => {
            if (!resp.ok) {
            return handleAPITypeErrors(resp)
            }

            return resp.json()
        })
        .then(res => {
            
            if (res == null){
                throw new Error('Server Error: Invalid Data');
            }

            
            createToast(res.type, res.message)
            img.src = window.location.origin + '/static/assets/avatars/' + avatar_name + '.png'
            closeModal()

            })
        .catch(error =>  {
                if (error.message != 'handled'){
                    createToast('error', error.message);
                }
            })
}
    


