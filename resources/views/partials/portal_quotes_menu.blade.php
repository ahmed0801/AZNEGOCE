{{-- Lien « Demandes de devis » du menu latéral (à coller dans <ul class="nav nav-secondary">, sous « Commandes portail »). --}}
<li class="nav-item">
    <a href="{{ route('b2b.quotes') }}">
        <i class="fas fa-file-invoice"></i>
        <p>Demandes de devis</p>
        <span class="badge badge-danger" id="pqSideBadge" style="display:none">0</span>
    </a>
</li>
