// Function which fetches comments from postId and puts them in commentElement
const createComments = (postId, commentElement) => {
    commentElement.innerHTML = ""

    fetch(`../../app/api/comments.php?post=${postId}`)
    .then(function(response) {
      return response.json();
    })
    .then(function(commentData) {

        if(commentData.error == null){

            // Adds all comments from commentData into comment container
            commentData.forEach((comment) => {

                let commentDiv = `
                <div class="comment">
                    <img class="comment-profile" src="${comment.user.image_path}" />
                    <a href="/?u=${comment.user.username}">${comment.user.username}</a>
                    <p class="comment-content">${comment.content}</p>
                `;

                // Shows delete option if user has authorization
                if(comment.self == true){
                commentDiv += `
                    <p class="comment-delete" data-id="${comment.id}" href="#">Delete</p>
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

        const deleteCommentButtons = document.querySelectorAll('.comment-delete')

        // Click event for deleting comments
        deleteCommentButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                const commentId = e.target.dataset.id;
                fetch(`../../app/comments/delete.php?id=${commentId}`)
                .then(function(response) {
                  return response.json();
                })
                .then(function(success) {

                    if(success.success){

                        postId = e.target.parentNode.parentNode.parentNode.dataset.id;
                        container = e.target.parentNode.parentNode.parentNode.querySelector('.comment-container');

                        createComments(postId, container)
                    }

                })
            })
        })

    });
}

// Fetch function which inserts new comment into database
const postNewComment = (commentContent, postId) => {
    fetch(`../../app/comments/create.php?post=${postId}&content=${commentContent}`)
    .then(function(response) {
      return response.json();
    })
    .then(function(myJson) {
    });
}
