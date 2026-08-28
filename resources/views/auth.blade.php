<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Presensi - SMK YPC TASIKMALAYA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side: Branding / Image -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 items-center justify-center">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Education" class="w-full h-full object-cover opacity-20" />
            </div>
            
            <!-- Content -->
            <div class="relative z-10 px-12 max-w-2xl">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 mb-6 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        Sistem Manajemen Presensi <br>
                        <span class="text-primary-100">SMK YPC Tasikmalaya</span>
                    </h1>
                    <p class="text-lg text-primary-50/80 leading-relaxed max-w-xl">
                        Platform presensi modern dan terintegrasi untuk memudahkan pemantauan kehadiran secara real-time dan akurat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative shadow-[0_0_40px_rgba(0,0,0,0.05)] lg:shadow-[-20px_0_40px_rgba(0,0,0,0.05)] z-10">
            <div class="mx-auto w-full max-w-sm lg:max-w-md">
                
                <div class="lg:hidden mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-50 rounded-2xl mb-4 text-primary-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">SMK YPC Tasikmalaya</h2>
                </div>

                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Selamat Datang!</h2>
                    <p class="mt-2 text-sm text-gray-500">Silakan masuk menggunakan akun Anda.</p>
                </div>

                <div class="mt-10">
                    <form id="loginForm" method="POST" action="{{ route('auth.login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <div class="mt-2">
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    autocomplete="email" 
                                    required 
                                    placeholder="nama@email.com"
                                    class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition-shadow duration-200"
                                >
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <div class="mt-2 relative">
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    autocomplete="current-password" 
                                    required 
                                    placeholder="Masukkan kata sandi"
                                    class="block w-full rounded-xl border-0 py-3 px-4 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition-shadow duration-200"
                                >
                                <button 
                                    type="button" 
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">Lupa kata sandi?</a>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                id="loginBtn"
                                class="flex w-full justify-center rounded-xl bg-primary-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200"
                            >
                                <span id="btnText">Masuk ke Sistem</span>
                                <svg id="btnLoader" class="hidden animate-spin ml-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="mt-12 text-center text-sm text-gray-500">
                    &copy; 2025 Pusdatin SMK YPC Tasikmalaya. Semua hak dilindungi.
                </div>
            </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });

        // Basic form submit handling for UI feedback
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const loginBtn = document.getElementById('loginBtn');
            
            btnText.textContent = 'Memproses...';
            btnLoader.classList.remove('hidden');
            loginBtn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
