<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $deleteQuery = "DELETE FROM users WHERE Id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
    } else {
        echo "<script>alert('Failed to delete user');</script>";
    }
    $stmt->close();
}

// Handle role change 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role_user_id'])) {
    $userId = intval($_POST['role_user_id']);
    $newRole = $_POST['new_role'];

    $updateQuery = "UPDATE users SET role = ? WHERE Id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newRole, $userId);
    if ($stmt->execute()) {
    } else {
        echo "<script>alert('Failed to update role');</script>";
    }
    $stmt->close();
}
// Handle verify/unverify request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_user_id'])) {
  $userId = intval($_POST['verify_user_id']);
  $newStatus = intval($_POST['new_verify_status']);
  
  $updateQuery = "UPDATE users SET verify_status = ? WHERE Id = ?";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("ii", $newStatus, $userId);
  
  if ($stmt->execute()) {
      // Success - no need for alert
  } else {
      echo "<script>alert('Failed to update verification status');</script>";
  }
  $stmt->close();
}

// Fetch users
$users = [];
$result = $conn->query("SELECT * FROM users");

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "<script>alert('Error fetching users: " . $conn->error . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Web Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<?php include("admin_navbar.php"); ?>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">User Management</h1>
        <p class="text-gray-600">Manage user accounts and permissions</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= $user['firstName'] ?></div>
                                    <div class="text-sm text-gray-500"><?= $user['email'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="verify_user_id" value="<?= $user['Id'] ?>">
                                    <input type="hidden" name="new_verify_status" value="<?= $user['verify_status'] ? 0 : 1 ?>">
                                    <button type="submit" class="flex items-center space-x-2">
                                        <span class="px-3 py-1 text-sm rounded-full <?= $user['verify_status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $user['verify_status'] ? 'Verified' : 'Unverified' ?>
                                        </span>
                                        <span class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-<?= $user['verify_status'] ? 'times' : 'check' ?>"></i>
                                        </span>
                                    </button>
                                </form>
                            </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <?= ucfirst($user['role']) ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium space-x-4">
                            <form method="POST" class="inline">
                                <input type="hidden" name="role_user_id" value="<?= $user['Id'] ?>">
                                <input type="hidden" name="new_role" value="<?= $user['role'] === 'admin' ? 'user' : 'admin' ?>">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-user-shield mr-1"></i>
                                    <span class="hidden md:inline"><?= $user['role'] === 'admin' ? 'Demote' : 'Promote' ?></span>
                                </button>
                            </form>
                            <form method="POST" class="inline">
                                <input type="hidden" name="user_id" value="<?= $user['Id'] ?>">
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash mr-1"></i>
                                    <span class="hidden md:inline">Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>