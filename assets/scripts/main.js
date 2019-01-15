function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};
// href="/app/likes/update.php?post=${post.id}&like=-1&location=${userData.username}"

const updateLike = (post, isLiked, likeCall, likeCounter) => {

    if(likeCall == "dislike"){

        if(isLiked == "true"){
            post.childNodes[1].childNodes[1].setAttribute("fill", "grey")
            post.dataset.liked = false;
        } else {
            console.log("Already disliked this post..")
        }

    } else {

        if(isLiked == "false"){
            post.childNodes[1].childNodes[1].setAttribute("fill", "red")
            post.dataset.liked = true;
        } else {
            console.log("Already liked this post..")
        }
    }
}

const createComments = (postId, commentElement) => {
    commentElement.innerHTML = ""

    fetch(`../../app/api/comments.php?post=${postId}`)
    .then(function(response) {
      return response.json();
    })
    .then(function(commentData) {
        console.log(commentData)
        if(commentData.error == null){

            commentData.forEach((comment) => {

                let commentDiv = `
                <div class="comment">
                    <img class="comment-profile" src="${comment.user.image_path}" />
                    <a href="/?u=${comment.user.username}">${comment.user.username}</a>
                    <p class="comment-content">${comment.content}</p>
                `;


                if(comment.self == true){
                commentDiv += `
                    <a class="comment-delete" href="/app/comments/delete.php?id=${comment.id}">Delete</a>
                `
                }

                commentDiv += `
                </div>
                `

                commentElement.innerHTML += commentDiv;


            })
        } else {
            commentElement.innerHTML += `
            <p class="comment-content">This post does not have any comments!</p>
            `
        }
    });
}

const postNewComment = (commentContent, postId) => {
    fetch(`../../app/comments/create.php?post=${postId}&content=${commentContent}`)
    .then(function(response) {
      return response.json();
    })
    .then(function(myJson) {
        console.log(myJson)
    });
}

const addEvents = () => {
    const likeButtons = document.querySelectorAll('.like-button')
    const dislikeButtons = document.querySelectorAll('.dislike-button')
    const commentButtons = document.querySelectorAll('.comment-button')
    const newCommentButtons = document.querySelectorAll('.submit-comment')
    const hideCommentButtons = document.querySelectorAll('.hide-comment-button')

    likeButtons.forEach(likebutton => {
        likebutton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('like-button')){
                postId = e.target.parentNode.parentNode.dataset.id;
                postLiked = e.target.parentNode.dataset.liked;
                likeAmount = e.target.parentNode.querySelector('.post-likes')

                updateLike(e.target.parentNode, postLiked, "like", likeAmount);

                fetch(`../../app/likes/update.php?post=${postId}&like=1`)
                .then(function(response) {
                  return response.json();
                })
                .then(function(myJson) {
                    likeAmount.innerHTML = myJson.likes
                });
            }
        })
    })

    dislikeButtons.forEach(dislikebutton => {
        dislikebutton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('dislike-button')){
                postId = e.target.parentNode.parentNode.dataset.id;
                postLiked = e.target.parentNode.dataset.liked;
                likeAmount = e.target.parentNode.querySelector('.post-likes')
                updateLike(e.target.parentNode, postLiked, "dislike", likeAmount);

                fetch(`../../app/likes/update.php?post=${postId}&like=-1`)
                .then(function(response) {
                  return response.json();
                })
                .then(function(myJson) {
                    likeAmount.innerHTML = myJson.likes
                });
            }
        })
    })

    commentButtons.forEach(commentButton => {
        commentButton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('comment-button')){
                postId = e.target.dataset.id;
                container = e.target.parentNode.parentNode.querySelector('.comment-container');
                commentField = e.target.parentNode.parentNode.parentNode.querySelector('.comment-field')

                commentField.classList.toggle('hide')

                e.target.parentNode.querySelector('.hide-comment-button').classList.toggle('hidden')
                e.target.classList.toggle('hidden')

                createComments(postId, container)

            }
        })
    })

    newCommentButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            commentContent = e.target.parentNode.querySelector('.comment-input').value;
            postId = e.target.dataset.id;
            commentContainer = e.target.parentNode.parentNode.querySelector('.comment-container')
            commentButton = e.target.parentNode.parentNode.querySelector('.comment-button')
            commentButton.classList.add('hidden')

            postNewComment(commentContent, postId)
            createComments(postId, commentContainer)
            e.target.parentNode.querySelector('.comment-input').value = "";
        })
    })

    hideCommentButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if(e.target.classList.contains('hide-comment-button')){
                commentField = e.target.parentNode.parentNode.parentNode.querySelector('.comment-field')

                commentField.classList.toggle('hide')

                e.target.classList.toggle('hidden')
                e.target.parentNode.querySelector('.comment-button').classList.toggle('hidden')
                e.target.parentNode.parentNode.querySelector('.comment-container').innerHTML = ""

            }
        })
    })
}


