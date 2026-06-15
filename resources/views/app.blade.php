<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Desaku — Portal informasi dan layanan administrasi desa. Akses layanan surat online, informasi publik, dan pantau status pengajuan.">
        <meta name="theme-color" content="#0F766E">

        <link rel="icon" type="image/svg+xml" href="{{ (\App\Models\PengaturanGampong::get('logo_fav') && \Illuminate\Support\Facades\Storage::disk('public')->exists(\App\Models\PengaturanGampong::get('logo_fav'))) ? \Illuminate\Support\Facades\Storage::url(\App\Models\PengaturanGampong::get('logo_fav')) : asset('logo-fav.svg') }}">

        <title inertia>{{ config('app.name', 'Desaku') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @inertiaHead
    </head>
    <body class="font-sans">
        @inertia
    </body>
</html>
