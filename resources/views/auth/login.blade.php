<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | Orientation Campus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="glass-panel w-full max-w-md rounded-[2rem] border border-white/70 p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Orientation Campus</p>
            <h1 class="page-title mt-4 text-3xl font-semibold text-slate-950">Sign in to the platform</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Sign in to manage students, records, and follow-up scores.
            </p>

            @if (session('success'))
                <x-alert type="success" :message="session('success')" class="mt-6" />
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="text-sm font-medium text-slate-700">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-teal-600" required autofocus>
                    <x-input-error field="email" />
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                    <input id="password" name="password" type="password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-teal-600" required>
                    <x-input-error field="password" />
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-teal-700 focus:ring-teal-700">
                    Remember me
                </label>

                <x-button type="submit" class="w-full">Sign in</x-button>
            </form>

        </div>
    </div>
</body>
</html>
