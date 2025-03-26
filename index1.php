<?php
require __DIR__ . '/includes1/config.php';

if (!isLoggedIn()) {
    redirect('login1.php');
}

$role = getUserRole();

// Redirect based on user role
switch ($role) {
    case 'admin':
        redirect('admin1/dashboard.php');
        break;
    case 'team_manager':
        redirect('manager1/dashboard.php');
        break;
    case 'referee':
        redirect('referee1/dashboard.php');
        break;
    case 'spectator':
        redirect('spectator1/dashboard.php');
        break;
    default:
        // Logout if invalid role
        session_destroy();
        redirect('login1.php');
        break;
}
?>