<?php
session_start();
include 'includes/header.php';

if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = ['wins' => 0, 'losses' => 0, 'game_number' => 1];
}

$choices = ['rock', 'paper', 'scissors'];
$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerChoice = $_POST['choice'];
    $computerChoice = $choices[array_rand($choices)];

    if ($playerChoice === $computerChoice) {
        $result = "It's a tie!";
    } elseif (
        ($playerChoice === 'rock' && $computerChoice === 'scissors') ||
        ($playerChoice === 'paper' && $computerChoice === 'rock') ||
        ($playerChoice === 'scissors' && $computerChoice === 'paper')
    ) {
        $result = "You win!";
        $_SESSION['game']['wins']++;
    } else {
        $result = "You lose!";
        $_SESSION['game']['losses']++;
    }

    $_SESSION['game']['game_number']++;
    $feedback = "Game " . ($_SESSION['game']['game_number'] - 1) . ": You chose $playerChoice, computer chose $computerChoice. $result";

    if ($_SESSION['game']['losses'] >= 10) {
        header('Location: game_over.php');
        exit;
    }
}

echo "<h1>Rock, Paper, Scissors</h1>";
echo "<p>Game Number: " . $_SESSION['game']['game_number'] . "</p>";
echo "<p>Wins: " . $_SESSION['game']['wins'] . " | Losses: " . $_SESSION['game']['losses'] . "</p>";

if ($feedback) {
    echo "<p><strong>$feedback</strong></p>";
}

?>

<form method="POST">
    <button type="submit" name="choice" value="rock">Rock</button>
    <button type="submit" name="choice" value="paper">Paper</button>
    <button type="submit" name="choice" value="scissors">Scissors</button>
</form>

<?php include 'includes/footer.php'; ?>
