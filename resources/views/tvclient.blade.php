<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>AZ NEGOCE - Écran Client</title>
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 300%;
            animation: gradientBG 12s ease infinite;
            color: #fff;
            font-family: 'Public Sans', sans-serif;
            margin: 0;
            padding: 15px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            max-width: 98%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 1s ease-in;
        }
        .header h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .header .datetime {
            font-size: 1.8rem;
            color: #e0e0e0;
            margin-top: 8px;
            font-weight: 300;
        }
        .sections-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .section {
            padding: 10px;
            border-radius: 12px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 70%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .section-header.en-cours i {
            animation: rotate 2s linear infinite;
            color: #00c4ff;
        }
        .section-header.expédié i {
            animation: pulse 1.5s ease-in-out infinite;
            color: #34c759;
        }
        .order-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr 0.5fr;
            align-items: center;
            font-size: 1.6rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            animation: slideIn 0.5s ease-out;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .order-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 1);
        }
        .order-card.new-expédié {
            animation: glow 2s ease-in-out 2;
        }
        .order-card.expédié {
            background: rgba(200, 230, 201, 0.9);
            border: 2px solid #28a745;
        }
        .order-card i {
            margin-right: 8px;
            font-size: 1.8rem;
        }
        .order-card .numdoc {
            font-weight: 700;
            color: #007bff;
        }
        .order-card .customer {
            font-weight: 500;
            color: #343a40;
            padding: 0 8px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .order-card .vendeur {
            color: #6c757d;
            font-style: italic;
        }
        .order-card .status {
            font-weight: 600;
            color: #fff;
            background: #ffc107;
            padding: 6px 12px;
            border-radius: 6px;
            text-align: center;
            font-size: 1.4rem;
        }
        .order-card.expédié .status {
            background: #28a745;
        }
        .no-orders {
            font-size: 1.8rem;
            text-align: center;
            color: #e0e0e0;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes glow {
            0% { box-shadow: 0 0 8px #28a745; }
            50% { box-shadow: 0 0 16px #34c759; }
            100% { box-shadow: 0 0 8px #28a745; }
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        @media (max-width: 1200px) {
            .header h1 { font-size: 2.5rem; }
            .header .datetime { font-size: 1.5rem; }
            .section-header { font-size: 2.5rem; }
            .order-card { font-size: 1.4rem; grid-template-columns: 1fr 1.2fr 1fr 0.5fr; padding: 15px; }
            .order-card i { font-size: 1.6rem; }
            .order-card .status { font-size: 1.2rem; }
            .sections-container { grid-template-columns: 1fr; }
            .no-orders { font-size: 1.5rem; }
        }
        @media (max-width: 768px) {
            .header h1 { font-size: 2rem; }
            .header .datetime { font-size: 1.2rem; }
            .section-header { font-size: 2rem; }
            .order-card { font-size: 1.2rem; grid-template-columns: 1fr 1fr 1fr 0.5fr; padding: 12px; }
            .order-card i { font-size: 1.4rem; }
            .order-card .status { font-size: 1rem; }
            .no-orders { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-store-alt"></i>Commandes Clients</h1>
            <div class="datetime"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::now('Europe/Paris')->format('d/m/Y H:i:s') }}</div>
        </div>

        <div class="sections-container">
            <div class="section en-cours">
                <div class="section-header en-cours"> En Cours De Préparation <i class="fas fa-tools"></i></div>
                <div id="en-cours-orders">
                    @foreach ($deliveryNotes->where('status', 'en_cours') as $deliveryNote)
                        <div class="order-card" data-id="{{ $deliveryNote->id }}">
                            <span class="numdoc"><i class="fas fa-file-alt"></i> {{ $deliveryNote->numdoc }}</span>
                            <span class="customer">{{ $deliveryNote->customer->name ?? 'Client inconnu' }}</span>
                            <span class="vendeur"><i class="fas fa-user"></i> {{ $deliveryNote->vendeur ?? 'Non assigné' }}</span>
                            <span class="status"><i class="fas fa-tools"></i></span>
                        </div>
                    @endforeach
                    @if ($deliveryNotes->where('status', 'en_cours')->isEmpty())
                        <div class="no-orders"><i class="fas fa-info-circle"></i> Aucune commande en cours</div>
                    @endif
                </div>
            </div>

            <div class="section expédié">
                <div class="section-header expédié"> Prêtes<i class="fas fa-check-circle"></i></div>
                <div id="expédié-orders">
                    @foreach ($deliveryNotes->where('status', 'expédié') as $deliveryNote)
                        <div class="order-card expédié" data-id="{{ $deliveryNote->id }}">
                            <span class="numdoc"><i class="fas fa-file-alt"></i> {{ $deliveryNote->numdoc }}</span>
                            <span class="customer">{{ $deliveryNote->customer->name ?? 'Client inconnu' }}</span>
                            <span class="vendeur"><i class="fas fa-user"></i> {{ $deliveryNote->vendeur ?? 'Non assigné' }}</span>
                            <span class="status"><i class="fas fa-check-circle"></i></span>
                        </div>
                    @endforeach
                    @if ($deliveryNotes->where('status', 'expédié')->isEmpty())
                        <div class="no-orders"><i class="fas fa-info-circle"></i> Aucune commande prête</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Initialize beep sound
            const beepSound = new Audio('https://www.soundjay.com/buttons/beep-01a.mp3');
            let lastDeliveryNotes = @json($lastDeliveryNotes);
            let soundUnlocked = false;

            // Prompt user to unlock sound on first interaction
            function unlockSound() {
                if (!soundUnlocked) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Activer le son',
                        text: 'Veuillez cliquer sur OK pour activer les notifications sonores.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        position: 'center',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            beepSound.play().then(() => {
                                soundUnlocked = true;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Son activé',
                                    text: 'Les notifications sonores sont maintenant activées.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true
                                });
                            }).catch((error) => {
                                console.error('Error playing sound on unlock:', error);
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Son bloqué',
                                    text: 'Le son n\'a pas pu être activé. Veuillez vérifier les paramètres de votre navigateur.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true
                                });
                            });
                        }
                    });
                }
            }

            // Play sound with error handling
            function playBeepSound() {
                if (soundUnlocked) {
                    console.log('Attempting to play sound for expédié order');
                    beepSound.play().then(() => {
                        console.log('Sound played successfully');
                    }).catch((error) => {
                        console.error('Error playing sound:', error);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Son bloqué',
                            text: 'Veuillez vérifier les paramètres de votre navigateur pour les notifications sonores.',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });
                    });
                } else {
                    console.log('Sound not unlocked, prompting user');
                    unlockSound();
                }
            }

            // Trigger unlock on first page click
            $(document).one('click', function () {
                console.log('First click detected, triggering unlockSound');
                unlockSound();
            });

            function updateOrders() {
                $.ajax({
                    url: '{{ route('tvclient.data') }}',
                    method: 'GET',
                    success: function (response) {
                        console.log('AJAX response received:', response);

                        // Update datetime with fade effect
                        $('.datetime').fadeOut(300, function () {
                            $(this).text(response.current_time).fadeIn(300);
                        });

                        // Detect new or updated expédié orders
                        const newDeliveryNotes = response.deliveryNotes;
                        const newExpédié = newDeliveryNotes.filter(note => note.status === 'expédié');
                        const oldIds = new Set(lastDeliveryNotes.map(note => note.id));
                        const oldExpédiéMap = new Map(lastDeliveryNotes
                            .filter(note => note.status === 'expédié')
                            .map(note => [note.id, note]));
                        const newlyExpédié = newExpédié.filter(note => {
                            const oldNote = oldExpédiéMap.get(note.id);
                            const isNew = !oldIds.has(note.id);
                            const isUpdated = oldNote && (oldNote.updated_at !== note.updated_at || oldNote.status !== note.status);
                            const wasNotExpédié = oldIds.has(note.id) && lastDeliveryNotes.find(old => old.id === note.id).status !== 'expédié';
                            return isNew || isUpdated || wasNotExpédié;
                        });

                        // Play sound and show notification for newly expédié orders
                        if (newlyExpédié.length > 0) {
                            console.log('New or updated expédié orders detected:', newlyExpédié);
                            playBeepSound();
                            newlyExpédié.forEach(note => {
                                Swal.fire({
                                    icon: 'success',
                                    title: `Commande ${note.numdoc} prête !`,
                                    text: `La commande pour ${note.customer_name} est prête à être récupérée au comptoir.`,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true,
                                    background: '#d4edda',
                                    iconColor: '#28a745'
                                });
                            });
                        } else {
                            console.log('No new or updated expédié orders');
                        }

                        // Update en_cours section
                        const enCoursOrders = newDeliveryNotes.filter(note => note.status === 'en_cours');
                        $('#en-cours-orders').empty();
                        if (enCoursOrders.length === 0) {
                            $('#en-cours-orders').append('<div class="no-orders"><i class="fas fa-info-circle"></i> Aucune commande en cours</div>');
                        } else {
                            enCoursOrders.forEach(note => {
                                const isNew = !lastDeliveryNotes.some(old => old.id === note.id);
                                $('#en-cours-orders').append(`
                                    <div class="order-card${isNew ? ' slide-in' : ''}" data-id="${note.id}">
                                        <span class="numdoc"><i class="fas fa-file-alt"></i> ${note.numdoc}</span>
                                        <span class="customer">${note.customer_name}</span>
                                        <span class="vendeur"><i class="fas fa-user"></i> ${note.vendeur}</span>
                                        <span class="status"><i class="fas fa-tools"></i></span>
                                    </div>
                                `);
                            });
                        }

                        // Update expédié section
                        const expédiéOrders = newDeliveryNotes.filter(note => note.status === 'expédié');
                        $('#expédié-orders').empty();
                        if (expédiéOrders.length === 0) {
                            $('#expédié-orders').append('<div class="no-orders"><i class="fas fa-info-circle"></i> Aucune commande prête</div>');
                        } else {
                            expédiéOrders.forEach(note => {
                                const isNewOrUpdated = !lastDeliveryNotes.some(old => old.id === note.id) ||
                                    lastDeliveryNotes.find(old => old.id === note.id)?.updated_at !== note.updated_at ||
                                    lastDeliveryNotes.find(old => old.id === note.id)?.status !== note.status;
                                $('#expédié-orders').append(`
                                    <div class="order-card expédié${isNewOrUpdated ? ' new-expédié slide-in' : ''}" data-id="${note.id}">
                                        <span class="numdoc"><i class="fas fa-file-alt"></i> ${note.numdoc}</span>
                                        <span class="customer">${note.customer_name}</span>
                                        <span class="vendeur"><i class="fas fa-user"></i> ${note.vendeur}</span>
                                        <span class="status"><i class="fas fa-check-circle"></i></span>
                                    </div>
                                `);
                            });
                        }

                        // Update lastDeliveryNotes for next comparison
                        lastDeliveryNotes = newDeliveryNotes;
                    },
                    error: function (xhr) {
                        console.error('Error fetching data:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Impossible de récupérer les données. Veuillez vérifier la connexion.',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });
                    }
                });
            }

            // Initial update and set interval
            updateOrders();
            setInterval(updateOrders, 6000);

            // Remove slide-in and glow classes after animation
            $(document).on('animationend', '.slide-in', function () {
                $(this).removeClass('slide-in');
            });
            $(document).on('animationend', '.new-expédié', function () {
                $(this).removeClass('new-expédié');
            });
        });
    </script>
</body>
</html>