<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashRegisterSession;
use App\Models\CashMovement;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    // List Cash Registers (Dashboard for Cash)
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        
        $registers = CashRegister::where('company_id', $currentCompany->id)
            ->with(['branch', 'currentSession.user'])
            ->get();
            
        return view('cash.index', compact('registers'));
    }

    // Open a new session
    public function openSession(Request $request)
    {
        $request->validate([
            'cash_register_id' => 'required|exists:cash_registers,id',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $register = CashRegister::findOrFail($request->cash_register_id);
        
        if($register->status === 'open') {
             return back()->with('error', 'Caja ya está abierta.');
        }

        DB::transaction(function() use ($register, $request) {
            CashRegisterSession::create([
                'cash_register_id' => $register->id,
                'user_id' => Auth::id(),
                'opened_at' => now(),
                'opening_balance' => $request->opening_balance,
                'status' => 'open'
            ]);
            
            $register->status = 'open';
            $register->save();
        });

        return back()->with('success', 'Caja aperturada correctamente.');
    }

    // Close session
    public function closeSession(Request $request, $sessionId)
    {
        $request->validate([
            'closing_balance' => 'required|numeric|min:0',
        ]);

        $session = CashRegisterSession::findOrFail($sessionId);
        
        if($session->status === 'closed') {
             return back()->with('error', 'Sesión ya está cerrada.');
        }

        // Calculate expected balance
        $income = $session->movements()->where('type', 'income')->sum('amount');
        $expense = $session->movements()->where('type', 'expense')->sum('amount');
        $expected = $session->opening_balance + $income - $expense;

        DB::transaction(function() use ($session, $request, $expected) {
            $session->update([
                'closed_at' => now(),
                'closing_balance' => $request->closing_balance,
                'calculated_balance' => $expected,
                'status' => 'closed',
                 'observations' => $request->observations
            ]);

            $session->cashRegister->update(['status' => 'closed']);
        });

        return back()->with('success', 'Caja cerrada correctamente.');
    }

    // Show session details (movements)
    public function show($id)
    {
        $register = CashRegister::findOrFail($id);
        // Get current or last session
        $session = $register->currentSession ?? $register->sessions()->latest()->first();

        // If no sessions ever
        if(!$session) {
             return view('cash.show', compact('register', 'session'));
        }

        $session->load(['movements.session', 'user']);
        
        $income = $session->movements->where('type', 'income')->sum('amount');
        $expense = $session->movements->where('type', 'expense')->sum('amount');
        $currentBalance = $session->opening_balance + $income - $expense;

        return view('cash.show', compact('register', 'session', 'currentBalance', 'income', 'expense'));
    }

    // Store movement
    public function storeMovement(Request $request) 
    {
         $request->validate([
            'cash_register_session_id' => 'required|exists:cash_register_sessions,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $session = CashRegisterSession::findOrFail($request->cash_register_session_id);

        if($session->status !== 'open') {
             return back()->with('error', 'No se pueden agregar movimientos a una caja cerrada.');
        }

        $data = $request->all();
        if($request->filled('reference')) {
            $data['description'] .= ' (Ref: ' . $request->reference . ')';
        }

        CashMovement::create($data);

        return back()->with('success', 'Movimiento registrado.');
    }
}
