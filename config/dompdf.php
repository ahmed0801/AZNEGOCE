<?php

return [

    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [

        // === CHEMINS ===
        "font_dir"   => sys_get_temp_dir(),
"font_cache" => sys_get_temp_dir(),
        "temp_dir" => sys_get_temp_dir(),
        "chroot" => realpath(base_path()),

        // === SÉCURITÉ & PERFORMANCES ===
        "enable_font_subsetting" => true,        // CRUCIAL : réduit la taille des polices ×10
        "enable_php" => false,                   // Sécurité + gain mémoire
        "enable_javascript" => false,            // Pas besoin pour un rapport
        "enable_remote" => true,                 // Si tu as des images externes (logo)
        "enable_html5_parser" => true,

        // === POLICE PAR DÉFAUT (obligatoire pour €, accents, arabe, etc.) ===
        "default_font" => "dejavusans",

        // === RÉSOLUTION & QUALITÉ (96 au lieu de 150 → -60% mémoire) ===
        "dpi" => 96,
        "font_height_ratio" => 1.1,

        // === FORMAT ===
        "default_media_type" => "print",
        "default_paper_size" => "a4",
        "default_paper_orientation" => "portrait",

        // === PROTOCOLES AUTORISÉS ===
        'allowed_protocols' => [
            "file://" => ["rules" => []],
            "http://" => ["rules" => []],
            "https://" => ["rules" => []]
        ],

        // === LOGS (désactivé en prod) ===
        'log_output_file' => null,

        // === BACKEND (CPDF est le plus stable et rapide) ===
        "pdf_backend" => "CPDF",

         'default_font' => 'dejavusans',
    'pdf_backend' => 'CPDF',
    'enable_font_subsetting' => true,
    'enable_html5_parser' => true,
    'font_cache' => sys_get_temp_dir(),
    'font_dir' => sys_get_temp_dir(),
    'temp_dir' => sys_get_temp_dir(),

    // ⚠️ Crucial pour éviter le crash iconv
    'unicode_warning' => false,

    
    ],
];