<?php
session_start();
require_once("connect.php");

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get the search query
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Check if search query is empty
if (empty($search_query)) {
    echo "<h1>Please provide a search query.</h1>";
    exit();
}

// Prepare SQL query to fetch matching notes
$email = $_SESSION['email']; // Assuming you're linking notes to the logged-in user
$sql = "SELECT * FROM notes WHERE user_email = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$like_query = "%" . $search_query . "%";
$stmt->bind_param("sss", $email, $like_query, $like_query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Notes</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50 text-gray-800">

    <div class="container mx-auto my-8 px-4">
        <!-- Search section -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-black-600 mb-2">Search Results</h1>
            <p class="text-xl text-yellow-800">Showing results for: <span class="italic text-yellow-700"><?= htmlspecialchars($search_query) ?></span></p>
        </div>

        <!-- Search Bar Section -->
        <div class="flex justify-center mb-6">
            <form action="search_notes.php" method="get" class="flex space-x-4">
                <input type="text" name="query" value="<?= htmlspecialchars($search_query) ?>" 
                       class="px-4 py-2 border-2 border-gray-300 rounded-md text-black focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                       placeholder="Search Notes..." required>
                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-500 transition duration-300">
                    Search
                </button>
            </form>
        </div>

        <!-- Results section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Check if any notes are found
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300'>";
                    echo "<h3 class='text-xl font-bold text-yellow-700 mb-2'>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p class='text-gray-600 mb-4'>" . htmlspecialchars(substr($row['content'], 0, 100)) . "...</p>";
                    echo "<a href='view_note.php?id=" . $row['id'] . "' class='text-yellow-500 hover:text-yellow-700 mt-2 block text-center'>Read More</a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='col-span-full text-center text-yellow-600'><h3>No notes found for '$search_query'.</h3></div>";
            }
            ?>

        </div>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
