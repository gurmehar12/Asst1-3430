<?php
session_start();
include 'includes/header.php';

// Reset the session on quiz start to avoid old data
if (!isset($_GET['q']) || $_GET['q'] == 1) {
    // Reset the session for a new quiz
    $_SESSION['quiz'] = [
        'score' => 0,
        'feedback' => []
    ];
}

$questionNumber = isset($_GET['q']) ? intval($_GET['q']) : 1;

if ($questionNumber == 6) {
    echo "<h1>Quiz Results</h1>";
    foreach ($_SESSION['quiz']['feedback'] as $feedback) {
        echo "<p>$feedback</p>";
    }
    echo "<p><strong>Your score: " . $_SESSION['quiz']['score'] . " / 5</strong></p>";
    session_destroy();
} else {
    $questions = [
        1 => ['question' => "Who is Harry Potter's godfather?", 'options' => ['Sirius Black', 'Severus Snape', 'Remus Lupin'], 'answer' => 'Sirius Black'],
        2 => ['question' => "What house is Harry Potter sorted into?", 'options' => ['Gryffindor', 'Slytherin', 'Ravenclaw'], 'answer' => 'Gryffindor'],
        3 => ['question' => "What is the name of Harry Potter's owl?", 'options' => ['Hedwig', 'Errol', 'Pigwidgeon'], 'answer' => 'Hedwig'],
        4 => ['question' => "What position does Harry play on the Quidditch team?", 'options' => ['Seeker', 'Beater', 'Keeper'], 'answer' => 'Seeker'],
        5 => ['question' => "Who is the headmaster of Hogwarts for most of the series?", 'options' => ['Albus Dumbledore', 'Minerva McGonagall', 'Severus Snape'], 'answer' => 'Albus Dumbledore']
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $answer = $_POST['answer'];
        if ($answer == $questions[$questionNumber]['answer']) {
            $_SESSION['quiz']['score']++;
            $_SESSION['quiz']['feedback'][] = "Question $questionNumber: Correct!";
        } else {
            $_SESSION['quiz']['feedback'][] = "Question $questionNumber: Incorrect. The correct answer was " . $questions[$questionNumber]['answer'] . ".";
        }
        header("Location: quiz.php?q=" . ($questionNumber + 1));
        exit;
    }

    echo "<h1>Question $questionNumber</h1>";
    echo "<p>" . $questions[$questionNumber]['question'] . "</p>";

    echo "<form method='POST'>";
    foreach ($questions[$questionNumber]['options'] as $option) {
        echo "<label><input type='radio' name='answer' value='$option' required> $option</label><br>";
    }
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
}

include 'includes/footer.php';
?>
