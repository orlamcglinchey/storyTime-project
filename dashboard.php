<?php
session_start();
if (!isset($_SESSION['user'])) { 
  header('Location: index.php'); 
  exit; 
  }
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titleStory = $_POST['title'];
    if ($titleStory) { //exists
        $id = uniqid();
        $room = ['id' => $id, 'title' => $titleStory, 'creator' => $user, 'lines' => []]; //create new room
        file_put_contents("data/rooms/$id.json", json_encode($room)); //store it

        header("Location: room.php?id=$id"); 
        exit;
    }
}

$rooms = [];
foreach (glob('data/rooms/*.json') as $roomFile) {
    $rooms[] = json_decode(file_get_contents($roomFile), true);
}
?>
<link rel="stylesheet" href="style.css">
<nav>
  <a href="dashboard.php">StoryTime</a>
  <a href="logout.php">Logout</a>
</nav>
<div class="page">
<h2>Create a Room</h2>
<form method="POST">
  Name your story: <input type="text" name="title">
  <button type="submit">Create</button>
</form>

<h2>All Rooms</h2>
  <div class="room-list">
<?php foreach ($rooms as $r): ?>
  <div class="room">
    <a id = "rooms" href="room.php?id=<?= $r['id'] ?>"><?= $r['title'] ?></a>
    — by <?= $r['creator']?>
    — <?= count($r['lines']) ?> lines
  </div>

<?php endforeach; ?>
</div>
