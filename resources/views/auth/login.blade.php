<x-guest-layout>
    
    <div class="min-h-screen flex">
        
        <!-- Left Side - Illustration -->
        <div class="hidden lg:flex lg:w-1/2 illustration items-center justify-center p-12 relative overflow-hidden">
            <div class="max-w-lg text-center relative z-10">
                <h1 class="text-white text-4xl font-bold mb-4">Welcome Back</h1>
                <p class="text-indigo-100 text-lg mb-8">Sign in to your account and continue</p>
                
                <!-- Illustration SVG placeholder - you can replace with actual image -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 mx-auto max-w-md">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/20 rounded-2xl p-6 flex items-center justify-center">
                                <i class="ri-mail-line text-white text-5xl"></i>
                            </div>
                            <div class="bg-white/20 rounded-2xl p-6 flex items-center justify-center">
                                <i class="ri-notification-3-line text-white text-5xl"></i>
                            </div>
                            <div class="bg-white/20 rounded-2xl p-6 flex items-center justify-center">
                                <i class="ri-calendar-line text-white text-5xl"></i>
                            </div>
                            <div class="bg-white/20 rounded-2xl p-6 flex items-center justify-center">
                                <i class="ri-settings-3-line text-white text-5xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-center space-x-4">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="ri-user-line text-white text-2xl"></i>
                            </div>
                            <div class="text-left">
                                <div class="h-3 w-24 bg-white/30 rounded mb-2"></div>
                                <div class="h-2 w-16 bg-white/20 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative circles -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                
                <!-- Logo -->
                <div class="mb-8">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="ri-lock-password-line text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ config('app.name', 'SILAB') }}</span>
                    </div>
                </div>

                <!-- Form Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Log In</h2>
                    <p class="text-gray-600">Masukkan Akun Absensi Untuk Masuk.</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="alert alert-error mb-6">
                    <i class="ri-error-warning-line text-xl"></i>
                    <div>
                        <ul class="list-none text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Username Field -->
                    <div class="form-control">
                        <label class="label pb-2">
                            <span class="label-text text-gray-700 font-medium">Masukkan Nama / Username</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="Username" 
                            class="input input-bordered w-full bg-white border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 @error('name') input-error @enderror" 
                            required 
                            autofocus
                        />
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-control">
                        <label class="label pb-2">
                            <span class="label-text text-gray-700 font-medium">Masukkan Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Password" 
                            class="input input-bordered w-full bg-white border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 @error('password') input-error @enderror" 
                            required
                        />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Lupa Password ?
                        </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary w-full bg-indigo-600 hover:bg-indigo-700 border-none text-white font-medium h-12 text-base">
                        Masuk
                    </button>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-center pt-2">
                        <label class="label cursor-pointer gap-2">
                            <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm border-2" />
                            <span class="label-text text-gray-600">Biarkan saya tetap masuk</span>
                        </label>
                    </div>
                </form>

            </div>
        </div>

    </div>

</x-guest-layout>