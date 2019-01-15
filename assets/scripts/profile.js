const profileContainer = document.querySelector('.profile-container');

function fetchProfile(userId) {
  fetch('../../app/api/profile.php?user='+userId)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    let userData = myJson
    let userPosts = userData.posts;
    console.log(userData);
    console.log(userPosts);
    if(userData.error){
        profileContainer.innerHTML += 'User "'+username+'" does not exist!';
    } else {
        profileContainer.innerHTML += createProfile(userData);
        profileContainer.innerHTML += createPosts(userPosts);
        addEvents();
    }

  });
}

if(getUrlParameter('u') !== ""){
    fetchProfile(getUrlParameter('u'));
}

const createProfile = (userData) => {
    console.log(userData.banner_image_path)
    let profilePage = `
    <div class="profile">
        <div class="profile-info">
            <img class="post-profile" src="${userData.image_path}"/>
            <a href="?u=${userData.username}">${userData.username}</a>
        `

    if(!userData.self){

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

        profilePage += `
        <a class="right-side" href="../profile.php">Edit Profile</a>
    `
    }

    profilePage += `
        <img class="profile-banner" src="${userData.banner_image_path}">
        </div>
    </div>
    `

    return profilePage
}
