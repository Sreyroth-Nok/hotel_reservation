<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Reservation System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#1a1a2e',
                            dark: '#16213e',
                        },
                        accent: {
                            DEFAULT: '#c9a961',
                            light: '#e5d4a6',
                        },
                        luxury: {
                            bg: '#f8f7f4',
                            card: '#ffffff',
                            border: '#e0ddd6',
                        }
                    },
                    fontFamily: {
                        serif: ['Cormorant Garamond', 'serif'],
                        sans: ['Work Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.6s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-luxury-bg font-sans text-gray-800">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')
        <div class="flex-1 lg:ml-72">
            @include('layouts.navbar')
            <main class="p-6 lg:p-8 fade-in">
                @include('layouts.alerts')
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>