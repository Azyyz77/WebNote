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
    $deleteId = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_viewnotes.php");
    exit();
}

// Gestion de l'édition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $editTitle = $_POST['edit_title'];
    $editContent = $_POST['edit_content'];

    $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $editTitle, $editContent, $editId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_viewnotes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showEditForm(id) {
            const editForm = document.getElementById(`edit-form-${id}`);
            editForm.style.display = "block";
        }

        function hideEditForm(id) {
            const editForm = document.getElementById(`edit-form-${id}`);
            editForm.style.display = "none";
        }
    </script>
</head>
<body class="bg-yellow-100 text-gray-800">
<?php include("admin_navbar.php"); ?>

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

    <!-- Affichage des notes -->
    <?php if (isset($error)): ?>
        <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
    <?php elseif (empty($notes)): ?>
        <p class="text-gray-600">Aucune note trouvée.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($notes as $note): ?>
                <div class="bg-white border rounded shadow p-4">
                    <h2 class="font-bold mb-2"><?= htmlspecialchars($note['title']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($note['content']) ?></p>
                    <p class="text-sm text-gray-400 mt-2">Créée le : <?= htmlspecialchars($note['created_at']) ?></p>
                    <div class="mt-4 flex gap-4">
                        <!-- Bouton Modifier -->
                        <button onclick="showEditForm(<?= $note['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Modifier</button>
                        <!-- Bouton Supprimer -->
                        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($note['id']) ?>">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Supprimer</button>
                        </form>
                    </div>
                    <!-- Formulaire d'édition caché -->
                    <div id="edit-form-<?= $note['id'] ?>" class="mt-4 p-4 border rounded bg-gray-50" style="display: none;">
                        <form method="POST">
                            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($note['id']) ?>">
                            <div class="mb-2">
                                <label for="edit_title" class="font-medium">Titre :</label>
                                <input type="text" name="edit_title" value="<?= htmlspecialchars($note['title']) ?>" class="w-full px-2 py-1 border rounded">
                            </div>
                            <div class="mb-2">
                                <label for="edit_content" class="font-medium">Contenu :</label>
                                <textarea name="edit_content" rows="3" class="w-full px-2 py-1 border rounded"><?= htmlspecialchars($note['content']) ?></textarea>
                            </div>
                            <div class="flex gap-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Enregistrer</button>
                                <button type="button" onclick="hideEditForm(<?= $note['id'] ?>)" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
