<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

/**
 * Création d'un véhicule en saisie libre (sans TecDoc) : tout le texte saisi est stocké dans « immatriculation »
 * (ex. « AB-123-CD » ou « Polo 6 modèle 2012 »). Les champs TecDoc reçoivent la même valeur « inconnu » (-1)
 * que pour les véhicules créés depuis une plaque.
 */
class VehicleFreeController extends Controller
{
    public function store(Request $request, $customer)
    {
        $data = $request->validate([
            'license_plate' => 'required|string|max:50',
        ], [
            'license_plate.required' => 'Saisissez une immatriculation ou une description du véhicule.',
            'license_plate.max'      => 'Maximum 50 caractères.',
        ]);

        $customer = Customer::findOrFail($customer);
        $label = mb_strtoupper(trim(preg_replace('/\s+/u', ' ', $data['license_plate'])));
        if ($label === '') {
            return response()->json(['success' => false, 'message' => 'Saisie vide.'], 422);
        }

        // même client + même texte = même véhicule (évite les doublons)
        $vehicle = Vehicle::firstOrCreate(
            ['customer_id' => $customer->id, 'license_plate' => $label],
            [
                'brand_id' => -1, 'brand_name' => '',
                'model_id' => -1, 'model_name' => '',
                'engine_id' => -1, 'engine_description' => '',
                'linkage_target_id' => -1,
            ]
        );

        return response()->json([
            'success' => true,
            'vehicle' => ['id' => $vehicle->id, 'text' => $vehicle->license_plate],
        ]);
    }
}
