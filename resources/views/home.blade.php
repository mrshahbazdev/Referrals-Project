@extends('layouts.frontend')

@section('title', 'Home - CodeShack')

@section('content')
    <!-- Image Slider -->
    <div id="image-slider" class="relative rounded-2xl overflow-hidden shadow-lg h-48">
        <div class="slider-track flex h-full">
            <div class="slide w-full flex-shrink-0 relative"><img src="https://placehold.co/600x300/1a1a2e/ffffff?text=Slide+1" alt="Crypto Banner 1" class="w-full h-full object-cover"><div class="absolute inset-0 p-6 flex flex-col justify-end bg-gradient-to-t from-black/80 to-transparent"><h2 class="text-2xl font-bold">CODESHACK</h2><h3 class="text-2xl font-bold text-gradient">CRYPTO CURRENCY</h3></div></div>
            <div class="slide w-full flex-shrink-0 relative"><img src="https://placehold.co/600x300/1e293b/ffffff?text=Slide+2" alt="Crypto Banner 2" class="w-full h-full object-cover"><div class="absolute inset-0 p-6 flex flex-col justify-end bg-gradient-to-t from-black/80 to-transparent"><h2 class="text-2xl font-bold">AI TRADING</h2><h3 class="text-2xl font-bold text-gradient">SMART PORTFOLIO</h3></div></div>
            <div class="slide w-full flex-shrink-0 relative"><img src="https://placehold.co/600x300/4a0e72/ffffff?text=Slide+3" alt="Crypto Banner 3" class="w-full h-full object-cover"><div class="absolute inset-0 p-6 flex flex-col justify-end bg-gradient-to-t from-black/80 to-transparent"><h2 class="text-2xl font-bold">SECURE WALLET</h2><h3 class="text-2xl font-bold text-gradient">DEBIT CARDS</h3></div></div>
        </div>
        <div id="slider-nav" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-2 z-10"></div>
    </div>

    <!-- Scrolling Announcement Bar -->
    @if($latestAnnouncement)
    <div class="bg-yellow-400/10 border border-yellow-400/30 text-yellow-300 p-2 rounded-lg flex items-center space-x-2 text-sm font-semibold marquee-container">
        <div class="marquee-content">
            <span><i class="ph-fill ph-megaphone"></i> {{ $latestAnnouncement->title }}: {{ $latestAnnouncement->content }}</span>
            <span><i class="ph-fill ph-megaphone"></i> {{ $latestAnnouncement->title }}: {{ $latestAnnouncement->content }}</span>
        </div>
    </div>
    @endif

    <!-- Icon Menu -->
    <div class="grid grid-cols-4 gap-4 text-center text-xs">
        <a href="#" class="flex flex-col items-center space-y-2"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-squares-four text-2xl text-blue-400"></i></div><span>Apps</span></a>
        <a href="#" class="flex flex-col items-center space-y-2"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-telegram-logo text-2xl text-sky-400"></i></div><span>Telegram</span></a>
        <a href="#" class="flex flex-col items-center space-y-2"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-youtube-logo text-2xl text-red-500"></i></div><span>Tutorial</span></a>
        <a href="#" class="flex flex-col items-center space-y-2"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-info text-2xl text-gray-400"></i></div><span>About Us</span></a>
    </div>

    <!-- Trading/Card Section -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="bg-[#1E1F2B] p-4 rounded-xl flex items-center justify-between hover:bg-[#2a2c3d] transition-colors"><div><p class="font-semibold">AI Trading</p><p class="text-xs text-gray-400">Binance, Paypal</p></div><i class="ph ph-chart-line-up text-3xl text-green-400"></i></a>
                    <a href="#" class="bg-[#1E1F2B] p-4 rounded-xl flex items-center justify-between hover:bg-[#2a2c3d] transition-colors"><div><p class="font-semibold">Credit/Debit Card</p><p class="text-xs text-gray-400">Visa, Mastercard</p></div><i class="ph ph-credit-card text-3xl text-purple-400"></i></a>
                </div>

                <!-- Interactive Chart Section -->
                <div class="bg-[#1E1F2B] p-4 rounded-xl">
                    <div id="chart-container" class="w-full h-auto"></div>
                    <div id="chart-controls" class="flex justify-around text-xs text-gray-400 mt-4">
                        <button data-range="1D" class="hover:text-white px-3 py-1">1D</button>
                        <button data-range="1M" class="active bg-[#2d3748] text-white rounded-full px-3 py-1">1M</button>
                        <button data-range="3M" class="hover:text-white px-3 py-1">3M</button>
                        <button data-range="1Y" class="hover:text-white px-3 py-1">1Y</button>
                        <button data-range="All" class="hover:text-white px-3 py-1">All</button>
                    </div>
                </div>

                <!-- Dynamic Crypto List -->
                <div id="crypto-list" class="space-y-2">
                    <div id="coin-btc" class="bg-[#1E1F2B] p-4 rounded-xl flex items-center justify-between transition-all duration-500"><div class="flex items-center space-x-3"><img src="https://cryptologos.cc/logos/bitcoin-btc-logo.svg?v=032" class="w-8 h-8" alt="Bitcoin"><div><p class="font-bold">BTCUSDT</p><p class="text-xs text-gray-400">Bitcoin / TetherUS</p></div></div><div class="text-right"><p id="btc-price" class="font-bold">118,167.74</p><p id="btc-change" class="text-xs text-green-500">+0.21%</p></div></div>
                    <div id="coin-sol" class="bg-[#1E1F2B] p-4 rounded-xl flex items-center justify-between transition-all duration-500"><div class="flex items-center space-x-3"><img src="https://cryptologos.cc/logos/solana-sol-logo.svg?v=032" class="w-8 h-8" alt="Solana"><div><p class="font-bold">SOLUSDT</p><p class="text-xs text-gray-400">SOL / TetherUS</p></div></div><div class="text-right"><p id="sol-price" class="font-bold">186.76</p><p id="sol-change" class="text-xs text-green-500">+1.02%</p></div></div>
                </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Side Menu Logic ---
        const menuButton = document.getElementById('menu-button');
        const closeMenuButton = document.getElementById('close-menu-button');
        const menuContainer = document.getElementById('side-menu-container');
        const menuDrawer = document.getElementById('menu-drawer');
        const menuOverlay = document.getElementById('menu-overlay');
        const openMenu = () => { menuContainer.classList.remove('hidden'); setTimeout(() => menuDrawer.classList.remove('-translate-x-full'), 10); };
        const closeMenu = () => { menuDrawer.classList.add('-translate-x-full'); setTimeout(() => menuContainer.classList.add('hidden'), 300); };
        menuButton.addEventListener('click', openMenu);
        closeMenuButton.addEventListener('click', closeMenu);
        menuOverlay.addEventListener('click', closeMenu);

        // --- Image Slider Logic ---
        const slider = document.getElementById('image-slider');
        const track = slider.querySelector('.slider-track');
        const slides = Array.from(track.children);
        const navDotsContainer = document.getElementById('slider-nav');
        const slideWidth = slides.length > 0 ? slides[0].getBoundingClientRect().width : 0;
        let currentIndex = 0;
        let intervalId;
        if (slides.length > 0) {
            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.classList.add('w-2', 'h-2', 'rounded-full', 'transition-all', 'duration-300', i === 0 ? 'bg-yellow-400' : 'bg-gray-500');
                dot.addEventListener('click', () => { currentIndex = i; goToSlide(currentIndex); resetInterval(); });
                navDotsContainer.appendChild(dot);
            });
            const dots = Array.from(navDotsContainer.children);
            const goToSlide = (index) => { track.style.transform = 'translateX(-' + slideWidth * index + 'px)'; dots.forEach((dot, i) => { dot.classList.toggle('bg-yellow-400', i === index); dot.classList.toggle('bg-gray-500', i !== index); }); };
            const nextSlide = () => { currentIndex = (currentIndex + 1) % slides.length; goToSlide(currentIndex); };
            const resetInterval = () => { clearInterval(intervalId); intervalId = setInterval(nextSlide, 5000); };
            goToSlide(0);
            resetInterval();
        }

        // --- Interactive Chart Logic ---
        const chartContainer = document.getElementById('chart-container');
        const chartControls = document.getElementById('chart-controls');
        const chartData = {
            '1D': '0,80 50,75 100,60 150,65 200,50 250,55 300,40', '1M': '0,80 20,60 40,70 60,50 80,55 100,40 120,45 140,30 160,35 180,20 200,25 220,15 240,20 260,10 280,15 300,5', '3M': '0,60 30,80 60,50 90,70 120,40 150,60 180,30 210,50 240,20 270,40 300,10', '1Y': '0,90 50,40 100,70 150,30 200,60 250,20 300,50', 'All': '0,20 50,80 100,30 150,90 200,40 250,95 300,50'
        };
        const createChart = (points) => {
            const areaPoints = `${points} 300,100 0,100`;
            const pointArray = points.split(' ').map(p => p.split(','));
            let circles = pointArray.map(p => `<circle cx="${p[0]}" cy="${p[1]}" r="3" fill="#14151c" stroke="#38bdf8" stroke-width="2"/>`).join('');
            chartContainer.innerHTML = `<svg viewBox="0 0 300 100" class="w-full h-auto" preserveAspectRatio="none"><defs><linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#38bdf8;stop-opacity:0.4"/><stop offset="100%" style="stop-color:#38bdf8;stop-opacity:0"/></linearGradient></defs><path d="M ${areaPoints}" fill="url(#chartGradient)" class="chart-area"/><path d="M ${points}" fill="none" stroke="#38bdf8" stroke-width="2" class="chart-line"/>${circles}</svg>`;
        };
        chartControls.addEventListener('click', (e) => {
            if (e.target.tagName === 'BUTTON') {
                chartControls.querySelectorAll('button').forEach(btn => btn.classList.remove('active', 'bg-[#2d3748]', 'text-white', 'rounded-full'));
                e.target.classList.add('active', 'bg-[#2d3748]', 'text-white', 'rounded-full');
                createChart(chartData[e.target.dataset.range]);
            }
        });
        createChart(chartData['1M']);

        // --- Dynamic Crypto List Logic ---
        const btcPriceEl = document.getElementById('btc-price');
        const btcChangeEl = document.getElementById('btc-change');
        const btcRow = document.getElementById('coin-btc');
        const solPriceEl = document.getElementById('sol-price');
        const solChangeEl = document.getElementById('sol-change');
        const solRow = document.getElementById('coin-sol');
        let currentBtcPrice = 118167.74;
        let currentSolPrice = 186.76;
        setInterval(() => {
            let btcChange = (Math.random() - 0.49) * 100;
            currentBtcPrice += btcChange;
            let btcPercentChange = (btcChange / (currentBtcPrice - btcChange)) * 100;
            btcPriceEl.textContent = currentBtcPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            btcChangeEl.textContent = `${btcPercentChange >= 0 ? '+' : ''}${btcPercentChange.toFixed(2)}%`;
            btcRow.classList.remove('update-flash-green', 'update-flash-red');
            if (btcPercentChange >= 0) { btcChangeEl.className = 'text-xs text-green-500'; setTimeout(() => btcRow.classList.add('update-flash-green'), 10); } else { btcChangeEl.className = 'text-xs text-red-500'; setTimeout(() => btcRow.classList.add('update-flash-red'), 10); }
            let solChange = (Math.random() - 0.5) * 2;
            currentSolPrice += solChange;
            let solPercentChange = (solChange / (currentSolPrice - solChange)) * 100;
            solPriceEl.textContent = currentSolPrice.toFixed(2);
            solChangeEl.textContent = `${solPercentChange >= 0 ? '+' : ''}${solPercentChange.toFixed(2)}%`;
            solRow.classList.remove('update-flash-green', 'update-flash-red');
            if (solPercentChange >= 0) { solChangeEl.className = 'text-xs text-green-500'; setTimeout(() => solRow.classList.add('update-flash-green'), 10); } else { solChangeEl.className = 'text-xs text-red-500'; setTimeout(() => solRow.classList.add('update-flash-red'), 10); }
        }, 3000);
    });
</script>
@endpush
