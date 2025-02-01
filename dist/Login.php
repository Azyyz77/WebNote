<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Web Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-100 via-yellow-200 to-amber-200">
    <div class="container mx-auto min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl bg-white rounded-[2rem] shadow-xl overflow-hidden flex">
            <!-- Graphic Section -->
            <div class="hidden md:flex flex-1 bg-gradient-to-br from-amber-400 to-amber-600 p-12 text-white">
                <div class="max-w-md space-y-8 self-center">
                    <h1 class="text-5xl font-bold leading-tight" data-aos="fade-right">Welcome Back!</h1>
                    <p class="text-lg opacity-90 leading-relaxed" data-aos="fade-right" data-aos-delay="200">
                        Organize your thoughts, manage tasks, and boost productivity. Sign in to continue your journey with Web Note.
                    </p>
                    <div class="space-y-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <span>Secure cloud storage</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-bolt text-sm"></i>
                            </div>
                            <span>Lightning-fast interface</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="flex-1 p-12">
                <div class="max-w-md mx-auto">
                    <div class="space-y-8">
                        <div class="text-center" data-aos="fade-up">
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNmNTljMWMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1ub3RlLXRleHQiPjxwYXRoIGQ9Ik0xMyA0VjJMNCAxM3Y5aDl2LTJoLTUiLz48cGF0aCBkPSJNMTggMTdWN2g0Ii8+PHBhdGggZD0iTTE1IDE0aC01Ii8+PHBhdGggZD0iTTE1IDExaC01Ii8+PC9zdmc+" 
                                 class="h-12 w-12 mx-auto mb-4"
                                 alt="Web Note Logo">
                            <h2 class="text-3xl font-bold text-gray-800">Sign In</h2>
                            <p class="text-gray-500 mt-2">Continue to your Web Note account</p>
                        </div>

                        <!-- Session Messages -->
                        <?php if(isset($_SESSION['login_status'])) { ?>
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center space-x-3" data-aos="fade-up">
                                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-exclamation text-red-500 text-sm"></i>
                                </div>
                                <span class="text-red-600 text-sm"><?php echo $_SESSION['login_status']; ?></span>
                            </div>
                            <?php unset($_SESSION['login_status']); ?>
                        <?php } ?>

                        <?php if(isset($_SESSION['login_work'])) { ?>
                            <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center space-x-3" data-aos="fade-up">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <span class="text-green-600 text-sm"><?php echo $_SESSION['login_work']; ?></span>
                            </div>
                            <?php unset($_SESSION['login_work']); ?>
                        <?php } ?>

                        <!-- Login Form -->
                        <form method="post" action="register.php" class="space-y-6">
                            <div data-aos="fade-up" data-aos-delay="200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400"
                                        placeholder="you@example.com"
                                        required
                                    >
                                </div>
                            </div>

                            <div data-aos="fade-up" data-aos-delay="300">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="flex justify-end" data-aos="fade-up" data-aos-delay="400">
                                <a href="reset_password.php" class="text-sm text-amber-600 hover:text-amber-700 font-medium transition-colors">
                                    Forgot Password?
                                </a>
                            </div>

                            <button 
                                type="submit" 
                                name="signIn"
                                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3.5 rounded-xl transition-all transform hover:scale-[1.02] shadow-md hover:shadow-lg"
                                data-aos="fade-up"
                                data-aos-delay="500"
                            >
                                Sign In
                            </button>
                        </form>

                        <div class="text-center text-sm text-gray-600">
                            Don't have an account? 
                            <a href="sign_up.php" class="text-amber-600 hover:text-amber-700 font-medium transition-colors">
                                Create Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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