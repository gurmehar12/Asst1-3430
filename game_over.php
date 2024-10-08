<?php
session_start();
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's name and email
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate name and email
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Connect to your database
            // Update with your actual database name, username, and password
            $pdo = new PDO('mysql:host=localhost;dbname=gurmeharkaursandhu', 'gurmeharkaursandhu', 'Focus@12');

            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert the score into the high_scores table
            $stmt = $pdo->prepare("INSERT INTO high_scores (name, email, score) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $_SESSION['game']['wins']]);

            // Redirect to the high scores page
            header('Location: high_scores.php');
            exit;
        } catch (PDOException $e) {
            // Handle any potential connection issues
            $error = "Database connection failed: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a valid name and email.";
    }
}
?>

<h1>Game Over</h1>
<p>You lost 10 times. Your total wins: <?php echo $_SESSION['game']['wins']; ?></p>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="game_over.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <button type="submit">Submit Score</button>
</form>

<p><a href="game.php">Play Again</a></p>

<?php include 'includes/footer.php'; ?>
