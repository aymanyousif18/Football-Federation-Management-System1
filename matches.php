<?php
require __DIR__ . '/../../includes1/config.php';
requireRole('referee');

// Get all referee's matches
$matches = $conn->query("
    SELECT m.*, t1.name as team1, t2.name as team2 
    FROM matches m
    JOIN teams t1 ON m.team1_id = t1.id
    JOIN teams t2 ON m.team2_id = t2.id
    ORDER BY m.match_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Matches</title>
    <link href="../../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes1/header.php'; ?>
    
    <div class="content">
        <h2>All My Matches</h2>
        
        <div class="match-filters">
            <a href="?filter=upcoming" class="btn">Upcoming</a>
            <a href="?filter=past" class="btn">Completed</a>
            <a href="matches.php" class="btn">All</a>
        </div>
        
        <?php if ($matches->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Match</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Status</th>
                </tr>
                <?php while ($match = $matches->fetch_assoc()): 
                    $status = (strtotime($match['match_date']) > time()) ? 'Upcoming' : 'Completed';
                ?>
                    <tr>
                        <td><?php echo $match['team1']; ?> vs <?php echo $match['team2']; ?></td>
                        <td><?php echo date('M j, Y H:i', strtotime($match['match_date'])); ?></td>
                        <td><?php echo $match['venue']; ?></td>
                        <td><?php echo $status; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="alert">No matches assigned to you.</p>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../../includes1/footer.php'; ?>
</body>
</html>