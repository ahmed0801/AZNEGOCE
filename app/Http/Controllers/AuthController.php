<?php

namespace App\Http\Controllers;

use App\Models\Arrivage;
use App\Models\Contact;
use App\Models\Log;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\User;
use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }




    
        // Formulaire de connexion pour les administrateurs
        public function loginFormAdmin()
        {
            return view('admin.loginFormAdmin');
        }



         // Processus de connexion pour les administrateurs
   public function loginAdmin(Request $request)
         {
             $request->validate([
                 'login' => 'required|string',
                 'password' => 'required',
             ]);
         
             // Vérifier si l'entrée est un email ou un nom d'utilisateur
             $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
         
             $credentials = [$loginField => $request->login, 'password' => $request->password];
         
             if (Auth::attempt($credentials)) {
                 return redirect()->route('admin.dashboard');
             }
         
             // En cas d'échec d'authentification
             return back()->withErrors(['login' => 'Email ou mot de passe incorrect.']);
         }



    // Déconnexion pour les administrateurs
    public function logoutAdmin()
    {
        Auth::logout();
        return redirect()->route('login.form.admin');
    }

    // Tableau de bord de l'administrateur
    public function adminDashboard()
    {
        $brands = [];

        try {
            $response = Http::timeout(3) // ⏱ max 3 secondes d'attente
                ->withHeaders([
                    'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
                ])
                ->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
                    "getLinkageTargets" => [
                        "provider" => env('TECDOC_PROVIDER_ID'),
                        "linkageTargetCountry" => "TN",
                        "lang" => "fr",
                        "linkageTargetType" => "P",
                        "perPage" => 0,
                        "page" => 1,
                        "includeMfrFacets" => true
                    ]
                ]);
    
            $data = $response->json();
    
            if (isset($data['mfrFacets']['counts'])) {
                $brands = $data['mfrFacets']['counts'];
            }
    
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Pas d'internet ou domaine inaccessible : ignorer et continuer avec $brands = []
            \Log::warning("Connexion échouée vers TecDoc : " . $e->getMessage());
        } catch (\Exception $e) {
            // Autres erreurs : on log mais on n'interrompt pas le fonctionnement
            \Log::error("Erreur HTTP TecDoc : " . $e->getMessage());
        }


        return view('admin.dashboard',compact('brands'));
    }




   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'codevendeur' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,vendeur,master,livreur,comptable,preparateur',
            'permissions' => 'nullable|array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'codevendeur' => $request->codevendeur,
            'role' => $request->role,
        ]);

        $user->permissions()->sync($request->permissions ?? []);

        return redirect()->back()->with('success', 'Utilisateur créé avec succès.');
    }
   
    



public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable',
        'role' => 'nullable|string',
        'permissions' => 'array'
    ]);

    $user->update([
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'],
    ]);

    if ($data['password']) {
        $user->password = bcrypt($data['password']);
        $user->save();
    }

    $user->permissions()->sync($data['permissions'] ?? []);

    return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès');
}




 public function index()
    {
        // Récupérer tous les utilisateurs avec leurs permissions
        $users = User::with('permissions')->orderBy('name')->get();

        // Récupérer la liste complète des permissions pour le formulaire
        $permissions = Permission::orderBy('label')->get();

        return view('userlist', compact('users', 'permissions'));
    }




        public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    


    
}
