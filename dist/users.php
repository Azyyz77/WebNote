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

// Handle role change and verify_status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role_user_id'])) {
    $userId = intval($_POST['role_user_id']);
    $newRole = $_POST['new_role'];

    $updateQuery = "UPDATE users SET role = ? WHERE Id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newRole, $userId);
    if ($stmt->execute()) {
    } else {
        echo "<script>alert('Failed to update role and Verify Status');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Web Note</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 text-gray-800">
<?php
    include("admin_navbar.php");
?>
<div class="container mx-auto mt-6">
  <h1 class="text-2xl font-bold mb-4">Users</h1>
  <table border="1" class="table-auto w-full border-collapse border border-gray-400">
    
  <thead>
      <tr class="bg-gray-200">
        <th class="border border-gray-400 px-4 py-2">ID</th>
        <th class="border border-gray-400 px-4 py-2">Name</th>
        <th class="border border-gray-400 px-4 py-2">Email</th>
        <th class="border border-gray-400 px-4 py-2">Verify Status</th> 
        <th class="border border-gray-400 px-4 py-2">Role</th>
        <th class="border border-gray-400 px-4 py-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Fetch users from database
        $users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
        foreach ($users as $user) {
          echo "
          <tr>
            <td class='border border-gray-400 px-4 py-2'>{$user['Id']}</td>
            <td class='border border-gray-400 px-4 py-2'>{$user['firstName']}</td>
            <td class='border border-gray-400 px-4 py-2'>{$user['email']}</td>
            <td class='border border-gray-400 px-4 py-2'>{$user['verify_status']}</td>
            <td class='border border-gray-400 px-4 py-2'>{$user['role']}</td>
            <td class='border border-gray-400 px-4 py-2'>
            
              <!-- Form for Delete -->
              <form method='POST' style='display: inline;'>
                <input type='hidden' name='user_id' value='{$user['Id']}'>
                <button type='submit' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</button>
              </form>
              <!-- Button for Change Role -->
              <form method='POST' style='display: inline;'>
                <input type='hidden' name='role_user_id' value='{$user['Id']}'>
                <input type='hidden' name='new_role' value='" . ($user['role'] === 'admin' ? 'user' : 'admin') . "'>
                <button type='submit' class='" . ($user['role'] === 'admin' ? 'bg-yellow-500' : 'bg-green-500') . " text-white px-2 py-1 rounded'>
                  " . ($user['role'] === 'admin' ? 'Make User' : 'Make Admin') . "
                </button>
              </form>
            </td>
          </tr>";
        }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
