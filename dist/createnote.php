<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html, body {
            overflow-y: auto; /* Allow scrolling on the entire page */
            margin-bottom: 40px;
        }

        
    </style>

</head>

<body class="bg-yellow-50 text-gray-800 overflow-hidden">

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
        <a href="view_note.php" class="hover:text-yellow-400 text-white">View Notes</a>
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
<!-- Main Content -->
<div class="flex flex-col md:flex-row min-h-screen pt-10">
    <div class="flex-1 p-6 bg-white rounded-lg shadow-lg relative mb-8 md:mb-0" id="editor-container">
        <form id="create-note-form" action="save_note.php" method="POST">
            <input name="title" type="text" required placeholder="Titre de la note" class="w-full p-4 mb-3 text-2xl font-medium text-gray-900 border-b-2 border-yellow-300 focus:outline-none bg-yellow-100" id="note-title" />

            <textarea name="content" required placeholder="Commencez à écrire votre note ici..." class="w-full h-32 md:h-96 p-4 text-lg text-gray-700 border rounded-lg focus:outline-none resize-none bg-yellow-50" id="editor"></textarea>

            <button type="submit" id="save-btn" class="mt-4 bg-yellow-300 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg transition duration-200">
                Enregistrer
            </button>
        </form>
        <button id="fullscreen-btn" class="absolute top-4 right-4 bg-yellow-100 hover:bg-yellow-200 text-gray-700 py-2 px-4 rounded-full transition duration-200">
            <svg id="fullscreen-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path id="fullscreen-icon-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V5a2 2 0 012-2h4m8 10v4a2 2 0 01-2 2h-4m-2 4h4a2 2 0 002-2v-4m-8 4H6a2 2 0 01-2-2v-4" />
            </svg>
        </button>
    </div>


    <div class="w-full md:w-[450px] bg-yellow-50 text-gray-900 p-6 flex flex-col rounded-lg shadow-lg">
        <input type="text" placeholder="Rechercher une note" class="p-3 mb-4 rounded-lg bg-white text-gray-700 focus:outline-none" />

        <hr class="border-yellow-300 mb-4" />

        <h2 class="text-2xl font-medium mb-6 text-gray-800">My Notes</h2>
        <div class="notes-list">
        </div>
        <form method="GET" action="" id="sortForm" class="flex mb-6">
            <label for="sort" class="text-lg font-medium text-gray-700 mr-3">Sort by:</label>
            <select name="sort" id="sort" onchange="document.getElementById('sortForm').submit()" class="px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer transition-all duration-300">
                <option value="date-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-desc') ? 'selected' : ''; ?>>Date (Newest First)</option>
                <option value="date-asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-asc') ? 'selected' : ''; ?>>Date (Oldest First)</option>
                <option value="title-asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title-asc') ? 'selected' : ''; ?>>Title (A-Z)</option>
                <option value="title-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title-desc') ? 'selected' : ''; ?>>Title (Z-A)</option>
            </select>
        </form>
        <?php
        // Display notes here
        include("display_notes.php");
        ?>
    </div>
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
