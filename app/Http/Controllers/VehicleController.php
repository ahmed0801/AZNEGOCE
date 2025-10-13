<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\DeliveryNote;
use App\Models\SalesReturn;
use PDF; // barryvdh/laravel-dompdf

class VehicleController extends Controller
{
public function index($vehicleId)
{
    $vehicle = Vehicle::findOrFail($vehicleId);

    // Charger les BL avec leurs lignes et client
    $deliveryNotes = DeliveryNote::with(['customer', 'lines.item'])
                        ->where('vehicle_id', $vehicleId)
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('vehicles.history', compact('deliveryNotes', 'vehicle'));
}


public function pdf($vehicleId)
{
    $vehicle = Vehicle::findOrFail($vehicleId);

    // Charger les BL avec client et lignes
    $deliveryNotes = DeliveryNote::with(['customer', 'lines.item', 'vehicle', 'salesOrder'])
                        ->where('vehicle_id', $vehicleId)
                        ->orderBy('created_at', 'desc')
                        ->get();

    // Infos de l'entreprise
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

    $pdf = Pdf::loadView('vehicles.history_pdf', compact('deliveryNotes', 'vehicle', 'company'));
    return $pdf->stream("historique_vehicule_{$vehicle->id}.pdf");
}

}