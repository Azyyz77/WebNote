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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-yellow-50 text-gray-800 overflow-hidden">

<!-- Navbar -->
<nav class="flex justify-between items-center bg-yellow-300 p-4 rounded-lg shadow-md fixed w-full top-0 left-0 z-10">
    <!-- Logo or Title -->
    <h1 class="text-3xl font-bold text-gray-800 tracking-wide">WebNote</h1>

   <!-- Desktop Menu -->
<div class="hidden md:flex items-center space-x-6">
    <a href="#home" class="text-gray-800 hover:text-yellow-600 transition duration-200">Home</a>
    <a href="#features" class="text-gray-800 hover:text-yellow-600 transition duration-200">Features</a>
    <a href="#about" class="text-gray-800 hover:text-yellow-600 transition duration-200">About</a>
    
    <!-- Logout Icon (Heroicons Door Icon) -->
    <a href="logout.php" class="text-gray-800 hover:text-yellow-600 transition duration-200">
        <!-- Using Heroicons Door Icon for logout -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8m4-4v8a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H12a2 2 0 01-2-2V8h4z" />
        </svg>
    </a>
</div>



    <!-- Mobile Hamburger Menu Button -->
    <button class="md:hidden text-gray-800" id="hamburger-btn">
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
        <a href="#home" class="text-xl hover:text-yellow-100">Home</a>
        <a href="#features" class="text-xl hover:text-yellow-100">Features</a>
        <a href="#about" class="text-xl hover:text-yellow-100">About</a>
        <a href="logout.php" class="text-xl hover:text-yellow-100">Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="flex flex-col md:flex-row h-screen pt-16">
    <div class="flex-1 p-8 bg-white rounded-lg shadow-lg relative mb-8 md:mb-0" id="editor-container">
    <form action="save_note.php" method="POST">
        <input name="title" type="text" placeholder="Titre de la note" class="w-full p-4 mb-4 text-2xl font-medium text-gray-900 border-b-2 border-yellow-300 focus:outline-none bg-yellow-100" id="note-title" />

        <textarea name="content" placeholder="Commencez à écrire votre note ici..." class="w-full h-3/4 p-4 text-lg text-gray-700 border rounded-lg focus:outline-none resize-none mt-8 bg-yellow-50" id="editor"></textarea>

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

    <div class="w-full md:w-72 bg-yellow-50 text-gray-900 p-6 flex flex-col rounded-lg shadow-lg">
        <input type="text" placeholder="Rechercher une note" class="p-3 mb-4 rounded-lg bg-white text-gray-700 focus:outline-none" />

        <hr class="border-yellow-300 mb-4" />

        <h2 class="text-2xl font-medium mb-6 text-gray-800">My Notes</h2>
        <div class="notes-list">
    </div>

        <label for="sort-options" class="text-gray-600 mb-2">Trier par:</label>
        <select id="sort-options" class="p-3 bg-white text-gray-700 rounded-lg focus:outline-none">
            <option value="date-desc">Date : Anciennes → Nouvelles</option>
            <option value="date-asc">Date : Nouvelles → Anciennes</option>
            <option value="alpha-asc">Alphabetique : A-Z</option>
            <option value="alpha-desc">Alphabetique : Z-A</option>
        </select>
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
    
    // Toggle fullscreen mode when the fullscreen button is clicked
    function sortNotes(criteria) {
            let sortedNotes;
            switch (criteria) {
                case 'date-desc':
                    sortedNotes = [...notes].sort((a, b) => b.date - a.date);
                    break;
                case 'date-asc':
                    sortedNotes = [...notes].sort((a, b) => a.date - b.date);
                    break;
                case 'alpha-asc':
                    sortedNotes = [...notes].sort((a, b) => a.title.localeCompare(b.title));
                    break;
                case 'alpha-desc':
                    sortedNotes = [...notes].sort((a, b) => b.title.localeCompare(a.title));
                    break;
                default:
                    sortedNotes = notes;
                    break;
            }
            displayNotes(sortedNotes);
        }

        const sortOptions = document.getElementById('sort-options');
        sortOptions.addEventListener('change', (e) => {
            sortNotes(e.target.value);
        });

        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const editorContainer = document.getElementById('editor-container');
        const fullscreenIconPath = document.getElementById('fullscreen-icon-path');

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                editorContainer.requestFullscreen?.() || editorContainer.mozRequestFullScreen?.() || 
                editorContainer.webkitRequestFullscreen?.() || editorContainer.msRequestFullscreen?.();
                fullscreenIconPath.setAttribute('d', 'M5 3v4a2 2 0 002 2h4m8 10v4a2 2 0 01-2 2h-4m-2 4h4a2 2 0 002-2v-4m-8 4H6a2 2 0 01-2-2v-4');
            } else {
                document.exitFullscreen?.() || document.mozCancelFullScreen?.() || 
                document.webkitExitFullscreen?.() || document.msExitFullscreen?.();
                fullscreenIconPath.setAttribute('d', 'M6 9V5a2 2 0 012-2h4m8 10v4a2 2 0 01-2 2h-4m-2 4h4a2 2 0 002-2v-4');
            }
        }

        fullscreenBtn.addEventListener('click', toggleFullscreen);
</script>

</body>
</html>
