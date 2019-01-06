const profileContainer = document.querySelector('.profile-container');

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

function fetchUser(username) {
  fetch('../../app/api/profile.php?user='+username)
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
        profileContainer.innerHTML += createPosts(userPosts, userData);
    }

  });
}

if(getUrlParameter('u') !== ""){
    fetchUser(getUrlParameter('u'));
}

const createProfile = (userData) => {
    return `
    <div class="profile">
        <h3>${userData.name}</h3>
        <img class="post-profile" src="${userData.image_path}"/>
        <a href="?u=${userData.username}">${userData.username}</a>
    </div>
    `
}

const createPosts = (postData, userData) => {
    let postMarkup = "";
    postData.forEach((post) => {
        if(post.auth === "true"){
            postMarkup += `
                <div class="post-container">
                  <div class="post">
                    <img class="post-image" src="${post.img_path}"/>
                    <div class="post-info">
                        <p>${post.description}</p>
                        <div class="post-user">
                            <img class="post-profile" src="${userData.image_path}"/>
                            <p>${userData.name}</p>
                            <a href="?u=${userData.username}">(${userData.username})</a>
                        </div>
                        <div class="post-options">
                            <a href="/../edit.php?post=${post.id}&location=${userData.username}" class="btn btn-primary">Edit Post</a>
                            <a href="/app/posts/delete.php?locale=${post.id}&location=${userData.username}" class="btn btn-primary">Delete Post</a>
                            <a href="/app/likes/update.php?post=${post.id}&like=1&location=${userData.username}" class="btn btn-primary">Like</a>
                            <a href="/app/likes/update.php?post=${post.id}&like=-1&location=${userData.username}" class="btn btn-primary">Dislike</a>
                        </div>
                        <p> Amount of likes: ${post.likes}</p>

                    </div>
                </div><!-- /post -->
              </div>
            `
        } else {
            postMarkup += `
                <div class="post-container">
                  <div class="post">
                    <img class="post-image" src="${post.img_path}"/>
                    <div class="post-info">
                        <p>${post.description}</p>
                        <div class="post-user">
                            <img class="post-profile" src="${userData.image_path}"/>
                            <p>${userData.name}</p>
                            <a href="?u=${userData.username}">(${userData.username})</a>
                        </div>
                        <div class="post-options">
                            <a href="/app/likes/update.php?post=${post.id}&like=1&location=${userData.username}" class="btn btn-primary">Like</a>
                            <a href="/app/likes/update.php?post=${post.id}&like=-1&location=${userData.username}" class="btn btn-primary">Dislike</a>
                        </div>
                        <p> Amount of likes: ${post.likes}</p>

                    </div>
                </div><!-- /post -->
              </div>
            `
        }
    })

    return postMarkup
}
