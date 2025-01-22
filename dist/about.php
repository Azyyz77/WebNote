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
    <title>About WebNote</title>
    <script src="https://cdn.tailwindcss.com"></script>
   <style>
        html, body {
            overflow-y: auto; /* Allow scrolling on the entire page */
        }
    </style>
</head>

<body class="bg-yellow-100 text-gray-800 overflow-hidden">
      <!-- Navbar -->
    <?php
        include("navbar.php");
    ?>
    <!-- About Section -->
    <section class="py-16 px-4 sm:px-8 lg:px-16  text-gray-900">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold mb-6 fadeIn">About WebNote</h1>
            <p class="text-xl mb-12 leading-relaxed text-gray-700 fadeIn">WebNote is a powerful online note-taking platform designed to help you stay organized and productive. Whether you are a student, a professional, or someone who simply likes to stay on top of their ideas, WebNote is your ideal tool.</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Mission Block -->
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg transition-shadow hover:shadow-2xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Mission</h2>
                    <p class="text-lg text-gray-600">At WebNote, our mission is to provide a simple, intuitive, and powerful note-taking experience. We strive to make it easier for individuals and teams to stay organized, capture ideas, and collaborate more efficiently.</p>
                </div>

                <!-- Vision Block -->
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg transition-shadow hover:shadow-2xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Vision</h2>
                    <p class="text-lg text-gray-600">We envision a world where everyone has access to a reliable, easy-to-use tool that helps them organize their thoughts, tasks, and ideas, no matter where they are. Our goal is to become the go-to platform for note-taking and personal productivity.</p>
                </div>

                <!-- Values Block -->
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg transition-shadow hover:shadow-2xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Values</h2>
                    <ul class="list-inside list-disc text-lg text-gray-600 space-y-2">
                        <li><strong>Innovation:</strong> We constantly evolve to meet the needs of our users.</li>
                        <li><strong>Simplicity:</strong> We believe in keeping things simple and easy to use.</li>
                        <li><strong>Collaboration:</strong> We encourage teamwork and knowledge sharing.</li>
                        <li><strong>Security:</strong> We prioritize the safety and privacy of your data.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

  
<!-- Footer -->
<footer class="bg-gray-800 text-white py-6 mt-16">
    <div class="container mx-auto text-center space-y-2">
      <p class="text-sm">&copy; 2025 Web Note | All rights reserved</p>
    </div>
  </footer>

</body>

</html>