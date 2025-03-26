<?php
require __DIR__ . '/../../includes1/config.php';
requireRole('admin');

if (!isLoggedIn() || getUserRole() != 'admin') {
    redirect('login1.php');
}

// Add team
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_team'])) {
    $name = $_POST['name'];
    $manager_id = $_POST['manager_id'] ?: 'NULL';
    
    $conn->query("INSERT INTO teams (name, manager_id) VALUES ('$name', $manager_id)");
}

// Delete team
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM teams WHERE id = $id");
}

$teams = $conn->query("SELECT t.*, u.username as manager_name 
                      FROM teams t LEFT JOIN users u ON t.manager_id = u.id");
$managers = $conn->query("SELECT * FROM users WHERE role = 'team_manager'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Teams</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .menu { background: #0066cc; padding: 10px; margin-bottom: 20px; }
        .menu a { color: white; text-decoration: none; margin-right: 15px; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, select { padding: 5px; }
    </style>
</head>
<body>
    <div class="menu">
        <a href="index1.php">Home</a>
        <a href="teams.php">Manage Teams</a>
        <a href="matches.php">Manage Matches</a>
        <a href="logout1.php" style="float:right">Logout</a>
    </div>

    <div class="card">
        <h2>Manage Teams</h2>
        
        <h3>Add New Team</h3>
        <form method="post">
            <input type="text" name="name" placeholder="Team Name" required>
            <select name="manager_id">
                <option value="">No Manager</option>
                <?php while ($manager = $managers->fetch_assoc()): ?>
                    <option value="<?php echo $manager['id']; ?>"><?php echo $manager['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="add_team">Add Team</button>
        </form>
        
        <h3>Team List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Manager</th>
                <th>Action</th>
            </tr>
            <?php while ($team = $teams->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $team['id']; ?></td>
                    <td><?php echo $team['name']; ?></td>
                    <td><?php echo $team['manager_name'] ?? 'None'; ?></td>
                    <td>
                        <a href="edit_team.php?id=<?php echo $team['id']; ?>">Edit</a> | 
                        <a href="teams.php?delete=<?php echo $team['id']; ?>" onclick="return confirm('Delete this team?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>