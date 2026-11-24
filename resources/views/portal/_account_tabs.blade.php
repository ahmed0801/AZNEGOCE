<div class="acct-tabs">
  <a href="{{ route('portal.account') }}" class="{{ request()->routeIs('portal.account') ? 'on' : '' }}">Situation</a>
  <a href="{{ route('portal.account.invoices') }}" class="{{ request()->routeIs('portal.account.invoice*') ? 'on' : '' }}">Factures</a>
  <a href="{{ route('portal.account.dns') }}" class="{{ request()->routeIs('portal.account.dn*') ? 'on' : '' }}">Bons de livraison</a>
</div>
