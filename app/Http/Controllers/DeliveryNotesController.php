<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Customer;
use App\Models\CompanyInformation;
use App\Exports\DeliveryNoteExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Http\Request;
class DeliveryNotesController extends Controller
{
    public function list(Request $request)
    {
$query = DeliveryNote::with(['customer', 'salesOrder', 'lines.item'])
    ->orderBy('delivery_notes.updated_at', 'desc');

        if ($request->filled('numclient')) {
            $query->where('delivery_notes.numclient', $request->numclient);
        }

        if ($request->filled('status')) {
            $query->where('delivery_notes.status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_notes.delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_notes.delivery_date', '<=', $request->date_to);
        }

        $deliveryNotes = $query->get();
        $customers = Customer::orderBy('name')->get();

        return view('delivery_notes.list', compact('deliveryNotes', 'customers'));
    }

 public function exportSingle($id)
    {
        $deliveryNote = DeliveryNote::with(['salesOrder', 'lines.item'])
            ->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')
            ->select('delivery_notes.*', 'customers.name as customer_name')
            ->where('delivery_notes.id', $id)
            ->firstOrFail();

        return Excel::download(new DeliveryNoteExport($deliveryNote), 'bon_livraison_' . $deliveryNote->numdoc . '.xlsx');
    }

   public function printSingle($id)
    {
        $deliveryNote = DeliveryNote::with(['salesOrder', 'lines.item'])
            ->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')
            ->select('delivery_notes.*', 'customers.name as customer_name')
            ->where('delivery_notes.id', $id)
            ->firstOrFail();

        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
        $barcode = 'data:image/png;base64,' . base64_encode(
            $generator->getBarcode($deliveryNote->numdoc, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('pdf.delivery_note', compact('deliveryNote', 'company', 'barcode'));
        return $pdf->stream("bon_livraison_{$deliveryNote->numdoc}.pdf");
    }
}