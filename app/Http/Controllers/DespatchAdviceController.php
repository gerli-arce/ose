<?php

namespace App\Http\Controllers;

use App\Models\DespatchAdvice;
use App\Models\DespatchAdviceItem;
use App\Models\DespatchTransferReason;
use App\Models\TransportModality;
use App\Models\Transporter;
use App\Models\Vehicle;
use App\Models\DocumentSeries;
use App\Models\Ubigeo;
use App\Models\Product;
use App\Jobs\SendDespatchToSunatJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DespatchAdviceController extends Controller
{
    /**
     * Listado de guías de remisión
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');

        $query = DespatchAdvice::with(['series', 'transferReason', 'transportModality'])
            ->forCompany($companyId)
            ->orderBy('issue_date', 'desc')
            ->orderBy('number', 'desc');

        // Filtros
        if ($request->filled('status')) {
            $query->where('sunat_status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        $despatches = $query->paginate(15);

        return view('despatch.index', compact('despatches'));
    }

    /**
     * Formulario de creación
     */
    public function create(Request $request)
    {
        $companyId = session('current_company_id');

        // Series de tipo guía (T001, T002, etc.)
        $series = DocumentSeries::where('company_id', $companyId)
            ->where('prefix', 'like', 'T%')
            ->active()
            ->get();

        // Si no hay series, crear una por defecto
        if ($series->isEmpty()) {
            DocumentSeries::create([
                'company_id' => $companyId,
                'document_type_id' => 9, // Guía de remisión
                'prefix' => 'T001',
                'current_number' => 0,
                'active' => true,
            ]);
            $series = DocumentSeries::where('company_id', $companyId)
                ->where('prefix', 'like', 'T%')
                ->active()
                ->get();
        }

        $transferReasons = DespatchTransferReason::active()->orderBy('code')->get();
        $modalities = TransportModality::active()->get();
        $transporters = Transporter::forCompany($companyId)->active()->get();
        $vehicles = Vehicle::forCompany($companyId)->active()->get();
        $departments = Ubigeo::getDepartments();
        $products = Product::where('company_id', $companyId)->where('active', true)->get();

        return view('despatch.create', compact(
            'series',
            'transferReasons',
            'modalities',
            'transporters',
            'vehicles',
            'departments',
            'products'
        ));
    }

    /**
     * Guardar guía de remisión
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|exists:document_series,id',
            'issue_date' => 'required|date',
            'transfer_date' => 'required|date',
            'transfer_reason_id' => 'required|exists:despatch_transfer_reasons,id',
            'transport_modality_id' => 'required|exists:transport_modalities,id',
            'gross_weight' => 'required|numeric|min:0.01',
            'package_count' => 'required|integer|min:1',
            'origin_address' => 'required|string|max:500',
            'origin_ubigeo_id' => 'required|exists:ubigeos,id',
            'destination_address' => 'required|string|max:500',
            'destination_ubigeo_id' => 'required|exists:ubigeos,id',
            'recipient_document_type' => 'nullable|string|max:10',
            'recipient_document_number' => 'nullable|string|max:20',
            'recipient_name' => 'nullable|string|max:255',
            'transporter_id' => 'nullable|exists:transporters,id',
            'driver_document_type' => 'nullable|string|max:10',
            'driver_document_number' => 'nullable|string|max:20',
            'driver_name' => 'nullable|string|max:255',
            'driver_license' => 'nullable|string|max:50',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'observation' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_code' => 'required|string|max:10',
        ]);

        $companyId = session('current_company_id');

        DB::beginTransaction();

        try {
            // Obtener siguiente número
            $series = DocumentSeries::findOrFail($validated['series_id']);
            $nextNumber = $series->current_number + 1;

            // Crear guía
            $despatch = DespatchAdvice::create([
                'company_id' => $companyId,
                'series_id' => $validated['series_id'],
                'number' => $nextNumber,
                'issue_date' => $validated['issue_date'],
                'transfer_date' => $validated['transfer_date'],
                'transfer_reason_id' => $validated['transfer_reason_id'],
                'transport_modality_id' => $validated['transport_modality_id'],
                'gross_weight' => $validated['gross_weight'],
                'package_count' => $validated['package_count'],
                'origin_address' => $validated['origin_address'],
                'origin_ubigeo_id' => $validated['origin_ubigeo_id'],
                'destination_address' => $validated['destination_address'],
                'destination_ubigeo_id' => $validated['destination_ubigeo_id'],
                'recipient_document_type' => $validated['recipient_document_type'],
                'recipient_document_number' => $validated['recipient_document_number'],
                'recipient_name' => $validated['recipient_name'],
                'transporter_id' => $validated['transporter_id'],
                'driver_document_type' => $validated['driver_document_type'],
                'driver_document_number' => $validated['driver_document_number'],
                'driver_name' => $validated['driver_name'],
                'driver_license' => $validated['driver_license'],
                'vehicle_id' => $validated['vehicle_id'],
                'observation' => $validated['observation'],
                'status' => DespatchAdvice::STATUS_DRAFT,
                'sunat_status' => DespatchAdvice::SUNAT_PENDING,
            ]);

            // Crear items
            foreach ($validated['items'] as $itemData) {
                DespatchAdviceItem::create([
                    'despatch_advice_id' => $despatch->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_code' => $itemData['unit_code'],
                ]);
            }

            // Actualizar número en serie
            $series->update(['current_number' => $nextNumber]);

            // Enviar a SUNAT si se solicitó
            if ($request->boolean('send_to_sunat')) {
                SendDespatchToSunatJob::dispatch($despatch->id);
            }

            DB::commit();

            return redirect()
                ->route('despatch.show', $despatch->id)
                ->with('success', "Guía {$despatch->full_number} creada exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la guía: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Ver detalle de guía
     */
    public function show(DespatchAdvice $despatch)
    {
        $companyId = session('current_company_id');

        if ($despatch->company_id != $companyId) {
            abort(403);
        }

        $despatch->load([
            'company',
            'series',
            'items.product',
            'transferReason',
            'transportModality',
            'originUbigeo',
            'destinationUbigeo',
            'transporter',
            'vehicle',
            'salesDocument',
            'eDocument',
        ]);

        return view('despatch.show', compact('despatch'));
    }

    /**
     * Reenviar a SUNAT
     */
    public function resendToSunat(DespatchAdvice $despatch)
    {
        $companyId = session('current_company_id');

        if ($despatch->company_id != $companyId) {
            abort(403);
        }

        SendDespatchToSunatJob::dispatch($despatch->id);

        return back()->with('success', 'Guía enviada a cola de procesamiento SUNAT.');
    }
}
