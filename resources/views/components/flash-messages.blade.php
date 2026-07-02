@if (session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-md px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-red-50 text-red-800 border border-red-200 rounded-md px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    </div>
@endif
