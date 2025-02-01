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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        html, body {
            overflow-y: auto; /* Allow scrolling on the entire page */
            margin-bottom: 40px;
        }
    </style>
</head>

<body class="bg-yellow-100 text-gray-800 overflow-hidden">
<!-- Navbar -->
    <?php
        include("navbar.php");
    ?>
<!-- Main Content -->
<div class="flex flex-col md:flex-row min-h-screen pt-10">
    <div class="flex-1 p-6 bg-white rounded-lg shadow-lg relative mb-8 md:mb-0" id="editor-container">
        <form id="create-note-form" action="save_note.php" method="POST">
            <input name="title" type="text" required placeholder="Note Title" class="w-full p-4 mb-3 text-2xl font-medium text-gray-900 border-b-2 border-yellow-300 focus:outline-none bg-yellow-100" id="note-title" />

            <textarea name="content" required placeholder="Write your note here..." class="w-full h-32 md:h-96 p-4 text-lg text-gray-700 border rounded-lg focus:outline-none resize-none bg-yellow-50" id="editor"></textarea>

            <button type="submit" id="save-btn" 
                        class="bg-yellow-300 hover:bg-yellow-400 text-gray-800 py-3 px-6 rounded-lg font-medium transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Note
            </button>
        </form>
        <button id="fullscreen-btn" class="absolute top-4 right-4 bg-yellow-100 hover:bg-yellow-200 text-gray-700 py-2 px-4 rounded-full transition duration-200">
            <svg id="fullscreen-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path id="fullscreen-icon-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V5a2 2 0 012-2h4m8 10v4a2 2 0 01-2 2h-4m-2 4h4a2 2 0 002-2v-4m-8 4H6a2 2 0 01-2-2v-4" />
            </svg>
        </button>
    </div>


    <div class="w-full md:w-[450px] bg-yellow-100 text-gray-900 p-6 flex flex-col rounded-lg shadow-lg">
    <div class="flex items-center ">
   
    </div>
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