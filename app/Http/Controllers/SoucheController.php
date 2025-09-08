<?php

namespace App\Http\Controllers;

use App\Models\Souche;
use Illuminate\Http\Request;

class SoucheController extends Controller
{
    public function index()
    {
        $souches = Souche::all();
        return view('souches',compact('souches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:souches',
            'name' => 'required',
            'type' => 'required|unique:souches,type',
            'prefix' => 'nullable',
            'suffix' => 'nullable',
            'number_length' => 'required|integer|min:1|max:10',
            'last_number' => 'required',
        ]);

        Souche::create($validated);

        return redirect()->back()->with('success', 'Souche créée avec succès.');
    }


        public function update(Request $request, $id)
    {
        $souche = Souche::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:souches,code,' . $souche->id,
            'name' => 'required',
            'number_length' => 'required|integer|min:1',
            'last_number' => 'required',
        ]);

        $souche->update($request->all());

        return redirect()->back()->with('success', 'Souche mise à jour.');
    }

    public function destroy($id)
    {
        Souche::destroy($id);
        return redirect()->back()->with('success', 'Souche supprimée.');
    }

    /**
     * Génère le prochain code basé sur la souche d’un type donné.
     */
    public static function generateNextCode($type)
    {
        $souche = Souche::where('type', $type)->first();

        if (!$souche) {
            return null; // ou throw une exception
        }

        // Incrémentation
        $souche->last_number += 1;
        $souche->save();

        // Format du code : [prefixe][000001][suffixe]
        $number = str_pad($souche->last_number, $souche->number_length, '0', STR_PAD_LEFT);
        return $souche->prefix . $number . $souche->suffix;
    }
    
}
