const menu_div = document.querySelector('#menu-burger' )
const menu_burger = menu_div.querySelector('i') 
const sidenav = document.querySelector('#side-nav')
const modalbg = document.querySelector('#modal-bg')
const closeSideX = document.getElementById('side-nav-x')

const openSideNav = () => {

        sidenav.className = 'opened';
        closeSideX.classList.add('opened')
        showModal('')

}

const closeSideNav = (slide=false) => {

        slide ? sidenav.className = 'closed-slide' : sidenav.className = 'closed-1';
        closeSideX.classList.remove('opened')
        closeModal()
}

function responsiveSideNav(slide=false) {
if (window.innerWidth <= 950 ){

      if (sidenav.className.includes('closed')){
        openSideNav();
      }else{
        closeSideNav(slide);
      }
}}

menu_div.addEventListener('click', responsiveSideNav)
closeSideX.addEventListener('click', closeSideNav)


modalbg.addEventListener('click', (event) => {


  if (modalbg.innerHTML.trim() == ""){
      responsiveSideNav(true)
  }


closeModal(event)

})

window.addEventListener('resize', () => {

  if (window.innerWidth >= 950){
    if ((modalbg.className.includes('show') && modalbg.innerHTML.trim() == "" )|| (!modalbg.className.includes('show'))){
    closeSideNav()      

  }

}
})

// open and close dropdowns in sidenav
document.querySelectorAll('#side-nav ul button').forEach(navlink => {
    let dropdownConatainer =  navlink.nextElementSibling
    navlink.onclick =  () => {
        dropdownConatainer.classList.toggle("active")
      }
})


