<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-indigo-300 via-purple-300 to-pink-300 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
        
        <!-- Elemen Bercak Warna (Blob) Animasi di Latar Belakang -->
        <div class="absolute top-0 -left-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob pointer-events-none z-0"></div>
        <div class="absolute top-0 -right-10 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000 pointer-events-none z-0"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000 pointer-events-none z-0"></div>

        <!-- Animasi Gelembung Kaca Melayang -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="bubble w-32 h-32 left-[10%] animation-delay-0"></div>
            <div class="bubble w-48 h-48 left-[30%] animation-delay-2000"></div>
            <div class="bubble w-24 h-24 left-[60%] animation-delay-4000"></div>
            <div class="bubble w-40 h-40 left-[80%] animation-delay-1500"></div>
            <div class="bubble w-56 h-56 left-[45%] animation-delay-3000"></div>
        </div>

        <!-- Logo Aplikasi -->
        <div class="relative z-10 mb-4 transition-transform duration-300 hover:scale-110">
            <Link href="/">
                <ApplicationLogo class="h-24 w-24 fill-current text-indigo-700 drop-shadow-xl" />
            </Link>
        </div>

        <!-- Kotak Login Glassmorphism -->
        <div class="relative z-10 w-full sm:max-w-md mt-2 px-8 py-10 bg-white/30 backdrop-blur-xl border border-white/40 shadow-[0_8px_32px_0_rgba(31,38,135,0.2)] overflow-hidden rounded-3xl">
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-extrabold text-gray-800 drop-shadow-sm tracking-wide">Selamat Datang</h2>
                <p class="text-gray-600 font-medium text-sm mt-1">Silakan masuk ke akun Kasir Anda</p>
            </div>

            <!-- Area Input Form Login -->
            <div class="form-glass">
                <slot />
            </div>
            
        </div>
    </div>
</template>

<style>
/* Animasi Gelembung Kaca (Glass Bubbles) */
.bubble {
    position: absolute;
    bottom: -20vh;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    animation: floatUp 15s infinite ease-in-out;
}

@keyframes floatUp {
    0% { transform: translateY(0) scale(0.8) translateX(0) rotate(0deg); opacity: 0; }
    20% { opacity: 1; }
    50% { transform: translateY(-50vh) scale(1.1) translateX(30px) rotate(180deg); }
    100% { transform: translateY(-120vh) scale(0.8) translateX(-30px) rotate(360deg); opacity: 0; }
}

.animation-delay-0 { animation-delay: 0s; }
.animation-delay-1500 { animation-delay: 1.5s; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-3000 { animation-delay: 3s; }
.animation-delay-4000 { animation-delay: 4s; }

/* Animasi Bercak Warna (Blob) */
.animate-blob {
    animation: blob 7s infinite alternate;
}

@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

/* Kustomisasi Form Input Bawaan Laravel Menjadi Transparan */
.form-glass input[type="email"],
.form-glass input[type="password"],
.form-glass input[type="text"] {
    background-color: rgba(255, 255, 255, 0.4) !important;
    border-color: rgba(255, 255, 255, 0.6) !important;
    border-radius: 0.75rem !important;
    color: #1f2937 !important;
    backdrop-filter: blur(4px);
    transition: all 0.3s ease;
}

.form-glass input:focus {
    background-color: rgba(255, 255, 255, 0.7) !important;
    border-color: #8b5cf6 !important;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.2) !important;
}

.form-glass label {
    color: #374151 !important;
    font-weight: 700 !important;
    margin-bottom: 0.25rem !important;
}

.form-glass input[type="checkbox"] {
    background-color: rgba(255, 255, 255, 0.5) !important;
    border-color: rgba(255, 255, 255, 0.8) !important;
    border-radius: 0.25rem !important;
}

/* Mempercantik Tombol Login */
.form-glass button {
    background: linear-gradient(to right, #8b5cf6, #ec4899) !important;
    border: none !important;
    border-radius: 0.75rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.05em !important;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3) !important;
    transition: all 0.3s ease !important;
}

.form-glass button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5) !important;
}
</style>