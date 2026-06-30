<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Orientation Campus' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="app-shell lg:flex">
        <aside class="glass-panel border-b border-white/60 px-6 py-6 lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-teal-700">Orientation Campus</p>
                    <h1 class="mt-2 text-2xl font-semibold text-slate-900">Student pathway tracking</h1>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-white/70 bg-white/70 p-4">
                <p class="text-sm text-slate-500">Signed in as</p>
                <p class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-sm uppercase tracking-wide text-teal-700">{{ auth()->user()->role }}</p>
            </div>

            <nav class="mt-8 space-y-2">
                @php
                    $links = [
                        ['label' => 'Dashboard', 'route' => 'dashboard'],
                        ['label' => 'Students', 'route' => 'candidates.index'],
                        ['label' => 'Programs', 'route' => 'programs.index'],
                        ['label' => 'Admission history', 'route' => 'historical-admissions.index'],
                        ['label' => 'Follow-up scores', 'route' => 'predictions.index'],
                    ];
                @endphp

                @foreach ($links as $link)
                    @php $active = request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route'])); @endphp
                    <a
                        href="{{ route($link['route']) }}"
                        class="{{ $active ? 'bg-teal-700 text-white shadow-lg shadow-teal-900/20' : 'text-slate-600 hover:bg-white/80 hover:text-slate-900' }} flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-900 hover:text-slate-900">
                    Sign out
                </button>
            </form>

        </aside>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-10">
            @if (session('success'))
                <x-alert type="success" :message="session('success')" class="mb-6" />
            @endif

            @if ($errors->any())
                <x-alert type="error" class="mb-6">
                    <div class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </x-alert>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
