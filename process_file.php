<?php
include 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['file']['tmp_name'];
        $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

        // Read the file into an array, one line per element
        $fileContents = file($uploadedFile, FILE_IGNORE_NEW_LINES);

        if (empty($fileContents)) {
            echo "<div class='error-message'>No content found in the file.</div>";
            return;
        }

        $linesWithoutSearchTerm = 0;

        echo "<div class='results-container'>";
        foreach ($fileContents as $lineNumber => $line) {
            echo "<div class='result-box'>";
            echo "<h2>Line " . ($lineNumber + 1) . ":</h2>";

            // Original String
            echo "<p><strong>Original String:</strong> " . htmlspecialchars($line) . "</p>";

            // Number of Words
            $wordCount = str_word_count($line);
            echo "<p><strong>Number of Words:</strong> $wordCount</p>";

            // Count 'a's
            $aCount = substr_count(strtolower($line), 'a');
            echo "<p><strong>Number of 'a's:</strong> $aCount</p>";

            // Count Punctuation
            $punctuationCount = 0;
            for ($i = 0; $i < strlen($line); $i++) {
                $asciiValue = ord($line[$i]);
                if (($asciiValue >= 33 && $asciiValue <= 47) || ($asciiValue >= 58 && $asciiValue <= 64) ||
                    ($asciiValue >= 91 && $asciiValue <= 96) || ($asciiValue >= 123 && $asciiValue <= 126)) {
                    $punctuationCount++;
                }
            }
            echo "<p><strong>Number of Punctuation Characters:</strong> $punctuationCount</p>";

            // Sort Words in Descending Order
            $words = explode(' ', $line);
            usort($words, 'strcasecmp');
            $sortedWords = array_reverse($words);
            $sortedLine = implode(' ', $sortedWords);
            echo "<p><strong>Words in Descending Alphabetical Order:</strong> " . htmlspecialchars($sortedLine) . "</p>";

            // Middle Third Characters
            $length = strlen($line);
            $third = floor($length / 3);
            $middleThird = substr($line, $third, $third);
            echo "<p><strong>Middle Third Characters:</strong> " . htmlspecialchars($middleThird) . "</p>";

            // Highlight Search Term
            if (!empty($searchTerm) && stripos($line, $searchTerm) !== false) {
                $highlightedLine = str_ireplace($searchTerm, "<span class='highlight'>" . htmlspecialchars($searchTerm) . "</span>", $line);
                echo "<p><strong>Line with Search Term Highlighted:</strong> " . $highlightedLine . "</p>";
            } elseif (!empty($searchTerm)) {
                $linesWithoutSearchTerm++;
            }
            echo "</div>"; // Close result-box
        }
        echo "</div>"; // Close results-container

        if (!empty($searchTerm)) {
            echo "<p><strong>Number of lines without the search term:</strong> $linesWithoutSearchTerm</p>";
        }
    } else {
        echo "<div class='error-message'>File upload error or no file uploaded. Please try again.</div>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="searchTerm">Search Term (Optional):</label>
        <input type="text" id="searchTerm" name="searchTerm">
    </div>
    <div class="form-group">
        <label for="file">Upload File:</label>
        <input type="file" id="file" name="file" accept=".txt" required>
    </div>
    <input type="submit" value="Submit">
</form>

<?php include 'includes/footer.php'; ?>
