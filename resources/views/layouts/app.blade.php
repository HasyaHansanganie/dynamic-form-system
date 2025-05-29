<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dynamic Form System' }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center text-gray-900">
    <div class="w-full max-w-2xl bg-white shadow rounded p-6">
        @yield('content')
    </div>
</body>

</html>