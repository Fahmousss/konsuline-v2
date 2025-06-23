@props(['title' => 'Dashboard', 'description' => '', 'header' => ''])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - E-Healthcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/pasien/global.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <x-pasien.header />

    <div class="main">
        <x-pasien.sidebar />

        <div class="content">
            <div class="content-header">
                <h1>{{ $header }}</h1>
                <p>{{ $description }}</p>
            </div>

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    @vite(['resources/js/admin/global.js'])
    @stack('scripts')

</body>

</html>
