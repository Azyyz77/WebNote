<?php
session_start();
require_once("connect.php");

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
    <title>Admin Dashboard - Web Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body  class="bg-gray-50">
    <?php include("admin_navbar.php"); ?>

    <!-- Main Content -->
    <section class="min-h-[calc(100vh-80px)] flex items-center">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <!-- Animated Welcome Text -->
                <div class="mb-8 space-y-6">
                    <h1 class="text-4xl md:text-6xl font-bold text-slate-800 leading-tight">
                        Welcome Back,<br>
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Administrator
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                        Manage your platform efficiently with comprehensive tools and real-time insights.
                    </p>
                </div>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
                    <!-- Management Card -->
                    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-users-cog text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-slate-800">User Management</h3>
                        <p class="text-slate-600 mb-4">Manage user accounts and permissions</p>
                        <a href="users.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Manage Users
                        </a>
                    </div>

                    <!-- Content Card -->
                    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="text-emerald-600 mb-4">
                            <i class="fas fa-sticky-note text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-slate-800">Content Oversight</h3>
                        <p class="text-slate-600 mb-4">Monitor and manage user-generated content</p>
                        <a href="admin_viewnotes.php" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                            View Content
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-quad'
        });
    </script>
</body>
</html>