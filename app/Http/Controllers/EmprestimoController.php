<?php

namespace App\Http\Controllers;

use App\Enums\EmprestimoStatus;
use App\Enums\UserRole;
use App\Http\Requests\StoreEmprestimoRequest;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $emprestimos = Emprestimo::with(['livro', 'leitor'])
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($status === 'atrasado', fn ($query) => $query->atrasados())
            ->when(in_array($status, ['emprestado', 'devolvido'], true), fn ($query) => $query->where('status', $status))
            ->latest('data_emprestimo')
            ->paginate(10)
            ->withQueryString();

        return view('emprestimos.index', compact('emprestimos', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $livros = Livro::where('quantidade_disponivel', '>', 0)->orderBy('titulo')->get();
        $leitores = User::where('role', UserRole::Leitor)->orderBy('name')->get();

        return view('emprestimos.create', compact('livros', 'leitores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmprestimoRequest $request): RedirectResponse
    {
        $livro = Livro::findOrFail($request->validated('livro_id'));

        if (! $livro->possuiExemplarDisponivel()) {
            return back()->withInput()->with('error', 'Este livro não possui exemplares disponíveis no momento.');
        }

        Emprestimo::create([
            'livro_id' => $livro->id,
            'user_id' => $request->validated('user_id'),
            'data_emprestimo' => now()->toDateString(),
            'data_prevista_devolucao' => now()->addDays(14)->toDateString(),
            'status' => EmprestimoStatus::Emprestado,
        ]);

        $livro->reservarExemplar();

        return redirect()->route('emprestimos.index')->with('success', 'Empréstimo registrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Emprestimo $emprestimo): View
    {
        abort_unless(
            $request->user()->isAdmin() || $emprestimo->user_id === $request->user()->id,
            403
        );

        $emprestimo->load('livro', 'leitor');

        return view('emprestimos.show', compact('emprestimo'));
    }

    public function devolver(Emprestimo $emprestimo): RedirectResponse
    {
        if ($emprestimo->status === EmprestimoStatus::Devolvido) {
            return back()->with('error', 'Este empréstimo já foi devolvido.');
        }

        $emprestimo->registrarDevolucao();

        return redirect()->route('emprestimos.index')->with('success', 'Devolução registrada com sucesso.');
    }
}
