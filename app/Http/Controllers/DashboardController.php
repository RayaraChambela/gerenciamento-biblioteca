<?php

namespace App\Http\Controllers;

use App\Enums\EmprestimoStatus;
use App\Models\Emprestimo;
use App\Models\Livro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $metrics = [
                'total_livros' => Livro::count(),
                'exemplares_disponiveis' => Livro::sum('quantidade_disponivel'),
                'emprestimos_ativos' => Emprestimo::where('status', EmprestimoStatus::Emprestado)->count(),
                'emprestimos_atrasados' => Emprestimo::atrasados()->count(),
            ];

            $ultimosEmprestimos = Emprestimo::with(['livro', 'leitor'])
                ->latest('data_emprestimo')
                ->limit(5)
                ->get();

            return view('dashboard', compact('metrics', 'ultimosEmprestimos'));
        }

        $meusEmprestimos = Emprestimo::with('livro')
            ->where('user_id', $user->id)
            ->latest('data_emprestimo')
            ->limit(5)
            ->get();

        $metrics = [
            'meus_emprestimos_ativos' => Emprestimo::where('user_id', $user->id)
                ->where('status', EmprestimoStatus::Emprestado)
                ->count(),
            'meus_emprestimos_atrasados' => Emprestimo::where('user_id', $user->id)->atrasados()->count(),
            'livros_no_acervo' => Livro::count(),
        ];

        return view('dashboard', compact('metrics', 'meusEmprestimos'));
    }
}
