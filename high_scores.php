<?php
include 'includes/header.php';

// Connect to your database
// Replace with your actual database connection details
$pdo = new PDO('mysql:host=localhost;dbname=gurmeharkaursandhu', 'gurmeharkaursandhu', 'Focus@12');

// Fetch the top 20 high scores from the high_scores table
$stmt = $pdo->query("SELECT name, score FROM high_scores ORDER BY score DESC LIMIT 20");
$highScores = $stmt->fetchAll();
?>

<h1>High Scores</h1>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Score</th>
    </tr>
    <?php foreach ($highScores as $score): ?>
        <tr>
            <td><?php echo htmlspecialchars($score['name']); ?></td>
            <td><?php echo htmlspecialchars($score['score']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="game.php">Play Again</a></p>

<?php include 'includes/footer.php'; ?>
