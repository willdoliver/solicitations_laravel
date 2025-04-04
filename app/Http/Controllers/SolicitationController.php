<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Solicitation;
use Illuminate\Validation\Rule;

class SolicitationController extends Controller
{
    private $STATUS = [
        'aberta' => 'Aberta',
        'em_andamento' => 'Em Andamento',
        'concluida' => 'Concluída',
    ];

    public function __construct()
    {
        $this->middleware('App\Http\Middleware\VerificarLogin');
    }

    public function index()
    {
        // $solicitations = Solicitation::all();
        $solicitations = Solicitation::with('user')->get();

        foreach ($solicitations as $solicitation) {
            $solicitation->status = $STATUS[$solicitation->status] ?? $solicitation->status;
        }

        return view('solicitations.index', compact('solicitations'));
    }

    public function create()
    {
        return view('solicitations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'category' => ['required', Rule::in(['TI','Suprimentos','RH'])],
        ], [
            'title.required' => 'O Título é obrigatório',
            'category.required' => 'O campo Categoria é obrigatório',
        ]);

        $solicitacao = new Solicitation();
        $solicitacao->title = $validatedData['title'];
        $solicitacao->description = $validatedData['description'];
        $solicitacao->category = $validatedData['category'];
        $solicitacao->status = "aberta";
        $solicitacao->user_id = Auth::user()->id;
        $solicitacao->created_at = now();
        $solicitacao->save();

        session()->flash('success', 'Solicitação criada com sucesso!');
        return redirect()->route('solicitations.index');
    }

    public function show($id)
    {
        $solicitation = Solicitation::find($id);

        return view('solicitations.show', compact('solicitation'));
    }

    public function edit($id)
    {
        $solicitation = Solicitation::find($id);

        return view('solicitations.edit', compact('solicitation'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['aberta','em_andamento','concluida'])],
            'category' => ['required', Rule::in(['TI','Suprimentos','RH'])],
        ], [
            'title.required' => 'O Título é obrigatório',
            'status.required' => 'O Status é obrigatório',
            'category.required' => 'O campo Categoria é obrigatório',
        ]);

        $solicitation = Solicitation::find($id);

        if ($solicitation['status'] == 'concluida') {
            session()->flash('error', 'Não é possível editar uma solicitação já concluída');
        } else {
            $solicitation->title = $validatedData['title'];
            $solicitation->description = $validatedData['description'];
            $solicitation->category = $validatedData['category'];
            $solicitation->status = $validatedData['status'];
            $solicitation->save();

            session()->flash('success', 'Solicitação atualizada com sucesso!');
        }

        return redirect()->route('solicitations.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['aberta','em_andamento','concluida'])],
        ], [
            'status.required' => 'O Status é obrigatório',
        ]);

        $solicitation = Solicitation::find($id);

        if ($solicitation['status'] == 'concluida') {
            $message = 'Não é possível editar uma solicitação já concluída';
            session()->flash('error', $message);
        } else {
            $solicitation->status = $validatedData['status'];
            $solicitation->save();

            $message = 'Status atualizado com sucesso!';
            session()->flash('success', $message);
        }

        return response()->json(['message' => $message]);
    }

    public function destroy($id)
    {
        $solicitation = Solicitation::find($id);
        $solicitation->delete();

        session()->flash('success', 'Solicitação excluída com sucesso!');
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('solicitations.index')
            ]);
        } else {
            return redirect()->route('solicitations.index');
        }
    }
}