<!DOCTYPE html>
<html lang="id" x-data="authPage()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HOSPITALINK - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-hospitalink-blue {
            background-color: #00A2FA;
        }

        .bg-hospitalink-green {
            background-color: #0B9078;
        }

        .text-hospitalink-green {
            color: #0B9078;
        }

        .border-hospitalink-green {
            border-color: #0B9078;
        }
    </style>
</head>

<body class="min-h-screen bg-hospitalink-blue">
    <!-- Mobile Layout -->
    <div class="lg:hidden min-h-screen flex flex-col justify-center w-full max-w-[100vw] overflow-x-hidden">
        <!-- Logo Area -->
        <div class="flex items-center justify-center px-4 sm:px-6 py-2 -mt-20">
            <div class="text-center w-full max-w-[280px] sm:max-w-[320px]">
                <img src="/images/Logo-Hospitalink.png" alt="HOSPITALINK Logo" class="w-full mx-auto mb-2">
            </div>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-3xl px-4 py-5 min-h-[50vh] w-[90%] sm:w-[85%] max-w-sm mx-auto shadow-lg">
            <!-- Tab Toggle -->
            <div class="mb-5 mx-4 sm:mx-8 mt-4">
                <div class="flex bg-gray-100 rounded-full p-0 border border-black">
                    <button @click="activeTab = 'login'"
                        :class="activeTab === 'login' ? 'bg-hospitalink-green text-white border border-black' :
                            'text-gray-600'"
                        class="flex-1 py-1.5 px-3 rounded-full text-sm font-small transition-all duration-200">
                        Log In
                    </button>
                    <button @click="activeTab = 'signup'"
                        :class="activeTab === 'signup' ? 'bg-hospitalink-green text-white border border-black' :
                            'text-gray-600'"
                        class="flex-1 py-1.5 px-3 rounded-full text-sm font-medium transition-all duration-200">
                        Sign Up
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form x-show="activeTab === 'login'" @submit.prevent="login()" class="space-y-3 mx-6 sm:mx-10 mt-8">
                <!-- Error Message -->
                <div x-show="loginError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                    <span x-text="loginError"></span>
                </div>
                
                @if ($errors->has('email'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                    {{ $errors->first('email') }}
                </div>
                @endif

                <!-- Success Message -->
                <div x-show="loginSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                    <span x-text="loginSuccess"></span>
                </div>

                <!-- Email Input -->
                <div class="relative">
                    <input type="email" x-model="loginForm.email" placeholder="Enter email"
                        class="w-full py-1.5 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm"
                        required>
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="loginForm.password" placeholder="Password"
                        class="w-full py-1.5 pr-8 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm"
                        required>
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-0 top-1.5 text-gray-400 hover:text-gray-600">
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-md"></i>
                    </button>
                </div>

                <!-- Forgot Password -->
                <div class="text-left">
                    <a href="#" class="text-sm text-black hover:text-hospitalink-green">Lupa kata sandi?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" :disabled="isLoading"
                    class="w-full bg-hospitalink-green text-white py-3 rounded-full text-sm font-small hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50">
                    <span x-show="!isLoading">Log In</span>
                    <span x-show="isLoading">Loading...</span>
                </button>

                <!-- Switch to Signup -->
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun? <a @click="activeTab = 'signup'" href="javascript:void(0)"
                            class="text-hospitalink-green hover:underline">Daftar</a>
                    </p>
                </div>
            </form>

            <!-- Signup Form -->
            <form x-show="activeTab === 'signup'" @submit.prevent="signup()" class="space-y-3 mx-6 sm:mx-10 mt-8" x-cloak>
                <!-- Error Message -->
                <div x-show="signupError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                    <span x-text="signupError"></span>
                </div>

                <!-- Success Message -->
                <div x-show="signupSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                    <span x-text="signupSuccess"></span>
                </div>

                <!-- Full Name Input -->
                <div class="relative">
                    <input type="text" x-model="signupForm.name" placeholder="Full Name"
                        class="w-full py-1.5 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm"
                        required>
                </div>

                <!-- Email Input -->
                <div class="relative">
                    <input type="email" x-model="signupForm.email" placeholder="Email"
                        class="w-full py-1.5 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm"
                        required>
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="signupForm.password" placeholder="Password (minimal 8 karakter)"
                        class="w-full py-2 pr-10 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm"
                        required>
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-0 top-2 text-gray-400 hover:text-gray-600">
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>

                <!-- Password Strength Indicator -->
                <div x-show="signupForm.password.length > 0" class="space-y-1">
                    <!-- Password Strength Bar -->
                    <div class="flex space-x-1">
                        <div class="h-1 flex-1 rounded-full"
                             :class="signupForm.password.length >= 8 ? 'bg-green-500' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded-full"
                             :class="signupForm.password.length >= 12 ? 'bg-green-500' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded-full"
                             :class="signupForm.password.length >= 16 ? 'bg-green-500' : 'bg-gray-200'"></div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="text-xs space-y-1">
                        <div class="flex items-center space-x-2">
                            <i :class="signupForm.password.length >= 8 ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
                            <span :class="signupForm.password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                                Minimal 8 karakter
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="relative">
                    <input :type="showConfirmPassword ? 'text' : 'password'" x-model="signupForm.password_confirmation" placeholder="Konfirmasi password"
                        class="w-full py-1.5 pr-8 border-0 border-b border-gray-400 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-sm mb-2"
                        required>
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute right-0 top-1.5 text-gray-400 hover:text-gray-600">
                        <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-md"></i>
                    </button>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" :disabled="isLoading"
                    class="w-full bg-hospitalink-green text-white py-3 rounded-full text-sm font-small hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50">
                    <span x-show="!isLoading">Sign Up</span>
                    <span x-show="isLoading">Loading...</span>
                </button>

                <!-- Switch to Login -->
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun? <a @click="activeTab = 'login'" href="javascript:void(0)"
                            class="text-hospitalink-green hover:underline">Login</a>
                    </p>
                </div>
            </form>

            <!-- Social Login -->
            <div class="mt-5 mx-7">
                <p class="text-center text-xs text-gray-500 mb-3">OR</p>
                <div class="flex justify-center space-x-4 sm:space-x-6">
                    <a href="/auth/facebook"
                        class="block w-8 h-8 sm:w-9 sm:h-9 rounded-full border-1 border-gray-300 overflow-hidden hover:scale-110 transition-transform">
                        <img src="/images/Facebook_Logo.png" alt="Facebook" class="w-full h-full object-cover">
                    </a>
                    <a href="/auth/google"
                        class="block w-9 h-9 sm:w-10 sm:h-10 rounded-full border-1 border-gray-300 overflow-hidden hover:scale-110 transition-transform">
                        <img src="/images/Google_Logo.jpg" alt="Google" class="w-full h-full object-cover scale-125">
                    </a>
                    <button @click="showTwitterPopup = true"
                        class="block w-9 h-9 sm:w-10 sm:h-10 rounded-full border-1 border-gray-300 overflow-hidden -translate-x-1 hover:scale-110 transition-transform">
                        <img src="/images/X_Logo.png" alt="X" class="w-full h-full object-cover scale-75">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Left Column - Logo -->
        <div class="w-2/5 bg-hospitalink-blue flex items-center justify-center">
            <div class="text-center">
                <img src="/images/Logo-Hospitalink.png" alt="HOSPITALINK Logo" class="w-112 mx-auto mb-6">

            </div>
        </div>

        <!-- Right Column - Auth Form -->
        <div class="w-3/5 bg-white flex items-center justify-center px-6">
            <div class="w-full max-w-xs">
                <!-- Tab Toggle -->
                <div class="mb-5 mx-8 mt-4">
                    <div class="flex bg-gray-100 rounded-full p-1 border border-black">
                        <button @click="activeTab = 'login'"
                            :class="activeTab === 'login' ? 'bg-hospitalink-green text-white' : 'text-gray-600'"
                            class="flex-1 py-1.5 px-3 rounded-full text-xs font-medium transition-all duration-200">
                            Log In
                        </button>
                        <button @click="activeTab = 'signup'"
                            :class="activeTab === 'signup' ? 'bg-hospitalink-green text-white' : 'text-gray-600'"
                            class="flex-1 py-1.5 px-3 rounded-full text-xs font-medium transition-all duration-200">
                            Sign Up
                        </button>
                    </div>
                </div>

                <!-- Login Form -->
                <form x-show="activeTab === 'login'" @submit.prevent="login()" class="space-y-3 mx-8">
                    <!-- Error Message -->
                    <div x-show="loginError" class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs">
                        <span x-text="loginError"></span>
                    </div>
                    
                    @if ($errors->has('email'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs">
                        {{ $errors->first('email') }}
                    </div>
                    @endif

                    <!-- Success Message -->
                    <div x-show="loginSuccess" class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-xs">
                        <span x-text="loginSuccess"></span>
                    </div>

                    <!-- Email/Username Input -->
                    <div class="relative">
                        <input type="email" x-model="loginForm.email" placeholder="Enter email"
                            class="w-full py-1.5 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" x-model="loginForm.password" placeholder="Password"
                            class="w-full py-1.5 pr-8 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-0 top-1.5 text-gray-400 hover:text-gray-600">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                        </button>
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-right">
                        <a href="#" class="text-xs text-gray-500 hover:text-hospitalink-green">Lupa kata
                            sandi?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" :disabled="isLoading"
                        class="w-full bg-hospitalink-green text-white py-2 rounded-full text-xs font-medium hover:bg-green-600 transition-all duration-200 disabled:opacity-50">
                        <span x-show="!isLoading">Login</span>
                        <span x-show="isLoading">Loading...</span>
                    </button>

                    <!-- Switch to Signup -->
                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            Belum punya akun? <a @click="activeTab = 'signup'" href="javascript:void(0)"
                                class="text-hospitalink-green hover:underline">Daftar</a>
                        </p>
                    </div>
                </form>

                <!-- Signup Form -->
                <form x-show="activeTab === 'signup'" @submit.prevent="signup()" class="space-y-3 mx-8" x-cloak>
                    <!-- Error Message -->
                    <div x-show="signupError" class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs">
                        <span x-text="signupError"></span>
                    </div>

                    <!-- Success Message -->
                    <div x-show="signupSuccess" class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-xs">
                        <span x-text="signupSuccess"></span>
                    </div>

                    <!-- Full Name Input -->
                    <div class="relative">
                        <input type="text" x-model="signupForm.name" placeholder="Full Name"
                            class="w-full py-1.5 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                    </div>

                    <!-- Email Input -->
                    <div class="relative">
                        <input type="email" x-model="signupForm.email" placeholder="Email"
                            class="w-full py-1.5 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" x-model="signupForm.password" placeholder="Password (minimal 8 karakter)"
                            class="w-full py-1.5 pr-8 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-0 top-1.5 text-gray-400 hover:text-gray-600">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                        </button>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div x-show="signupForm.password.length > 0" class="space-y-1">
                        <!-- Password Strength Bar -->
                        <div class="flex space-x-1">
                            <div class="h-1 flex-1 rounded-full"
                                 :class="signupForm.password.length >= 8 ? 'bg-green-500' : 'bg-gray-200'"></div>
                            <div class="h-1 flex-1 rounded-full"
                                 :class="signupForm.password.length >= 12 ? 'bg-green-500' : 'bg-gray-200'"></div>
                            <div class="h-1 flex-1 rounded-full"
                                 :class="signupForm.password.length >= 16 ? 'bg-green-500' : 'bg-gray-200'"></div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="text-xs space-y-1">
                            <div class="flex items-center space-x-2">
                                <i :class="signupForm.password.length >= 8 ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'" class="text-xs"></i>
                                <span :class="signupForm.password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                                    Minimal 8 karakter
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" x-model="signupForm.password_confirmation" placeholder="Confirm Password"
                            class="w-full py-1.5 pr-8 border-0 border-b border-gray-300 focus:border-hospitalink-green focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 text-xs"
                            required>
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute right-0 top-1.5 text-gray-400 hover:text-gray-600">
                            <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                        </button>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" :disabled="isLoading"
                        class="w-full bg-hospitalink-green text-white py-2 rounded-full text-xs font-medium hover:bg-green-600 transition-all duration-200 disabled:opacity-50">
                        <span x-show="!isLoading">Sign Up</span>
                        <span x-show="isLoading">Loading...</span>
                    </button>

                    <!-- Switch to Login -->
                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            Sudah punya akun? <a @click="activeTab = 'login'" href="javascript:void(0)"
                                class="text-hospitalink-green hover:underline">Login</a>
                        </p>
                    </div>
                </form>

                <!-- Social Login -->
                <div class="mt-5 mx-8">
                    <p class="text-center text-xs text-gray-500 mb-3">Login dengan</p>
                    <div class="flex justify-center space-x-3">
                        <a href="/auth/facebook" class="bg-blue-600 text-white p-1.5 rounded-full hover:bg-blue-700 text-md transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="/auth/google" class="bg-red-600 text-white p-1.5 rounded-full hover:bg-red-700 text-xs transition-colors">
                            <i class="fab fa-google"></i>
                        </a>
                        <button @click="showTwitterPopup = true" class="bg-blue-400 text-white p-1.5 rounded-full hover:bg-blue-500 text-xs transition-colors" aria-label="Login dengan Twitter">
                            <span class="lg:hidden"><i class="fab fa-x-twitter"></i></span>
                            <img src="/images/X_Logo.png" alt="Twitter" class="hidden lg:inline-block w-4 h-4 object-contain" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Twitter Popup Modal -->
    <div x-show="showTwitterPopup" x-cloak 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showTwitterPopup = false">
        <div class="bg-white rounded-2xl p-6 mx-4 max-w-sm w-full shadow-2xl transform transition-all duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Close Button -->
            <div class="flex justify-end mb-4">
                <button @click="showTwitterPopup = false" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Message -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Maaf!</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Fitur login dengan Twitter belum tersedia saat ini. Silakan gunakan opsi login lainnya atau daftar dengan email.
                </p>
            </div>
        </div>
    </div>

    <script>
        function authPage() {
            return {
                activeTab: 'login',
                showPassword: false,
                showConfirmPassword: false,
                showTwitterPopup: false,
                isLoading: false,
                loginError: '',
                loginSuccess: '',
                signupError: '',
                signupSuccess: '',
                loginForm: {
                    email: '',
                    password: ''
                },
                signupForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                
                async login() {
                    this.isLoading = true;
                    this.loginError = '';
                    this.loginSuccess = '';
                    
                    try {
                        const formData = new FormData();
                        formData.append('email', this.loginForm.email);
                        formData.append('password', this.loginForm.password);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                        
                        console.log('Attempting login for:', this.loginForm.email);
                        
                        const response = await fetch('/login', {
                            method: 'POST',
                            body: formData,
                            redirect: 'manual' // Handle redirects manually
                        });
                        
                        console.log('Login response status:', response.status);
                        console.log('Login response URL:', response.url);
                        
                        if (response.status === 302) {
                            // Handle redirect response
                            const location = response.headers.get('Location');
                            console.log('Redirect location:', location);
                            if (location) {
                                if (location.includes('/admin-dashboard')) {
                                    console.log('Redirecting to admin dashboard');
                                    window.location.href = '/admin-dashboard';
                                } else if (location.includes('/dashboard')) {
                                    console.log('Redirecting to dashboard');
                                    window.location.href = '/dashboard';
                                } else {
                                    window.location.href = location;
                                }
                            } else {
                                // Fallback to dashboard if no location header
                                window.location.href = '/dashboard';
                            }
                        } else if (response.status === 0 || response.ok) {
                            // Handle successful response without redirect
                            // Check if we can determine user role from response
                            console.log('Login successful, checking user role...');
                            
                            // Try to get user info to determine redirect
                            fetch('/api/user', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            })
                            .then(userResponse => userResponse.json())
                            .then(userData => {
                                if (userData.user && userData.user.role === 'admin') {
                                    console.log('User is admin, redirecting to admin dashboard');
                                    window.location.href = '/admin-dashboard';
                                } else {
                                    console.log('User is patient, redirecting to dashboard');
                                    window.location.href = '/dashboard';
                                }
                            })
                            .catch(error => {
                                console.log('Could not determine user role, redirecting to dashboard');
                                window.location.href = '/dashboard';
                            });
                        } else {
                            // Handle error response
                            const responseText = await response.text();
                            console.error('Login failed:', responseText);
                            
                            // Try to parse error message from response
                            try {
                                const errorData = JSON.parse(responseText);
                                this.loginError = errorData.message || 'Email atau password salah';
                            } catch (parseError) {
                                this.loginError = 'Email atau password salah';
                            }
                        }
                    } catch (error) {
                        this.loginError = 'Terjadi kesalahan saat login';
                        console.error('Login error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                async signup() {
                    this.isLoading = true;
                    this.signupError = '';
                    this.signupSuccess = '';
                    
                    // Validate password length
                    if (this.signupForm.password.length < 8) {
                        this.signupError = 'Password harus minimal 8 karakter';
                        this.isLoading = false;
                        return;
                    }
                    
                    // Validate password confirmation
                    if (this.signupForm.password !== this.signupForm.password_confirmation) {
                        this.signupError = 'Password dan konfirmasi password tidak sama';
                        this.isLoading = false;
                        return;
                    }
                    
                    try {
                        const formData = new FormData();
                        formData.append('name', this.signupForm.name);
                        formData.append('email', this.signupForm.email);
                        formData.append('password', this.signupForm.password);
                        formData.append('password_confirmation', this.signupForm.password_confirmation);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                        
                        console.log('Attempting signup for:', this.signupForm.email);
                        
                        const response = await fetch('/register', {
                            method: 'POST',
                            body: formData,
                            redirect: 'manual' // Handle redirects manually
                        });
                        
                        console.log('Signup response status:', response.status);
                        console.log('Signup response URL:', response.url);
                        
                        if (response.status === 302) {
                            // Handle redirect response
                            const location = response.headers.get('Location');
                            console.log('Redirect location:', location);
                            if (location) {
                                if (location.includes('/admin-dashboard')) {
                                    console.log('Redirecting to admin dashboard');
                                    window.location.href = '/admin-dashboard';
                                } else if (location.includes('/dashboard')) {
                                    console.log('Redirecting to dashboard');
                                    window.location.href = '/dashboard';
                                } else {
                                    window.location.href = location;
                                }
                            } else {
                                // Fallback to dashboard if no location header
                                window.location.href = '/dashboard';
                            }
                        } else if (response.status === 0 || response.ok) {
                            // Handle successful response without redirect
                            console.log('Signup successful, redirecting to dashboard');
                            window.location.href = '/dashboard';
                        } else {
                            // Handle error response
                            const responseText = await response.text();
                            console.error('Signup failed:', responseText);
                            
                            // Try to parse error message from response
                            try {
                                const errorData = JSON.parse(responseText);
                                this.signupError = errorData.message || 'Registrasi gagal. Silakan periksa data yang dimasukkan.';
                            } catch (parseError) {
                                // Check if response contains validation errors
                                if (responseText.includes('validation')) {
                                    this.signupError = 'Data yang dimasukkan tidak valid. Silakan periksa kembali.';
                                } else {
                                    this.signupError = 'Registrasi gagal. Silakan periksa data yang dimasukkan.';
                                }
                            }
                        }
                    } catch (error) {
                        this.signupError = 'Terjadi kesalahan saat registrasi';
                        console.error('Signup error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</body>

</html>
