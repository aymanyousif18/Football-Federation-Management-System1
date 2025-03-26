<?php
require __DIR__ . '/../../includes1/config.php';

// Get stats for spectator
$live_matches = $conn->query("
    SELECT COUNT(*) FROM matches
    WHERE match_date BETWEEN NOW() - INTERVAL 3 HOUR AND NOW()
")->fetch_row()[0];

$upcoming_matches = $conn->query("
    SELECT COUNT(*) FROM matches
    WHERE match_date > NOW()
")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Football Hub</title>
    <link href="../../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes1/header.php'; ?>
    
    <div class="content">
        <h2>Football Hub</h2>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Live Matches</h3>
                <p><?php echo $live_matches; ?></p>
            </div>
            <div class="stat-card">
                <h3>Upcoming</h3>
                <p><?php echo $upcoming_matches; ?></p>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="view.php" class="btn">Browse All Matches</a>
        </div>
        
        <h3>Featured Matches</h3>
        <?php
        $featured = $conn->query("
            SELECT m.*, t1.name as team1, t2.name as team2 
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.id
            JOIN teams t2 ON m.team2_id = t2.id
            WHERE m.match_date > NOW()
            ORDER BY m.match_date ASC
            LIMIT 3
        ");
        
        if ($featured->num_rows > 0): ?>
            <div class="match-cards">
                <?php while ($match = $featured->fetch_assoc()): ?>
                    <div class="match-card">
                        <h4><?php echo $match['team1']; ?> vs <?php echo $match['team2']; ?></h4>
                        <p><?php echo date('D, M j, Y H:i', strtotime($match['match_date'])); ?></p>
                        <p><?php echo $match['venue']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="alert">No featured matches available.</p>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../../includes1/footer.php'; ?>
</body>
</html>