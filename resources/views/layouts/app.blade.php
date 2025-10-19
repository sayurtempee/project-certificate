<!DOCTYPE html>
<html lang="en">

<style>
    body {
        font-family: 'Tinos', serif;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Project Certificate')</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{--  Fonts Times New Roman  --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    {{-- Styles tambahan dari child view --}}
    @stack('styles')
</head>

<body class="antialiased bg-gray-100">
    @yield('content')

    <script>
        feather.replace();
    </script>

    {{-- Scripts tambahan dari child view --}}
    @stack('scripts')

    {{-- Scripts tambahan dari child view --}}
    @stack('scripts')

    {{-- Auto Logout jika idle --}}
    <script>
        let idleTime = 0;
        const maxIdle = 10; // menit

        function resetIdleTime() {
            idleTime = 0;
        }

        // Deteksi aktivitas user
        window.onload = resetIdleTime;
        document.onmousemove = resetIdleTime;
        document.onkeypress = resetIdleTime;
        document.onclick = resetIdleTime;
        document.onscroll = resetIdleTime;

        // Hitung idle tiap menit
        setInterval(function() {
            idleTime++;
            if (idleTime >= maxIdle) {
                document.getElementById('autoLogoutForm').submit();
            }
        }, 60000); // cek tiap 60 detik
    </script>

    <form id="autoLogoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
        @csrf
    </form>
</body>

</html>
