// Function that prompts user with choice on post or account deletion
const createDeleteMessage = (id, isAccountDeletion) => {

    let deleteMarkup = ``

    // If the delete message is for a post
    if(!isAccountDeletion){
        deleteMarkup = `
            <div class="delete-container">
                <div class="delete-box"
                    <p>Would you like to delete this post?</p>
                    <div class="delete-options">
                        <a href="/app/posts/delete.php?locale=${id}">Yes</a>
                        <p class="delete-deny" href="">No</p>
                    </div>
                </div>
            </div>
        `
    } else {

        // If the delete message is for an account
        deleteMarkup = `
            <div class="delete-container">
                <div class="delete-box"
                    <p>Are you sure you want to delete your account?</p>
                    <div class="delete-options">
                        <a href="/app/users/delete.php">Delete Account</a>
                        <p class="delete-deny" href="">Go Back</p>
                    </div>
                </div>
            </div>
        `
    }

    document.body.innerHTML += deleteMarkup
    document.body.classList.toggle('no-scroll')
    const denyButton = document.querySelector('.delete-deny');

    // If user cancels deletion box
    denyButton.addEventListener('click', (e) => {
        let deleteContainer = e.target.parentNode.parentNode.parentNode;
        document.body.removeChild(deleteContainer);
        document.body.classList.toggle('no-scroll')
        if(isAccountDeletion){
            accountDeletionEvent();
        } else {
            addEvents();
        }
    })
}
