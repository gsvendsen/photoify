// Function which updates the looks of the like and dislike buttons
const updateLike = (post, isLiked, isDisliked, likeCall, likeCounter) => {

    if(likeCall == "dislike"){

        // If dislike is called on a liked post
        if(isLiked == "true"){

            post.querySelector('.like-button').childNodes[1].childNodes[1].setAttribute("fill", "grey")
            post.querySelector('.dislike-button').childNodes[1].childNodes[1].setAttribute("fill", "red")

            post.dataset.liked = false;
            post.dataset.disliked = true;

        // If dislike is called on a disliked post
        } else if (isDisliked == "true") {

            post.querySelector('.dislike-button').childNodes[1].childNodes[1].setAttribute("fill", "grey")

            post.dataset.liked = false;
            post.dataset.disliked = false;

        // If dislike is called on a neutral post
        } else if (isDisliked == "false"){

            post.querySelector('.dislike-button').childNodes[1].childNodes[1].setAttribute("fill", "red")

            post.dataset.liked = false;
            post.dataset.disliked = true;
        }

    } else {

        // If like is called on a liked post
        if(isLiked == "true"){

            post.querySelector('.like-button').childNodes[1].childNodes[1].setAttribute("fill", "grey")

            post.dataset.liked = false;
            post.dataset.disliked = false;

        // If like is called on a disliked post
        } else if (isDisliked == "true") {

            post.querySelector('.like-button').childNodes[1].childNodes[1].setAttribute("fill", "red")
            post.querySelector('.dislike-button').childNodes[1].childNodes[1].setAttribute("fill", "grey")

            post.dataset.liked = true;
            post.dataset.disliked = false;

        // If like is called on a neutral post
        } else if (isLiked == "false"){

        post.querySelector('.like-button').childNodes[1].childNodes[1].setAttribute("fill", "red")

        post.dataset.liked = true;
        post.dataset.disliked = false;
        }
    }
}
