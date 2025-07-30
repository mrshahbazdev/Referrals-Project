<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CodeShack')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons CDN -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #111827; }
        .text-gradient { background: linear-gradient(to right, #fde047, #facc15); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .slider-track { transition: transform 0.5s ease-in-out; }
        .marquee-container { overflow: hidden; position: relative; width: 100%; }
        .marquee-content { display: inline-block; white-space: nowrap; animation: marquee-scroll 20s linear infinite; }
        .marquee-content span { margin-right: 50px; }
        @keyframes marquee-scroll { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
    </style>
    @stack('styles')
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- App Container -->
    <div class="w-full max-w-sm mx-auto bg-[#10111A] text-white shadow-2xl rounded-3xl overflow-hidden relative">
        <div class="h-[800px] overflow-y-auto">

            <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-[#10111A] sticky top-0 z-20">
                <i class="ph ph-circles-four text-2xl text-yellow-400"></i>
                <h1 class="text-lg font-bold">CODESHACK</h1>
                <div class="flex items-center space-x-4">
                    <i class="ph ph-bell text-2xl"></i>
                    <i id="menu-button" class="ph ph-list text-2xl cursor-pointer"></i>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 space-y-6">
                @yield('content')
            </main>
        </div>

        <!-- Bottom Navigation -->
        <nav class="grid grid-cols-5 gap-2 p-3 bg-[#1E1F2B] border-t border-gray-700">
            <a href="{{ route('home') }}" class="flex flex-col items-center text-center text-blue-400"><i class="ph ph-house text-2xl"></i><span class="text-xs">Home</span></a>
            <a href="#" class="flex flex-col items-center text-center text-gray-400 hover:text-white"><i class="ph ph-wallet text-2xl"></i><span class="text-xs">Deposit</span></a>
            <a href="#" class="flex flex-col items-center text-center text-gray-400 hover:text-white"><i class="ph ph-radioactive text-2xl"></i><span class="text-xs">Mining</span></a>
            <a href="#" class="flex flex-col items-center text-center text-gray-400 hover:text-white"><i class="ph ph-arrow-circle-up-right text-2xl"></i><span class="text-xs">Withdraw</span></a>
            @auth
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-center text-gray-400 hover:text-white"><i class="ph ph-user-circle text-2xl"></i><span class="text-xs">My Account</span></a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center text-center text-gray-400 hover:text-white"><i class="ph ph-sign-in text-2xl"></i><span class="text-xs">Login</span></a>
            @endauth
        </nav>
    </div>

    <!-- Side Menu (Drawer) -->
    <div id="side-menu-container" class="fixed inset-0 z-30 hidden">
        <div id="menu-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div id="menu-drawer" class="relative w-72 h-full bg-[#14151c] shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out p-6 text-gray-300">
            <!-- Menu Content -->
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('menu-button');
        const menuContainer = document.getElementById('side-menu-container');
        const menuDrawer = document.getElementById('menu-drawer');
        const menuOverlay = document.getElementById('menu-overlay');

        const openMenu = () => { menuContainer.classList.remove('hidden'); setTimeout(() => menuDrawer.classList.remove('-translate-x-full'), 10); };
        const closeMenu = () => { menuDrawer.classList.add('-translate-x-full'); setTimeout(() => menuContainer.classList.add('hidden'), 300); };

        if(menuButton) menuButton.addEventListener('click', openMenu);
        if(menuOverlay) menuOverlay.addEventListener('click', closeMenu);
    });
    </script>
    @stack('scripts')
</body>
</html>
