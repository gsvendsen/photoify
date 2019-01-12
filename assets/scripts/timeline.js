const postContainer = document.querySelector('.post-wrapper')

function Comparator(a, b) {
  if (a[1] < b[1]) return -1;
  if (a[1] > b[1]) return 1;
  return 0;
}

const createTimeline = (timelineData) => {
    console.log(timelineData)
    postContainer.innerHTML += createPosts(timelineData);
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
