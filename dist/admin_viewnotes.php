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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Management - WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <?php include("admin_navbar.php"); ?>

    <div class="container mx-auto p-4 lg:p-8 max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Note Management</h1>
            <p class="text-gray-600">Manage and monitor all user notes</p>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="admin_viewnotes.php" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by user</label>
                    <select name="user_email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">All users</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['email']) ?>" <?= $selectedUser === $user['email'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user['email']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                    <select name="sort" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="date-desc" <?= $sort === 'date-desc' ? 'selected' : '' ?>>Date (recent → old)</option>
                        <option value="date-asc" <?= $sort === 'date-asc' ? 'selected' : '' ?>>Date (old → recent)</option>
                        <option value="title-asc" <?= $sort === 'title-asc' ? 'selected' : '' ?>>Title (A → Z)</option>
                        <option value="title-desc" <?= $sort === 'title-desc' ? 'selected' : '' ?>>Title (Z → A)</option>
                    </select>
                </div>

                <div class="self-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Apply
                    </button>
                </div>
            </form>
        </div>

        <!-- Notes Grid -->
        <?php if (empty($notes)): ?>
            <div class="bg-white rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                <p>No notes found</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($notes as $note): ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow" id="note-<?= $note['id'] ?>">
                    <form method="POST" class="p-6">
                        <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                        
                        <!-- Note Header -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">
                                <?= date('m/d/Y H:i', strtotime($note['created_at'])) ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                <?= htmlspecialchars($note['user_email']) ?>
                            </span>
                        </div>

                        <!-- Note Content -->
                        <div class="space-y-4">
                            <div>
                                <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" 
                                    class="w-full px-3 py-2 border-b-2 border-transparent focus:border-indigo-500 focus:outline-none text-lg font-medium">
                            </div>
                            
                            <div>
                                <textarea name="content" rows="4"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"><?= htmlspecialchars($note['content']) ?></textarea>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="submit" name="update_id" 
                                class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-save mr-2"></i>Save
                            </button>
                            
                            <button type="submit" name="delete_id" 
                                class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                onclick="return confirm('Are you sure you want to delete this note?')">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Auto-resize textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
        
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
    });

    // Note highlight logic
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const noteId = urlParams.get('id');

        if (noteId) {
            const noteElement = document.getElementById(`note-${noteId}`);
            if (noteElement) {
                noteElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                noteElement.classList.add('ring-2', 'ring-indigo-500');
                setTimeout(() => {
                    noteElement.classList.remove('ring-2', 'ring-indigo-500');
                }, 3000);
            }
        }
    });
    </script>
</body>
</html>