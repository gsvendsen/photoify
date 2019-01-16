const postContainer = document.querySelector('.post-wrapper')

// Creates posts from timeLine data
const createTimeline = (timelineData) => {
    console.log(timelineData)
    if(timelineData !== null){
        postContainer.innerHTML += createPosts(timelineData);
    } else {
        postContainer.textContent = "Looks like you haven't posted anything, try doing that!"
    }
}


// Fetches timeLine data from api using userId
function fetchTimeline(userId) {
  fetch('../../app/api/timeline.php?user='+userId)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    createTimeline(myJson);
    addEvents();
  });
}

// Gets userId from current logged in session
function fetchUserId() {
  fetch('../../app/api/user.php')
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    fetchTimeline(myJson.id)
  });
}

// Fetches if url parameter is empty and is on home page
if(getUrlParameter('u') == "" && postContainer !== null){
    fetchUserId()
}
