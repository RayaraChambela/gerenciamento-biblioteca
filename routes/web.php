<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categorias', CategoriaController::class)
        ->parameters(['categorias' => 'categoria'])
        ->except(['show'])
        ->middleware('role:admin');

    // "create" precisa ser registrada antes de "{livro}" para não colidir com o wildcard do show.
    Route::middleware('role:admin')->group(function () {
        Route::get('livros/create', [LivroController::class, 'create'])->name('livros.create');
        Route::post('livros', [LivroController::class, 'store'])->name('livros.store');
    });
    Route::get('livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('livros/{livro}', [LivroController::class, 'show'])->name('livros.show');
    Route::middleware('role:admin')->group(function () {
        Route::get('livros/{livro}/edit', [LivroController::class, 'edit'])->name('livros.edit');
        Route::put('livros/{livro}', [LivroController::class, 'update'])->name('livros.update');
        Route::delete('livros/{livro}', [LivroController::class, 'destroy'])->name('livros.destroy');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('emprestimos/create', [EmprestimoController::class, 'create'])->name('emprestimos.create');
        Route::post('emprestimos', [EmprestimoController::class, 'store'])->name('emprestimos.store');
        Route::patch('emprestimos/{emprestimo}/devolver', [EmprestimoController::class, 'devolver'])->name('emprestimos.devolver');
    });
    Route::get('emprestimos', [EmprestimoController::class, 'index'])->name('emprestimos.index');
    Route::get('emprestimos/{emprestimo}', [EmprestimoController::class, 'show'])->name('emprestimos.show');
});

require __DIR__.'/auth.php';
