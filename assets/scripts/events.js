// All eventListeners which are created after posts are created
const addEvents = () => {
    const likeButtons = document.querySelectorAll('.like-button')
    const dislikeButtons = document.querySelectorAll('.dislike-button')
    const commentButtons = document.querySelectorAll('.comment-button')
    const newCommentButtons = document.querySelectorAll('.submit-comment')
    const hideCommentButtons = document.querySelectorAll('.hide-comment-button')
    const deleteButtons = document.querySelectorAll('.delete-button')

    // Click event for deleting a post
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e)=> {
                if(e.target.classList.contains('delete-button')){
                    const postId = e.target.parentNode.parentNode.dataset.id;
                    createDeleteMessage(postId);
                }
        })
    })

    // Click event for liking or removing like
    likeButtons.forEach(likebutton => {
        likebutton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('like-button')){
                const postId = e.target.parentNode.parentNode.dataset.id;
                const postLiked = e.target.parentNode.dataset.liked;
                const postDisliked = e.target.parentNode.dataset.disliked;

                let likeAmount = e.target.parentNode.querySelector('.post-likes')

                updateLike(e.target.parentNode, postLiked, postDisliked, "like", likeAmount);

                if(postLiked !== "true"){
                    fetch(`../../app/likes/update.php?post=${postId}&like=1`)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(myJson) {
                        likeAmount.innerHTML = myJson.likes
                    });
                } else {
                    fetch(`../../app/likes/update.php?post=${postId}&like=1&remove=true`)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(myJson) {
                        likeAmount.innerHTML = myJson.likes
                    });
                }
            }
        })
    })

    // Click event for disliking or removing dislike
    dislikeButtons.forEach(dislikebutton => {
        dislikebutton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('dislike-button')){
                const postId = e.target.parentNode.parentNode.dataset.id;
                const postLiked = e.target.parentNode.dataset.liked;
                const postDisliked = e.target.parentNode.dataset.disliked;

                let likeAmount = e.target.parentNode.querySelector('.post-likes')
                updateLike(e.target.parentNode, postLiked, postDisliked, "dislike", likeAmount);

                if(postDisliked !== "true"){
                    fetch(`../../app/likes/update.php?post=${postId}&like=-1`)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(myJson) {
                        likeAmount.innerHTML = myJson.likes
                    });
                } else {
                    fetch(`../../app/likes/update.php?post=${postId}&like=-1&remove=true`)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(myJson) {
                        likeAmount.innerHTML = myJson.likes
                    });
                }
            }
        })
    })

    // Click event for showing comment section
    commentButtons.forEach(commentButton => {
        commentButton.addEventListener('click', (e)=>{
            if(e.target.classList.contains('comment-button')){
                const postId = e.target.dataset.id;
                const container = e.target.parentNode.parentNode.querySelector('.comment-container');
                const commentField = e.target.parentNode.parentNode.parentNode.querySelector('.comment-field')

                commentField.classList.toggle('hide')

                e.target.parentNode.querySelector('.hide-comment-button').classList.toggle('hidden')
                e.target.classList.toggle('hidden')

                createComments(postId, container)

            }
        })
    })

    // Click event for submitting a new comment
    newCommentButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const commentContent = e.target.parentNode.querySelector('.comment-input').value;
            const postId = e.target.dataset.id;
            const commentContainer = e.target.parentNode.parentNode.querySelector('.comment-container')
            const commentButton = e.target.parentNode.parentNode.querySelector('.comment-button')
            commentButton.classList.add('hidden')

            postNewComment(commentContent, postId)

            setTimeout(()=> {
                createComments(postId, commentContainer)
            }, 200)


            e.target.parentNode.querySelector('.comment-input').value = "";
        })
    })

    // Click event for hiding comment section
    hideCommentButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if(e.target.classList.contains('hide-comment-button')){
                const commentField = e.target.parentNode.parentNode.parentNode.querySelector('.comment-field')

                commentField.classList.toggle('hide')

                e.target.classList.toggle('hidden')
                e.target.parentNode.querySelector('.comment-button').classList.toggle('hidden')
                e.target.parentNode.parentNode.querySelector('.comment-container').innerHTML = ""

            }
        })
    })
}

// Click event for deleting your account
const accountDeletionEvent = () => {
    const deleteAccountButton = document.querySelector('.delete-account-button')
    deleteAccountButton.addEventListener('click', (e) => {
        const accountId = e.target.dataset.id;
        createDeleteMessage(accountId, true)
    })
}


const inputs = document.querySelectorAll('textarea');
const bottomNav = document.querySelector('.bottom-bar')

// Hides bottom nav bar on input focus to not collide with mobile keyboards
inputs.forEach(input => {
    input.addEventListener('focus', ()=>{
        bottomNav.classList.toggle('hide');
    })

    input.addEventListener('blur', ()=>{
        bottomNav.classList.toggle('hide');
    })
})

// Shows the file selected to upload directly on the page
let openFile = function(event) {
    
    let input = event.target;
    let reader = new FileReader();

    reader.onload = function(){
      let dataURL = reader.result;
      let output = document.getElementById('output');
      output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);
  };
