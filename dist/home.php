<?php
session_start();
require_once("connect.php");



// Check if user is logged in
if (!isset($_SESSION['email'])) {
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
<body class="bg-gray-100 text-gray-900">

  <?php
    include("navbar.php");
  ?>
  <!-- Reduced Section -->
  <section class="flex flex-col lg:flex-row items-center justify-between w-full max-h-[600px] px-8 py-12 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-lg shadow-xl">
    <div class="flex flex-col justify-center space-y-5 w-full lg:w-1/2 text-left text-gray-900">
      <h1 class="text-4xl md:text-5xl font-bold leading-tight text-gray-800">Your Personal Digital Note Block</h1>
      <p class="text-lg text-gray-700">Effortlessly organize and manage your ideas and thoughts anytime, anywhere.</p>
    </div>
    <div class="w-full lg:w-1/2 mt-8 lg:mt-0">
      <img src="https://collegeinfogeek.com/wp-content/uploads/2020/01/best-note-taking-apps-for-ipad-featured.jpg" alt="Note Block" class="w-full h-full object-cover rounded-lg shadow-xl">
    </div>
  </section>

 <!-- Features Section -->
<div class="flex flex-col items-center text-center py-16 space-y-8">
  <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Features</h2>
  <p class="text-lg text-gray-600 max-w-2xl px-4 sm:px-8">
    Discover the powerful features designed to help you manage, edit, and organize your notes efficiently.
  </p>
</div>

<main class="flex flex-col items-center justify-center space-y-16">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 w-full max-w-6xl px-4 sm:px-8">
    <!-- Add Button -->
    <div class="text-center p-6 bg-white shadow-lg rounded-lg hover:shadow-2xl transition duration-300">
      <a href="createnote.php">
      <button  class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg text-lg hover:bg-gray-300 transition duration-300">
        Add
      </button>
      </a>
      <p class="mt-4 text-gray-600 text-sm">
        Create a new note to organize your thoughts and ideas.
      </p>
      <img src="https://cdn-icons-png.flaticon.com/512/1828/1828925.png" alt="New Add Icon" class="h-16 w-16 mx-auto mt-4">
    </div>

    <!-- Edit Button -->
    <div class="text-center p-6 bg-white shadow-lg rounded-lg hover:shadow-2xl transition duration-300">
      <button class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg text-lg hover:bg-gray-300 transition duration-300">
        Edit
      </button>
      <p class="mt-4 text-gray-600 text-sm">
        Update an existing note to refine your ideas.
      </p>
      <img src="https://cdn-icons-png.flaticon.com/512/1160/1160515.png" alt="Edit Icon" class="h-16 w-16 mx-auto mt-4">
    </div>

    <!-- Delete Button -->
    <div class="text-center p-6 bg-white shadow-lg rounded-lg hover:shadow-2xl transition duration-300">
      <button class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg text-lg hover:bg-gray-300 transition duration-300">
        Delete
      </button>
      <p class="mt-4 text-gray-600 text-sm">
        Remove notes that you no longer need.
      </p>
      <img src="https://cdn-icons-png.flaticon.com/512/1214/1214428.png" alt="Delete Icon" class="h-16 w-16 mx-auto mt-4">
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-6 mt-16">
  <div class="container mx-auto text-center space-y-2">
    <p class="text-sm">&copy; 2025 Web Note | All rights reserved</p>
  </div>
</footer>


</body>
</html>
