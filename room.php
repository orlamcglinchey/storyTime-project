<?php
session_start();
//setup
// stops people with the link and no login from entering rooms
if (!isset($_SESSION['user'])) { 
  header('Location: index.php'); 
  exit; }
$user = $_SESSION['user'];
$id = $_GET['id'];


//filepath for room data
$file = "data/rooms/$id.json";

//php array room
$room = json_decode(file_get_contents($file), true);
?>



<link rel="stylesheet" href="style.css">
<nav>
  <a href="dashboard.php">Back</a>
  <a href="logout.php">Logout</a>
</nav>

<div class="page">
<div class="head">
<h2><?= $room['title']?></h2>
</div>





<div class="layout">
  <div class = "storypart">
<div id="story" style="background:white; border:1px solid #ccc; padding:10px; overflow-y:auto;min-height:200px; max-height:400px; width:600px;">
  Nothing written yet
</div>



<br>
<textarea id="input" placeholder="Write the next sentence..."></textarea><br>

<br>
<button onclick="addLine()">Add sentence</button>
<span id="errorText" style="color:red; margin-left:10px;"></span>

</div>

<div class="sidebar">

<h2>Created by: <?= $room['creator'] ?> 

</h2>
<!--js updates the users as they come and go -->
<h2>Online users: <span id="users">...</span></h2>
</div>
  </div>

<script>

var roomId = <?= json_encode($id) ?>;
var me = <?= json_encode($user) ?>;
var lastCount = 0; //tracks lines in story so updates can be detected

function updateStory() {
  fetch('api/get_story.php?id=' + roomId) //get story data
    .then(r => r.json())
    .then(data => {


      if (data.lines.length != lastCount) { //update needed
        lastCount = data.lines.length;
        var div = document.getElementById('story');
        div.innerHTML = '';


          data.lines.forEach(function(line) {
            div.innerHTML += '<div class="line"><small>' + line.author + ':</small> ' + line.text + '</div>';
          });
        
      }
      //update people online
      var usersSpan = document.getElementById('users');

  var viewers;

    if (data.viewers && data.viewers.length > 0) {
    viewers = data.viewers;
    } 
    else {
    viewers = [me];
    }

    usersSpan.textContent = viewers.join(', ');
      });
}

function addLine() {
  var text = document.getElementById('input').value.trim();

  document.getElementById('errorText').textContent = '';
  if (!text) { 
    //handle empty text
    document.getElementById('errorText').textContent = 'Write something first!'; 
    return;
   }

   //send text to server
  fetch('api/add_line.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({room_id: roomId, text: text})
  })

  .then(r => r.json())
  .then(data => {
    if (data.ok) {
      document.getElementById('input').value = '';//clear input value once sent off to server
      updateStory();
    } 
    else {
      document.getElementById('errorText').textContent = data.error;
    }
  });
}


function trackUsers() {
  fetch('api/track_users.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({room_id: roomId}) 
  });
}

updateStory();
trackUsers();
setInterval(updateStory, 2000);
setInterval(trackUsers, 5000);
</script>
</div>