const postContainer = document.querySelector('.post-wrapper')

function Comparator(a, b) {
  if (a[1] < b[1]) return -1;
  if (a[1] > b[1]) return 1;
  return 0;
}

const createTimeline = (timelineData) => {
    console.log(timelineData)
    if(timelineData !== null){
        postContainer.innerHTML += createPosts(timelineData);
    } else {
        postContainer.textContent = "Looks like you haven't posted anything, try doing that!"
    }
}



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

function fetchUserId() {
  fetch('../../app/api/user.php')
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    fetchTimeline(myJson.id)
  });
}

if(getUrlParameter('u') == "" && postContainer !== null){
    fetchUserId()
}
