<?php

namespace App\Http\Controllers;

use App\Models\Solicitation;
use App\Services\CsvExportService;
use App\Services\LogService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index(Request $request)
    {
        $solicitations = $this->getSolicitations($request);

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
            'category.in' => 'A Categoria selecionada é inválida',
        ]);

        $solicitation = new Solicitation();
        $solicitation->title = $validatedData['title'];
        $solicitation->description = $validatedData['description'] ?? null;
        $solicitation->category = $validatedData['category'];
        $solicitation->status = "aberta";
        $solicitation->user_id = Auth::user()->id;
        $solicitation->created_at = now();
        $solicitation->save();

        LogService::log(
            'Solicitação criada',
            'Dados: ' . json_encode( $solicitation->toArray()),
            $solicitation->id
        );

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $solicitation->id,
                'message' => 'Solicitação criada com sucesso!',
            ], 201);
        } else {
            session()->flash('success', 'Solicitação criada com sucesso!');
            return redirect()->route('solicitations.index');
        }
    }

    public function show($id)
    {
        try {
            $solicitation = Solicitation::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Solicitação não encontrada');
            return redirect()->route('solicitations.index');
        }

        return view('solicitations.show', compact('solicitation'));
    }

    public function edit($id)
    {
        try {
            $solicitation = Solicitation::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Solicitação não encontrada');
            return redirect()->route('solicitations.index');
        }

        return view('solicitations.edit', compact('solicitation'));
    }

    public function update(Request $request, $id)
    {
        try {
            $solicitation = Solicitation::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Solicitação não encontrada'], 404);
            } else {
                session()->flash('error', 'Solicitação não encontrada');
                return redirect()->route('solicitations.index');
            }
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['aberta','em_andamento','concluida'])],
            'category' => ['required', Rule::in(['TI','Suprimentos','RH'])],
        ], [
            'title.required' => 'O Título é obrigatório',
            'status.required' => 'O Status é obrigatório',
            'status.in' => 'O Status selecionado é inválido',
            'category.required' => 'O campo Categoria é obrigatório',
            'category.in' => 'A Categoria selecionada é inválida',
        ]);

        if ($solicitation['status'] === 'concluida') {
            $message = 'Não é possível editar uma solicitação já concluída';
            session()->flash('error', $message);
        } else {
            $solicitation->title = $validatedData['title'];
            $solicitation->description = $validatedData['description'];
            $solicitation->category = $validatedData['category'];
            $solicitation->status = $validatedData['status'];
            $solicitation->save();

            $message = 'Solicitação atualizada com sucesso!';
            session()->flash('success', $message);
        }

        LogService::log(
            'Solicitação atualizada',
            json_encode($solicitation->toArray()),
            $solicitation->id
        );

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $solicitation->id,
                'message' => $message,
            ], 201);
        } else {
            return redirect()->route('solicitations.index');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $solicitation = Solicitation::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Solicitação não encontrada'], 404);
            } else {
                session()->flash('error', 'Solicitação não encontrada');
                return redirect()->route('solicitations.index');
            }
        }

        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['aberta','em_andamento','concluida'])],
        ], [
            'status.required' => 'O Status é obrigatório',
        ]);

        if ($solicitation['status'] === 'concluida') {
            $message = 'Não é possível editar uma solicitação já concluída';
            session()->flash('error', $message);
        } else {
            $oldStatus = $solicitation->status;
            $solicitation->status = $validatedData['status'];
            $solicitation->save();

            LogService::log(
                'Solicitação atualizada',
                'Status antigo: ' . $oldStatus . ' / Novo status: ' . $solicitation->status,
                $solicitation->id
            );

            $message = 'Status atualizado com sucesso!';
            session()->flash('success', $message);
        }

        return response()->json(['message' => $message]);
    }

    public function destroy($id)
    {
        try {
            $solicitation = Solicitation::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Solicitação não encontrada'], 404);
            } else {
                session()->flash('error', 'Solicitação não encontrada');
                return redirect()->route('solicitations.index');
            }
        }

        $solicitation->delete();

        LogService::log(
            'Solicitação removida',
            'Solicitação removida pelo usuário',
            $id
        );

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

    public function exportCsv(Request $request)
    {
        $solicitations = $this->getSolicitations($request);

        return CsvExportService::export($solicitations);
    }

    private function getSolicitations(Request $request)
    {
        $query = Solicitation::with('user');

        // filter
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%$searchTerm%")
                  ->orWhere('description', 'like', "%$searchTerm%");
            });
        }

        // order
        $query->orderBy('created_at', 'desc');

        $solicitations = $query->get();

        foreach ($solicitations as $solicitation) {
            $solicitation->status = $STATUS[$solicitation->status] ?? $solicitation->status;
        }

        return $solicitations;
    }

}
