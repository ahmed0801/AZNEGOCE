<?php

return [
    /*
     | Sous-domaine du portail B2B, par exemple « b2b.votre-domaine.com » (sans https://).
     | À définir dans .env :  PORTAL_DOMAIN=b2b.votre-domaine.com
     | Laisser vide en développement local : le portail est alors disponible sous /portail.
     */
    'domain' => env('PORTAL_DOMAIN'),

    // Validation de commande par les clients (mettre PORTAL_CHECKOUT=false dans .env pour la couper).
    'checkout_enabled' => env('PORTAL_CHECKOUT', true),

    // Nom du cookie de session du portail (distinct de celui de l'ERP).
    'session_cookie' => env('PORTAL_SESSION_COOKIE', 'az_portal_session'),
];
