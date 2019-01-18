const profileContainer = document.querySelector('.profile-container');

// Fetches profile from api and creates page using createProfile() and createPosts()
const fetchProfile = (username) => {
  fetch('../../app/api/profile.php?user='+username)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    let userData = myJson
    let userPosts = userData.posts;

    if(userData.error){
        profileContainer.innerHTML += '<p class="warning-message">User "'+username+'" could not be found.</p>';
    } else {
        profileContainer.innerHTML += createProfile(userData);

        if(userPosts.length == 0){
            if(userData.self){
                profileContainer.innerHTML += `
                <p class="message">Nothing posted yet! Try doing that <a href="/post.php">here</a></p>
                `
            } else {
                profileContainer.innerHTML += `
                <p class="message">This user has not posted anything yet!</p>
                `
            }
        } else {
            profileContainer.innerHTML += createPosts(userPosts);
        }
        addEvents();
    }

  });
}

// Fetches profile if paramter u exists
if(getUrlParameter('u') !== ""){
    fetchProfile(getUrlParameter('u'));
}

// Creates profile header using userData
const createProfile = (userData) => {

    let profilePage = `
    <div class="profile">
        <div class="profile-info">
            <img class="post-profile" src="${userData.image_path}"/>
            <a href="?u=${userData.username}">${userData.username}</a>
        `

    if(!userData.self){
        // Shows follow options if user is not self
        if(userData.follows == "false"){
            profilePage += `
            <a class="right-side" href="app/follows/update.php?follow=${userData.id}&location=${userData.username}">Follow</a>
            `
        } else {
            profilePage += `
            <a class="right-side" href="app/follows/update.php?follow=${userData.id}&location=${userData.username}&unfollow=true">Unfollow</a>
            `
        }

    } else {
        // Edit profile option for your own profile
        profilePage += `
        <a class="right-side" href="../profile.php">Edit Profile</a>
    `
    }

    // Banner image
    profilePage += `
        </div>
        <img class="profile-banner" src="${userData.banner_image_path}">
        <div class="profile-bio">
            <p>${userData.biography}</p>
        </div>
    </div>
    `

    return profilePage
}
