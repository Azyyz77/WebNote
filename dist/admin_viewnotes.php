<?php
session_start();
require_once("connect.php");

// Vérification si l'utilisateur est connecté et a le rôle d'admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

$email = $_SESSION['email'];

// Récupération des utilisateurs pour le dropdown
try {
    $usersQuery = "SELECT DISTINCT email FROM users";
    $usersStmt = $conn->prepare($usersQuery);
    $usersStmt->execute();
    $usersResult = $usersStmt->get_result();
    $users = $usersResult->fetch_all(MYSQLI_ASSOC);
    $usersStmt->close();
} catch (Exception $e) {
    error_log("Erreur de base de données (utilisateurs) : " . $e->getMessage());
}

// Gestion du filtre utilisateur
$selectedUser = isset($_GET['user_email']) ? $_GET['user_email'] : null;

// Gestion de l'option de tri
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date-desc';
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
        $order_by = 'ORDER BY created_at DESC';
        break;
}

try {
    // Récupération des notes en fonction de l'utilisateur sélectionné
    $query = "SELECT * FROM notes WHERE 1=1";
    $params = [];
    $types = "";

    if ($selectedUser) {
        $query .= " AND user_email = ?";
        $params[] = $selectedUser;
        $types .= "s";
    }

    $query .= " $order_by";

    $stmt = $conn->prepare($query);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $notes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log("Erreur de base de données (notes) : " . $e->getMessage());
    $notes = [];
    $error = "Une erreur s'est produite lors de la récupération des notes.";
}

// Gestion de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['note_id'];

    $deleteQuery = "DELETE FROM notes WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
    header("Location: admin_viewnotes.php");
    exit();
}

// Gestion de l'édition
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
        $noteId = $_POST['note_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        $updateQuery = "UPDATE notes SET title = ?, content = ? WHERE id = ? ";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssi", $title, $content, $noteId);
        $updateStmt->execute();
        $updateStmt->close();
        
        header("Location: admin_viewnotes.php");
        exit();
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
    <h1 class="text-2xl font-bold mb-4">Gestion des Notes</h1>
    <!-- Filtre utilisateur et tri -->
    <form method="GET" action="admin_viewnotes.php" id="filterForm" class="flex items-center mb-6 gap-4">
        <div>
            <label for="user_email" class="font-medium">Filtrer par utilisateur :</label>
            <select name="user_email" id="user_email" class="px-4 py-2 border rounded" onchange="document.getElementById('filterForm').submit()">
                <option value="">-- Tous les utilisateurs --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['email']) ?>" <?= $selectedUser === $user['email'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['email']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="sort" class="font-medium">Trier par :</label>
            <select name="sort" id="sort" class="px-4 py-2 border rounded" onchange="document.getElementById('filterForm').submit()">
                <option value="date-desc" <?= $sort === 'date-desc' ? 'selected' : '' ?>>Date (plus récent)</option>
                <option value="date-asc" <?= $sort === 'date-asc' ? 'selected' : '' ?>>Date (plus ancien)</option>
                <option value="title-asc" <?= $sort === 'title-asc' ? 'selected' : '' ?>>Titre (A-Z)</option>
                <option value="title-desc" <?= $sort === 'title-desc' ? 'selected' : '' ?>>Titre (Z-A)</option>
            </select>
        </div>
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
                            <button type="submit" name="update_id" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Update</button>
                            <button type="submit" name="delete_id" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
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



