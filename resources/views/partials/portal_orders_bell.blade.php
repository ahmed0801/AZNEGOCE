{{-- Cloche « Commandes portail » (barre du haut). Facultative : les alertes popup fonctionnent même sans elle. --}}
{{-- À coller dans <ul class="navbar-nav topbar-nav ...">. Toute la logique est dans public/assets/js/portal-orders-notify.js --}}
<li class="nav-item hidden-caret me-2" id="poBellItem" style="position:relative">
    <a href="#" id="poBellBtn" class="nav-link" style="position:relative" title="Commandes portail" aria-label="Commandes portail">
        <i class="fas fa-bell" style="font-size:1.15rem"></i>
        <span id="poBellN" style="display:none;position:absolute;top:2px;right:-2px;background:#f25961;color:#fff;border-radius:99px;min-width:18px;height:18px;font-size:.68rem;font-weight:800;align-items:center;justify-content:center;padding:0 4px">0</span>
    </a>
    <div id="poBellPanel" style="display:none;position:absolute;right:0;top:calc(100% + 6px);width:340px;max-width:92vw;background:#fff;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,.2);z-index:1500;overflow:hidden">
        <div style="padding:12px 16px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center">
            <b>Commandes portail</b>
            <span style="font-size:.78rem"><a href="#" id="poSound">Son : activé</a></span>
        </div>
        <div id="poBellList" style="max-height:340px;overflow:auto"></div>
        <div style="padding:10px 16px;border-top:1px solid #eee;background:#f8f9fa;display:flex;justify-content:space-between;gap:10px;font-size:.85rem">
            <a href="#" id="poDesk" style="display:none">Activer les alertes bureau</a>
            <a href="{{ route('b2b.orders') }}" style="margin-left:auto;font-weight:700">Voir toutes les commandes</a>
        </div>
    </div>
</li>
