// Function which gets url parameter from name
const getUrlParameter = (name) => {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    let results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

const hamButton = document.querySelector(".hamburger-menu");
const hamBar = document.querySelector(".bar");
const navBar = document.querySelector('.bottom-bar');
const menu = document.querySelector('.menu');

/* Hamburger menu toggle for mobile/tablet */
if(hamButton !== null){
    hamButton.addEventListener("click", ()=>{
        hamBar.classList.toggle("clicked");
        menu.classList.toggle("open");
        document.body.classList.toggle("no-scroll");
    });
}

const homeButton = document.querySelector('.home-bar-icon');
const postButton = document.querySelector('.post-bar-icon');
const profileButton = document.querySelector('.profile-bar-icon');
const pathname = window.location.pathname;

// Update events and nav icons depending on current path
if(pathname == "/post.php" && postButton !== null){

    postButton.classList.toggle('active');

} else if (pathname == "/" && getUrlParameter('u') == "" && homeButton !== null) {

    homeButton.classList.toggle('active');

} else if (getUrlParameter('u') !== "" && profileButton !== null){

    profileButton.classList.toggle('active')

} else if(pathname == "/profile.php") {

    accountDeletionEvent();

}
