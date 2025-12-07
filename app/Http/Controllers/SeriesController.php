<?php

namespace App\Http\Controllers;

use App\Models\DocumentSeries;
use App\Models\DocumentType;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        if (!$currentCompany) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        // Get series grouped by Branch
        $series = DocumentSeries::where('company_id', $currentCompany->id)
            ->with(['branch', 'documentType'])
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'branch' => $s->branch ? $s->branch->name : 'N/A', // Null safety
                    'type' => $s->documentType ? $s->documentType->name : 'N/A', // Null safety
                    'prefix' => $s->prefix,
                    'current_number' => $s->current_number
                ];
            });

        $documentTypes = DocumentType::all();
        $branches = Branch::where('company_id', $currentCompany->id)->get();

        return view('sales.series.index', compact('series', 'documentTypes', 'branches'));
    }

    public function store(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'document_type_id' => 'required|exists:document_types,id',
            'prefix' => 'required|string|max:4|unique:document_series,prefix',
            'current_number' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DocumentSeries::create([
            'company_id' => $currentCompany->id,
            'branch_id' => $request->branch_id,
            'document_type_id' => $request->document_type_id,
            'prefix' => strtoupper($request->prefix),
            'current_number' => $request->current_number,
        ]);

        return response()->json(['success' => true, 'message' => 'Serie creada exitosamente.', 'redirect_url' => route('series.index')]);
    }

    public function edit($id)
    {
        $series = DocumentSeries::findOrFail($id);
        return response()->json($series);
    }

    public function update(Request $request, $id)
    {
        $series = DocumentSeries::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'prefix' => 'required|string|max:4|unique:document_series,prefix,' . $id,
            'current_number' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $series->update([
            'prefix' => strtoupper($request->prefix),
            'current_number' => $request->current_number,
        ]);

        return response()->json(['success' => true, 'message' => 'Serie actualizada.', 'redirect_url' => route('series.index')]);
    }

    public function destroy($id)
    {
        $series = DocumentSeries::findOrFail($id);
        $series->delete();
        return response()->json(['success' => true, 'message' => 'Serie eliminada.']);
    }
}
