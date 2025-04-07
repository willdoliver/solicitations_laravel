<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('App\Http\Middleware\VerificarLogin');
    }

    public function index(Request $request)
    {
        if (Auth::user()->name != 'Admin') {
            return redirect()->route('solicitations.index');
        }

        $query = Log::with('user');
        
        // filter
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('action', 'like', "%$searchTerm%")
                  ->orWhere('description', 'like', "%$searchTerm%")
                  ->orWhere('solicitation_id', '=', "$searchTerm");
            });
        }

        $logs = $query->get();

        return view('logs.index', compact('logs'));
    }
}
