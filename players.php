<?php
require __DIR__ . '/includes1/config.php';

if (!isLoggedIn() || getUserRole() != 'team_manager') {
    redirect('login1.php');
}

// Get manager's team
$team = $conn->query("SELECT * FROM teams WHERE manager_id = {$_SESSION['user_id']}")->fetch_assoc();

if (!$team) {
    die("You are not assigned to any team.");
}

// Add player
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_player'])) {
    $name = $_POST['name'];
    $conn->query("INSERT INTO players (name, team_id) VALUES ('$name', {$team['id']})");
}

// Delete player
if (isset($_GET['delete_player'])) {
    $id = $_GET['delete_player'];
    $conn->query("DELETE FROM players WHERE id = $id AND team_id = {$team['id']}");
}

$players = $conn->query("SELECT * FROM players WHERE team_id = {$team['id']}");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Team</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .menu { background: #0066cc; padding: 10px; margin-bottom: 20px; }
        .menu a { color: white; text-decoration: none; margin-right: 15px; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input { padding: 5px; }
    </style>
</head>
<body>
    <div class="menu">
        <a href="index1.php">Home</a>
        <a href="my_team.php">My Team</a>
        <a href="logout1.php" style="float:right">Logout</a>
    </div>

    <div class="card">
        <h2>My Team: <?php echo $team['name']; ?></h2>
        
        <h3>Add Player</h3>
        <form method="post">
            <input type="text" name="name" placeholder="Player Name" required>
            <button type="submit" name="add_player">Add Player</button>
        </form>
        
        <h3>Player List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php while ($player = $players->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $player['id']; ?></td>
                    <td><?php echo $player['name']; ?></td>
                    <td>
                        <a href="edit_player.php?id=<?php echo $player['id']; ?>">Edit</a> | 
                        <a href="my_team.php?delete_player=<?php echo $player['id']; ?>" onclick="return confirm('Delete this player?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>