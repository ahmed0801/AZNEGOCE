{{-- Lien « Commandes portail » du menu latéral (à coller dans <ul class="nav nav-secondary">). --}}
<li class="nav-item">
    <a href="{{ route('b2b.orders') }}">
        <i class="fas fa-inbox"></i>
        <p>Commandes portail</p>
        <span class="badge badge-danger" id="poSideBadge" style="display:none">0</span>
    </a>
</li>
