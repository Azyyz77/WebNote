<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
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
    <tbody >
      <?php
        // Fetch users from database
        $users= $conn-> query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
        foreach ($users as $user) {
          echo "
          <form method='POST' action='admin_viewnotes.php' style='display: inline;'>
          <tr>
            <input type='hidden' name='Id' value='{$user['Id']}'>
            <td name='id' class='border border-gray-400 px-4 py-2'>{$user['Id']}</td>
            <td name='fName' class='border border-gray-400 px-4 py-2'>{$user['firstName']}</td>
            <input type='hidden' name='email' value='{$user['email']}'>
            <td name='email' class='border border-gray-400 px-4 py-2'>{$user['email']}</td>
            <input type='hidden' name='v_status' value='{$user['verify_status']}'>
            <th name='v_status' class='border border-gray-400 px-4 py-2'>{$user['verify_status']}</th>
            <input type='hidden' name='role' value='{$user['role']}'>
            <td name='role' class='border border-gray-400 px-4 py-2'>{$user['role']}</td>
            <td class='border border-gray-400 px-4 py-2'>
            <!-- Form for Edit -->
                <button type='submit' class='bg-blue-500 text-white px-2 py-1 rounded'>View</button>
            </form>
            
            <!-- Form for Delete -->
            <form method='POST'  style='display: inline;'>
                <input type='hidden' name='user_id' value='{$user['Id']}'>
                <button type='submit' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</button>
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