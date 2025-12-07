<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        
        $accounts = BankAccount::where('company_id', $currentCompany->id)
            ->with(['currency'])
            ->get();

        $currencies = \App\Models\Currency::all();
            
        return view('bank.index', compact('accounts', 'currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'currency_id' => 'required|exists:currencies,id',
            'initial_balance' => 'required|numeric|min:0'
        ]);

        $currentCompany = Auth::user()->companies->first();

        BankAccount::create([
            'company_id' => $currentCompany->id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'currency_id' => $request->currency_id,
            'current_balance' => $request->initial_balance,
            'holder_name' => $request->holder_name
        ]);

        return back()->with('success', 'Cuenta bancaria registrada.');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        $account = BankAccount::findOrFail($request->bank_account_id);

        DB::transaction(function() use ($account, $request) {
            BankTransaction::create([
                'bank_account_id' => $account->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'reference' => $request->reference,
                'description' => $request->description,
            ]);

            // Update balance
            if ($request->type === 'deposit') {
                $account->increment('current_balance', $request->amount);
            } else {
                $account->decrement('current_balance', $request->amount);
            }
        });

        return back()->with('success', 'Movimiento registrado.');
    }

    public function show($id)
    {
        $currentCompany = Auth::user()->companies->first();
        
        $accounts = BankAccount::where('company_id', $currentCompany->id)
            ->with(['currency'])
            ->get();
            
        $currencies = \App\Models\Currency::all();

        $account = BankAccount::with(['transactions' => function($q) {
            $q->latest('transaction_date');
        }])->where('company_id', $currentCompany->id)->findOrFail($id);

        return view('bank.show', compact('account', 'accounts', 'currencies'));
    }
    public function toggleReconciled($id)
    {
        $transaction = BankTransaction::findOrFail($id);
        
        $transaction->is_reconciled = !$transaction->is_reconciled;
        $transaction->reconciled_at = $transaction->is_reconciled ? now() : null;
        $transaction->save();

        return back()->with('success', 'Estado de conciliación actualizado.');
    }
}