const createPosts = (postData) => {
    let postMarkup = "";
    postData.forEach((post) => {
        let userData = post.user

        postMarkup += `
            <div class="post-container">
              <div class="post">
                <img class="post-image" src="${post.img_path}"/>
                <div class="post-info" data-id="${post.id}">
                    <p>${post.description}</p>
                    <div class="post-user">
                        <img class="post-profile" src="${userData.image_path}"/>
                        <p class="post-name">${userData.name}</p>
                        <a href="?u=${userData.username}">(${userData.username})</a>
                    </div>
                    <div class="post-options" data-liked="${post.liked}">
                        `;
        // Shows non liked heart referring to like update
        if(post.liked === false){
            postMarkup += `
                <svg class="heart-icon like-button"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="378.94px" height="378.94px" viewBox="0 0 378.94 378.94" style="enable-background:new 0 0 378.94 378.94;" xml:space="preserve">
                    <path d="M348.151,54.514c-19.883-19.884-46.315-30.826-74.435-30.826c-28.124,0-54.559,10.942-74.449,30.826l-9.798,9.8l-9.798-9.8   c-19.884-19.884-46.325-30.826-74.443-30.826c-28.117,0-54.56,10.942-74.442,30.826c-41.049,41.053-41.049,107.848,0,148.885   l147.09,147.091c2.405,2.414,5.399,3.892,8.527,4.461c1.049,0.207,2.104,0.303,3.161,0.303c4.161,0,8.329-1.587,11.498-4.764   l147.09-147.091C389.203,162.362,389.203,95.567,348.151,54.514z M325.155,180.404L189.47,316.091L53.782,180.404   c-28.368-28.364-28.368-74.514,0-102.893c13.741-13.739,32.017-21.296,51.446-21.296c19.431,0,37.702,7.557,51.438,21.296   l21.305,21.312c6.107,6.098,16.897,6.098,23.003,0l21.297-21.312c13.737-13.739,32.009-21.296,51.446-21.296   c19.431,0,37.701,7.557,51.438,21.296C353.526,105.89,353.526,152.039,325.155,180.404z"></path>
                </svg>
            <p class="post-likes">${post.likes}</p>
                <svg class="heart-icon dislike-button" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 490.1 490.1" style="enable-background:new 0 0 490.1 490.1;" xml:space="preserve">
                	<path d="M316.2,400.1V324h105.6c37.6,0,68.3-30.6,68.3-68.3v-2.1c0-0.6-0.1-1.3-0.2-1.9L461.8,75.1C456.6,32.7,427,8.3,380.7,8.3   H171.5c-37.6,0-68.3,30.6-68.3,68.3v196.1c0,5.9-4.8,10.7-10.7,10.7H35.2c-5.9,0-10.7-4.8-10.7-10.7V64.9c0-5.9,4.8-10.7,10.7-10.7   h33.1c6.8,0,12.3-5.5,12.3-12.3S75,29.6,68.3,29.6H35.2C15.8,29.6,0,45.4,0,64.8v207.9c0,19.4,15.8,35.2,35.2,35.2h57.3   c13.8,0,25.8-8,31.6-19.6c0.8,0.5,1.7,0.8,2.7,1.1c3.3,0.9,81.4,23.9,81.4,91.2v85.5c0,5.3,3.5,10.1,8.6,11.7c0.9,0.3,13,4,28.6,4   c13.4,0,29.3-2.7,42.9-12.7C306.8,455.5,316.2,432.3,316.2,400.1z M273.8,449.3c-12.9,9.6-30.7,8.7-41.1,7v-75.6   c0-86-95.2-113.6-99.3-114.8c-1.9-0.5-3.8-0.6-5.6-0.2V76.6c0-24.1,19.6-43.8,43.8-43.8h209.2c34,0,53.1,15.2,56.9,45.7l28,176v1.1   c0,24.1-19.6,43.8-43.8,43.8h-118c-6.8,0-12.3,5.5-12.3,12.3v88.4C291.7,424,285.7,440.5,273.8,449.3z"/>
                </svg>
            `

        // href="/app/likes/update.php?post=${post.id}&like=-1&location=${userData.username}"
        // Shows red liked heart referring to like update
        } else {
            postMarkup += `
                <svg class="heart-icon like-button" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="378.94px" height="378.94px" viewBox="0 0 378.94 378.94" style="enable-background:new 0 0 378.94 378.94;" xml:space="preserve">
                    <path fill="red" d="M348.151,54.514c-19.883-19.884-46.315-30.826-74.435-30.826c-28.124,0-54.559,10.942-74.449,30.826l-9.798,9.8l-9.798-9.8   c-19.884-19.884-46.325-30.826-74.443-30.826c-28.117,0-54.56,10.942-74.442,30.826c-41.049,41.053-41.049,107.848,0,148.885   l147.09,147.091c2.405,2.414,5.399,3.892,8.527,4.461c1.049,0.207,2.104,0.303,3.161,0.303c4.161,0,8.329-1.587,11.498-4.764   l147.09-147.091C389.203,162.362,389.203,95.567,348.151,54.514z M325.155,180.404L189.47,316.091L53.782,180.404   c-28.368-28.364-28.368-74.514,0-102.893c13.741-13.739,32.017-21.296,51.446-21.296c19.431,0,37.702,7.557,51.438,21.296   l21.305,21.312c6.107,6.098,16.897,6.098,23.003,0l21.297-21.312c13.737-13.739,32.009-21.296,51.446-21.296   c19.431,0,37.701,7.557,51.438,21.296C353.526,105.89,353.526,152.039,325.155,180.404z"></path>
                </svg>
            <p class="post-likes">${post.likes}</p>
                <svg class="heart-icon dislike-button" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 490.1 490.1" style="enable-background:new 0 0 490.1 490.1;" xml:space="preserve">
                	<path d="M316.2,400.1V324h105.6c37.6,0,68.3-30.6,68.3-68.3v-2.1c0-0.6-0.1-1.3-0.2-1.9L461.8,75.1C456.6,32.7,427,8.3,380.7,8.3   H171.5c-37.6,0-68.3,30.6-68.3,68.3v196.1c0,5.9-4.8,10.7-10.7,10.7H35.2c-5.9,0-10.7-4.8-10.7-10.7V64.9c0-5.9,4.8-10.7,10.7-10.7   h33.1c6.8,0,12.3-5.5,12.3-12.3S75,29.6,68.3,29.6H35.2C15.8,29.6,0,45.4,0,64.8v207.9c0,19.4,15.8,35.2,35.2,35.2h57.3   c13.8,0,25.8-8,31.6-19.6c0.8,0.5,1.7,0.8,2.7,1.1c3.3,0.9,81.4,23.9,81.4,91.2v85.5c0,5.3,3.5,10.1,8.6,11.7c0.9,0.3,13,4,28.6,4   c13.4,0,29.3-2.7,42.9-12.7C306.8,455.5,316.2,432.3,316.2,400.1z M273.8,449.3c-12.9,9.6-30.7,8.7-41.1,7v-75.6   c0-86-95.2-113.6-99.3-114.8c-1.9-0.5-3.8-0.6-5.6-0.2V76.6c0-24.1,19.6-43.8,43.8-43.8h209.2c34,0,53.1,15.2,56.9,45.7l28,176v1.1   c0,24.1-19.6,43.8-43.8,43.8h-118c-6.8,0-12.3,5.5-12.3,12.3v88.4C291.7,424,285.7,440.5,273.8,449.3z"/>
                </svg>
            `
        }

        if(post.auth === "true"){
            postMarkup += `
            <a href="/../edit.php?post=${post.id}&location=${userData.username}">
                <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" height="401pt" viewBox="0 -1 401.52289 401" width="401pt"><path d="m370.589844 250.972656c-5.523438 0-10 4.476563-10 10v88.789063c-.019532 16.5625-13.4375 29.984375-30 30h-280.589844c-16.5625-.015625-29.980469-13.4375-30-30v-260.589844c.019531-16.558594 13.4375-29.980469 30-30h88.789062c5.523438 0 10-4.476563 10-10 0-5.519531-4.476562-10-10-10h-88.789062c-27.601562.03125-49.96875 22.398437-50 50v260.59375c.03125 27.601563 22.398438 49.96875 50 50h280.589844c27.601562-.03125 49.96875-22.398437 50-50v-88.792969c0-5.523437-4.476563-10-10-10zm0 0"/><path d="m376.628906 13.441406c-17.574218-17.574218-46.066406-17.574218-63.640625 0l-178.40625 178.40625c-1.222656 1.222656-2.105469 2.738282-2.566406 4.402344l-23.460937 84.699219c-.964844 3.472656.015624 7.191406 2.5625 9.742187 2.550781 2.546875 6.269531 3.527344 9.742187 2.566406l84.699219-23.464843c1.664062-.460938 3.179687-1.34375 4.402344-2.566407l178.402343-178.410156c17.546875-17.585937 17.546875-46.054687 0-63.640625zm-220.257812 184.90625 146.011718-146.015625 47.089844 47.089844-146.015625 146.015625zm-9.40625 18.875 37.621094 37.625-52.039063 14.417969zm227.257812-142.546875-10.605468 10.605469-47.09375-47.09375 10.609374-10.605469c9.761719-9.761719 25.589844-9.761719 35.351563 0l11.738281 11.734375c9.746094 9.773438 9.746094 25.589844 0 35.359375zm0 0"/></svg>

            </a>
            <a href="/app/posts/delete.php?locale=${post.id}&location=${userData.username}">
                <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="774.266px" height="774.266px" viewBox="0 0 774.266 774.266" style="enable-background:new 0 0 774.266 774.266;" xml:space="preserve">
                		<path d="M640.35,91.169H536.971V23.991C536.971,10.469,526.064,0,512.543,0c-1.312,0-2.187,0.438-2.614,0.875    C509.491,0.438,508.616,0,508.179,0H265.212h-1.74h-1.75c-13.521,0-23.99,10.469-23.99,23.991v67.179H133.916    c-29.667,0-52.783,23.116-52.783,52.783v38.387v47.981h45.803v491.6c0,29.668,22.679,52.346,52.346,52.346h415.703    c29.667,0,52.782-22.678,52.782-52.346v-491.6h45.366v-47.981v-38.387C693.133,114.286,670.008,91.169,640.35,91.169z     M285.713,47.981h202.84v43.188h-202.84V47.981z M599.349,721.922c0,3.061-1.312,4.363-4.364,4.363H179.282    c-3.052,0-4.364-1.303-4.364-4.363V230.32h424.431V721.922z M644.715,182.339H129.551v-38.387c0-3.053,1.312-4.802,4.364-4.802    H640.35c3.053,0,4.365,1.749,4.365,4.802V182.339z"/>
                		<rect x="475.031" y="286.593" width="48.418" height="396.942"/>
                		<rect x="363.361" y="286.593" width="48.418" height="396.942"/>
                		<rect x="251.69" y="286.593" width="48.418" height="396.942"/>
                </svg>
            </a>
            `
        }
            postMarkup += `
                        <svg data-id="${post.id}" class="heart-icon comment-button right-side" fill="black" xmlns="http://www.w3.org/2000/svg" height="682pt" viewBox="-21 -47 682.66669 682" width="682pt"><path d="m552.011719-1.332031h-464.023438c-48.515625 0-87.988281 39.464843-87.988281 87.988281v283.972656c0 48.414063 39.300781 87.816406 87.675781 87.988282v128.863281l185.191407-128.863281h279.144531c48.515625 0 87.988281-39.472657 87.988281-87.988282v-283.972656c0-48.523438-39.472656-87.988281-87.988281-87.988281zm50.488281 371.960937c0 27.835938-22.648438 50.488282-50.488281 50.488282h-290.910157l-135.925781 94.585937v-94.585937h-37.1875c-27.839843 0-50.488281-22.652344-50.488281-50.488282v-283.972656c0-27.84375 22.648438-50.488281 50.488281-50.488281h464.023438c27.839843 0 50.488281 22.644531 50.488281 50.488281zm0 0"/><path d="m171.292969 131.171875h297.414062v37.5h-297.414062zm0 0"/><path d="m171.292969 211.171875h297.414062v37.5h-297.414062zm0 0"/><path d="m171.292969 291.171875h297.414062v37.5h-297.414062zm0 0"/></svg>
                        <svg data-id="${post.id}" class="heart-icon hide-comment-button hidden right-side" fill="#FD6A02" xmlns="http://www.w3.org/2000/svg" height="682pt" viewBox="-21 -47 682.66669 682" width="682pt"><path d="m552.011719-1.332031h-464.023438c-48.515625 0-87.988281 39.464843-87.988281 87.988281v283.972656c0 48.414063 39.300781 87.816406 87.675781 87.988282v128.863281l185.191407-128.863281h279.144531c48.515625 0 87.988281-39.472657 87.988281-87.988282v-283.972656c0-48.523438-39.472656-87.988281-87.988281-87.988281zm50.488281 371.960937c0 27.835938-22.648438 50.488282-50.488281 50.488282h-290.910157l-135.925781 94.585937v-94.585937h-37.1875c-27.839843 0-50.488281-22.652344-50.488281-50.488282v-283.972656c0-27.84375 22.648438-50.488281 50.488281-50.488281h464.023438c27.839843 0 50.488281 22.644531 50.488281 50.488281zm0 0"/><path d="m171.292969 131.171875h297.414062v37.5h-297.414062zm0 0"/><path d="m171.292969 211.171875h297.414062v37.5h-297.414062zm0 0"/><path d="m171.292969 291.171875h297.414062v37.5h-297.414062zm0 0"/></svg>
                        </div>

                        <div class="comment-container">
                        </div>
                    </div>
                    <div class="comment-field hide">
                        <input placeholder="Enter your comment here.." type="text" class="comment-input" data-id="${post.id}">
                        <button class="submit-comment" data-id="${post.id}">Submit</button>
                    </div>
                </div><!-- /post -->
              </div><!-- /post-container -->
            `

    })

    return postMarkup
}

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

const homeButton = document.querySelector('.home-icon');
const postButton = document.querySelector('.post-icon');
const profileButton = document.querySelector('.profile-icon');

const pathname = window.location.pathname;

if(pathname == "/post.php" && postButton !== null){
    console.log("On post page.")
    postButton.classList.toggle('active');
} else if (pathname == "/" && getUrlParameter('u') == "" && homeButton !== null) {
    console.log("On home page.")
    homeButton.classList.toggle('active');
} else if (getUrlParameter('u') !== "" && profileButton !== null){
    console.log("On profile page.")
    profileButton.classList.toggle('active')
}

const inputs = document.querySelectorAll('textarea');
const bottomNav = document.querySelector('.bottom-bar')

inputs.forEach(input => {
    input.addEventListener('focus', ()=>{
        bottomNav.classList.toggle('hide');
    })

    input.addEventListener('blur', ()=>{
        bottomNav.classList.toggle('hide');
    })
})

var openFile = function(event) {
    var input = event.target;

    var reader = new FileReader();
    reader.onload = function(){
      var dataURL = reader.result;
      var output = document.getElementById('output');
      output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);
  };
