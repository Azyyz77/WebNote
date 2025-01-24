<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])|| $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}


$email = $_POST['email'];

// Set default sorting option
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date-desc';  // Default is "date-desc"

// Build the SQL query based on the sort option
switch ($sort) {
    case 'date-asc':
        $order_by = 'ORDER BY created_at ASC';
        break;
    case 'date-desc':
        $order_by = 'ORDER BY created_at DESC';
        break;
    case 'title-asc':
        $order_by = 'ORDER BY title ASC';
        break;
    case 'title-desc':
        $order_by = 'ORDER BY title DESC';
        break;
    default:
        $order_by = 'ORDER BY created_at DESC';  // Default fallback
        break;
}

try {
    // Fetch notes for the logged-in user
    $query = "SELECT * FROM notes WHERE user_email = ? $order_by";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $notes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Handle deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
        $noteId = $_POST['note_id'];
        $deleteQuery = "DELETE FROM notes WHERE id = ? AND user_email = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("is", $noteId, $email);
        $deleteStmt->execute();
        $deleteStmt->close();
        header("Location: admin_viewnotes.php");
        exit();
    }

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
        $noteId = $_POST['note_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $updateQuery = "UPDATE notes SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssis", $title, $content, $noteId, $email);
        $updateStmt->execute();
        $updateStmt->close();
        header("Location: admin_viewnotes.php?id=$noteId");
        exit();
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $notes = [];
    $error = "An error occurred while fetching the notes.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Notes - WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js"></script>
    <style>
        /* Custom CSS for compact notes */
        .note-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .note-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .note-title {
            font-size: 1rem;
            font-weight: bold;
            color: #1f2937;
        }
        .note-content {
            font-size: 0.875rem;
            color: #4b5563;
            line-height: 1.25rem;
        }
        .note-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
        .note-actions button {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body class="bg-yellow-100 text-gray-800 ">
<!-- Navbar -->
    <?php
        include("admin_navbar.php");
    ?>
<!-- Main -->
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Notes</h1>
    <form method="GET" action="" id="sortForm" class="flex mb-6">
            <label for="sort" class="text-lg font-medium text-gray-700 mr-3">Sort by:</label>
            <select name="sort" id="sort" onchange="document.getElementById('sortForm').submit()" class="px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer transition-all duration-300">
                <option value="date-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-desc') ? 'selected' : ''; ?>>Date (Newest First)</option>
                <option value="date-asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-asc') ? 'selected' : ''; ?>>Date (Oldest First)</option>
                <option value="title-asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title-asc') ? 'selected' : ''; ?>>Title (A-Z)</option>
                <option value="title-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title-desc') ? 'selected' : ''; ?>>Title (Z-A)</option>
            </select>
        </form>
    <?php if (isset($error)): ?>
        <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($notes)): ?>
        <p class="text-gray-600">You have no notes yet.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($notes as $note): ?>
                <div class="note-card bg-gray-50" id="note-<?= $note['id'] ?>">
                    <form method="POST">
                        <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                        <div class="mb-2">
                            <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" class="note-title w-full border rounded p-1">
                        </div>
                        <div>
                            <textarea name="content" rows="3" class="note-content w-full border rounded p-1"><?= htmlspecialchars($note['content']) ?></textarea>
                        </div>
                        <div class="note-actions">
                            <button type="submit" name="update_note" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Update</button>
                            <button type="submit" name="delete_note" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


 
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get the 'id' parameter from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const noteId = urlParams.get('id'); // Example: '5'

        if (noteId) {
            // Find the note element with the corresponding ID
            const noteElement = document.getElementById(`note-${noteId}`);
            if (noteElement) {
                // Scroll the element into view
                noteElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Optionally highlight the note for better visibility
                noteElement.style.backgroundColor = 'gray'; // Light yellow highlight
                setTimeout(() => {
                    noteElement.style.backgroundColor = ''; // Remove highlight after 2 seconds
                }, 2000);
            }
        }
    });
</script>


</body>
</html>