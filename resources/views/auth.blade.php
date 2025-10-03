<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managment Presensi SMK YPC TASIKMALAYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            box-sizing: border-box;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .demo-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full animate-float"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-white opacity-5 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-3/4 left-1/3 w-32 h-32 bg-white opacity-5 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Title -->
        <div class="text-center mb-8 animate-fadeInUp">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 animate-pulse-custom">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Managment Presensi <br> SMK YPC TASIKMALAYA</h1>
            <p class="text-indigo-100">Masuk ke akun Anda</p>
        </div>
        
        <!-- Login Form -->
        <div class="glass-effect rounded-2xl p-8 animate-fadeInUp" style="animation-delay: 0.2s;">
            <form id="loginForm" class="space-y-6" method="POST" action="{{ route('auth.login') }}">
              @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        placeholder="Masukan Email"
                        class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300 input-focus"
                        required
                    >
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Masukkan password"
                            class="w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300 input-focus pr-12"
                            required
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-indigo-200 hover:text-white transition-colors duration-200"
                        >
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <!-- <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-white bg-opacity-20 border-white border-opacity-30 rounded focus:ring-white focus:ring-2"> -->
                        <!-- <span class="ml-2 text-sm text-indigo-100">Ingat saya</span> -->
                    </label>
                    <!-- <a href="#" class="text-sm text-white hover:text-indigo-200 transition-colors duration-200">Lupa password?</a> -->
                </div>
                
                <button 
                    type="submit" 
                    id="loginBtn"
                    class="w-full bg-white text-indigo-600 py-3 px-4 rounded-lg font-semibold hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600 transition-all duration-300 transform hover:scale-105"
                >
                    <span id="btnText">Masuk</span>
                    <span id="btnLoader" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <!-- <p class="text-indigo-100 text-sm">
                    Belum punya akun? 
                    <a href="#" class="text-white font-semibold hover:text-indigo-200 transition-colors duration-200">Daftar sekarang</a>
                </p> -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 animate-fadeInUp" style="animation-delay: 0.4s;">
            <p class="text-indigo-200 text-sm">© 2025 Pusdatin SMK YPC Tasikmalaya. Semua hak dilindungi.</p>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Login Berhasil!</h3>
            <p class="text-gray-600 mb-6">Selamat datang di aplikasi presensi</p>
            <button 
                id="closeModal"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200"
            >
                Tutup
            </button>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        });

        // Handle form submission
        // document.getElementById('loginForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const loginBtn = document.getElementById('loginBtn');
        //     const btnText = document.getElementById('btnText');
        //     const btnLoader = document.getElementById('btnLoader');
        //     const successModal = document.getElementById('successModal');
            
        //     // Show loading state
        //     btnText.classList.add('hidden');
        //     btnLoader.classList.remove('hidden');
        //     loginBtn.disabled = true;
            
        //     // Simulate login process
        //     setTimeout(() => {
        //         // Hide loading state
        //         btnText.classList.remove('hidden');
        //         btnLoader.classList.add('hidden');
        //         loginBtn.disabled = false;
                
        //         // Show success modal
        //         successModal.classList.remove('hidden');
        //         successModal.querySelector('.bg-white').classList.add('scale-100');
        //         successModal.querySelector('.bg-white').classList.remove('scale-95');
        //     }, 2000);
        // });

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function() {
            const successModal = document.getElementById('successModal');
            successModal.classList.add('hidden');
            successModal.querySelector('.bg-white').classList.add('scale-95');
            successModal.querySelector('.bg-white').classList.remove('scale-100');
        });

        // Add input animation effects
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-105');
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'988229d4548a6bff',t:'MTc1OTM4Njc1NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
