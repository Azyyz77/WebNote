<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Web Note</title>
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
                    <h1 class="text-5xl font-bold leading-tight" data-aos="fade-right">Welcome to Web Note</h1>
                    <p class="text-lg opacity-90 leading-relaxed" data-aos="fade-right" data-aos-delay="200">
                        Keep track of your thoughts, organize your tasks, and capture your ideas all in one place.
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
                            <span>Easy-to-use platform</span>
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
                            <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                            <p class="text-gray-500 mt-2">Start your productivity journey with us</p>
                        </div>

                        <!-- Session Messages -->
                        <?php if(isset($_SESSION['signup_status'])) { ?>
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center space-x-3" data-aos="fade-up">
                                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-exclamation text-red-500 text-sm"></i>
                                </div>
                                <span class="text-red-600 text-sm"><?php echo $_SESSION['signup_status']; ?></span>
                            </div>
                            <?php unset($_SESSION['signup_status']); ?>
                        <?php } ?>

                        <?php if(isset($_SESSION['signup_work'])) { ?>
                            <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center space-x-3" data-aos="fade-up">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <span class="text-green-600 text-sm"><?php echo $_SESSION['signup_work']; ?></span>
                            </div>
                            <?php unset($_SESSION['signup_work']); ?>
                        <?php } ?>

                        <!-- Signup Form -->
                        <form method="post" action="register.php" class="space-y-6" onsubmit="return validatePassword()">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div data-aos="fade-up" data-aos-delay="200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            name="fName" 
                                            id="fName"
                                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400"
                                            placeholder="John"
                                            required
                                        >
                                    </div>
                                </div>

                                <div data-aos="fade-up" data-aos-delay="250">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            name="lName" 
                                            id="lName"
                                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400"
                                            placeholder="Doe"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <div data-aos="fade-up" data-aos-delay="300">
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

                            <div data-aos="fade-up" data-aos-delay="350">
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
                                <div id="passwordError" class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm hidden">
                                    Password must be at least 8 characters, 1 Uppercase Letter, 1 Number and 1 Special character.
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                name="signUp"
                                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3.5 rounded-xl transition-all transform hover:scale-[1.02] shadow-md hover:shadow-lg"
                                data-aos="fade-up"
                                data-aos-delay="400"
                            >
                                Create Account
                            </button>
                        </form>
                        <div class="text-center text-sm text-gray-600">
                            Already have an account? 
                            <a href="Login.php" class="text-amber-600 hover:text-amber-700 font-medium transition-colors">
                                Sign In
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

        function validatePassword() {
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            const strongPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[?!@#$%^&.*])[A-Za-z\d?!@#$%.^&*]{8,}$/;

            if (!strongPassword.test(password)) {
                passwordError.classList.remove('hidden');
                return false;
            }
            passwordError.classList.add('hidden');
            return true;
        }
    </script>
</body>
</html>