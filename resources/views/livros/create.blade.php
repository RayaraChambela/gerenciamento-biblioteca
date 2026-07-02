<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900">{{ __('Novo livro') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('livros.store') }}">
                    @csrf
                    @include('livros._form', ['categorias' => $categorias])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
