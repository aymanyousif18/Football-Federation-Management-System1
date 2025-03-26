<?php
require __DIR__ . '/../../includes1/config.php';

// Get filter if any
$filter = $_GET['filter'] ?? 'all';

$query = "
    SELECT m.*, t1.name as team1, t2.name as team2 
    FROM matches m
    JOIN teams t1 ON m.team1_id = t1.id
    JOIN teams t2 ON m.team2_id = t2.id
";

switch ($filter) {
    case 'live':
        $query .= " WHERE m.match_date BETWEEN NOW() - INTERVAL 3 HOUR AND NOW()";
        break;
    case 'upcoming':
        $query .= " WHERE m.match_date > NOW()";
        break;
    case 'past':
        $query .= " WHERE m.match_date <= NOW()";
        break;
}

$query .= " ORDER BY m.match_date " . ($filter === 'past' ? 'DESC' : 'ASC');

$matches = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Match Browser</title>
    <link href="../../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes1/header.php'; ?>
    
    <div class="content">
        <h2>Match Browser</h2>
        
        <div class="match-filters">
            <a href="?filter=live" class="btn">Live Now</a>
            <a href="?filter=upcoming" class="btn">Upcoming</a>
            <a href="?filter=past" class="btn">Past Matches</a>
            <a href="view.php" class="btn">All Matches</a>
        </div>
        
        <?php if ($matches->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Match</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Status</th>
                </tr>
                <?php while ($match = $matches->fetch_assoc()): 
                    $status = (strtotime($match['match_date']) > time()) ? 
                        'Upcoming' : 
                        (time() - strtotime($match['match_date']) < 10800 ? 'Live' : 'Completed');
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
            <p class="alert">No matches found.</p>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../../includes1/footer.php'; ?>
</body>
</html>