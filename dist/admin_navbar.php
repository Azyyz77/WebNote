  <!-- Navigation Bar -->
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
        <li><a href="users.php" class="hover:text-yellow-400 text-white ">Users</a></li>
        <li><a href="admin_viewnotes.php" class="hover:text-yellow-400 text-white">View Notes</a></li>
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
  

  <!-- Mobile Menu -->
  <div class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-20 hidden" id="mobile-menu-overlay">
    <div class="flex justify-end p-6">
        <button id="close-menu" class="text-white text-3xl">×</button>
    </div>
    <div class="flex flex-col items-center space-y-4 text-white">
        <a href="users.php" class="hover:text-yellow-400 text-white ">Users</a>
        <a href="admin_viewnotes.php" class="hover:text-yellow-400 text-white">View Notes</a>
        <a href="logout.php" class="text-white hover:text-yellow-600 transition duration-200 transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M16.75 21h-9.5A3.25 3.25 0 0 1 4 17.75v-11.5A3.25 3.25 0 0 1 7.25 3h9.5A3.25 3.25 0 0 1 20 6.25v2.5a.75.75 0 0 1-1.5 0v-2.5c0-.966-.784-1.75-1.75-1.75h-9.5c-.966 0-1.75.784-1.75 1.75v11.5c0 .966.784 1.75 1.75 1.75h9.5c.966 0 1.75-.784 1.75-1.75v-2.5a.75.75 0 0 1 1.5 0v2.5A3.25 3.25 0 0 1 16.75 21ZM15.28 14.53a.75.75 0 0 0 0-1.06l-2.72-2.72h8.69a.75.75 0 0 0 0-1.5h-8.69l2.72-2.72a.75.75 0 0 0-1.06-1.06l-4 4a.75.75 0 0 0 0 1.06l4 4a.75.75 0 0 0 1.06 0Z"/>
        </svg>
    </a>
    </div>
  </div>
</nav>
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