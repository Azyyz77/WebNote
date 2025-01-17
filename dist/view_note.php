<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
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
        header("Location: view_note.php");
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
        header("Location: view_note.php");
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
<body class="bg-gray-100 text-gray-800">
    <!-- Navbar -->
  <nav class="bg-gray-800 text-white py-4 px-6 shadow-md flex justify-between items-center ">
        <!-- Logo or Title -->
        <div class="flex items-center space-x-4">
        <img src="https://static.vecteezy.com/system/resources/previews/010/760/390/large_2x/wn-logo-w-n-design-white-wn-letter-wn-letter-logo-design-initial-letter-wn-linked-circle-uppercase-monogram-logo-vector.jpg" 
            alt="Web Note Logo" 
            class="w-12 h-12 rounded-full">
        <h1 class="text-xl font-bold">Web Note</h1>
        </div>

        

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
        <ul class="flex space-x-6">
        <li><a href="home.php" class="hover:text-yellow-400 text-white ">Home</a></li>
        <li><a href="createnote.php" class="hover:text-yellow-400 text-white">Create Notes</a></li>
        <li><a href="view_note.php" class="hover:text-yellow-400 text-white">View Notes</a></li>
        <li><a href="about.html" class="hover:text-yellow-400 text-white ">About</a></li>
        </ul>
    
        
        <!-- Logout Icon (Heroicons Door Icon) -->
        <a href="logout.php" class="text-white hover:text-yellow-600 transition duration-200 transform hover:scale-110">
        <!-- Better Logout Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16.75 21h-9.5A3.25 3.25 0 0 1 4 17.75v-11.5A3.25 3.25 0 0 1 7.25 3h9.5A3.25 3.25 0 0 1 20 6.25v2.5a.75.75 0 0 1-1.5 0v-2.5c0-.966-.784-1.75-1.75-1.75h-9.5c-.966 0-1.75.784-1.75 1.75v11.5c0 .966.784 1.75 1.75 1.75h9.5c.966 0 1.75-.784 1.75-1.75v-2.5a.75.75 0 0 1 1.5 0v2.5A3.25 3.25 0 0 1 16.75 21ZM15.28 14.53a.75.75 0 0 0 0-1.06l-2.72-2.72h8.69a.75.75 0 0 0 0-1.5h-8.69l2.72-2.72a.75.75 0 0 0-1.06-1.06l-4 4a.75.75 0 0 0 0 1.06l4 4a.75.75 0 0 0 1.06 0Z"/>
            </svg>
        </a>


        </div>
        <!-- Mobile Hamburger Menu Button -->
        <button class="md:hidden text-gray-1000" id="hamburger-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

<!-- Mobile Menu -->
<div class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-20 hidden" id="mobile-menu-overlay">
    <div class="flex justify-end p-6">
        <button id="close-menu" class="text-white text-3xl">×</button>
    </div>
    <div class="flex flex-col items-center space-y-4 text-white">
        <a href="home.php" class="hover:text-yellow-400 text-white ">Home</a>
        <a href="createnote.php" class="hover:text-yellow-400 text-white">Create Notes</a>
        <a href="view.html" class="hover:text-yellow-400 text-white">View Notes</a>
        <a href="about.html" class="hover:text-yellow-400 text-white ">About</a>
        <a href="logout.php" class="text-white hover:text-yellow-600 transition duration-200 transform hover:scale-110">
    <!-- Better Logout Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M16.75 21h-9.5A3.25 3.25 0 0 1 4 17.75v-11.5A3.25 3.25 0 0 1 7.25 3h9.5A3.25 3.25 0 0 1 20 6.25v2.5a.75.75 0 0 1-1.5 0v-2.5c0-.966-.784-1.75-1.75-1.75h-9.5c-.966 0-1.75.784-1.75 1.75v11.5c0 .966.784 1.75 1.75 1.75h9.5c.966 0 1.75-.784 1.75-1.75v-2.5a.75.75 0 0 1 1.5 0v2.5A3.25 3.25 0 0 1 16.75 21ZM15.28 14.53a.75.75 0 0 0 0-1.06l-2.72-2.72h8.69a.75.75 0 0 0 0-1.5h-8.69l2.72-2.72a.75.75 0 0 0-1.06-1.06l-4 4a.75.75 0 0 0 0 1.06l4 4a.75.75 0 0 0 1.06 0Z"/>
        </svg>
    </a>
 
    </div>
</div>
</nav>
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
                <div class="note-card">
                    <form method="POST">
                        <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                        <div>
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
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    const closeMenuBtn = document.getElementById('close-menu');

    // Open the mobile menu when hamburger button is clicked
    hamburgerBtn.addEventListener('click', () => {
        mobileMenuOverlay.classList.remove('hidden');
    });

    // Close the mobile menu when the close button is clicked
    closeMenuBtn.addEventListener('click', () => {
        mobileMenuOverlay.classList.add('hidden');
    });

    // Close the mobile menu if the overlay is clicked
    mobileMenuOverlay.addEventListener('click', (e) => {
        if (e.target === mobileMenuOverlay) {
            mobileMenuOverlay.classList.add('hidden');
        }
    });
</script>
</body>
</html>