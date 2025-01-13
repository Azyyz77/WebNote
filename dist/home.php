<?php
session_start();
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
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


  <!-- Navigation Bar -->
  <nav class="bg-gray-600 text-white py-4 px-8 flex justify-between items-center shadow-md">
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
        <li><a href="viewnote.html" class="hover:text-yellow-400 text-white">View Notes</a></li>
        <li><a href="about.html" class="hover:text-yellow-400 text-white ">About</a></li>
      </ul>
      <!-- Logout Icon -->
      <a href="logout.php" class="text-white hover:text-yellow-600 transition duration-200 transform hover:scale-110">
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
        <a href="viewnote.html" class="hover:text-yellow-400 text-white">View Notes</a>
        <a href="about.html" class="hover:text-yellow-400 text-white ">About</a>
        <a href="logout.php" class="text-white hover:text-yellow-600 transition duration-200 transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M16.75 21h-9.5A3.25 3.25 0 0 1 4 17.75v-11.5A3.25 3.25 0 0 1 7.25 3h9.5A3.25 3.25 0 0 1 20 6.25v2.5a.75.75 0 0 1-1.5 0v-2.5c0-.966-.784-1.75-1.75-1.75h-9.5c-.966 0-1.75.784-1.75 1.75v11.5c0 .966.784 1.75 1.75 1.75h9.5c.966 0 1.75-.784 1.75-1.75v-2.5a.75.75 0 0 1 1.5 0v2.5A3.25 3.25 0 0 1 16.75 21ZM15.28 14.53a.75.75 0 0 0 0-1.06l-2.72-2.72h8.69a.75.75 0 0 0 0-1.5h-8.69l2.72-2.72a.75.75 0 0 0-1.06-1.06l-4 4a.75.75 0 0 0 0 1.06l4 4a.75.75 0 0 0 1.06 0Z"/>
        </svg>
    </a>
    </div>
  </div>

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
