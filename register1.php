<?php
require __DIR__ . '/includes1/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    // Check if username exists
    if ($conn->query("SELECT id FROM users WHERE username = '$username'")->num_rows > 0) {
        $error = "Username already taken!";
    } else {
        $conn->query("INSERT INTO users (username, password, role) 
                     VALUES ('$username', '$password', '$role')");
        redirect('login1.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #0066cc; color: white; border: none; padding: 10px; width: 100%; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="team_manager">Team Manager</option>
                <option value="referee">Referee</option>
                <option value="spectator">Spectator</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login1.php">Login here</a></p>
</body>
</html>