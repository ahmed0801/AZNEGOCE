<footer>
    <p><strong>{{ $company->name }}</strong> | {{ $company->address }}</p>
    <p>Tél : {{ $company->phone ?? '-' }} | Email : {{ $company->email ?? '-' }} | MF : {{ $company->matricule_fiscal }}</p>
    @if($company->rib || $company->iban)<p class="hours">RIB : {{ $company->rib }} | IBAN : {{ $company->iban }} | SWIFT : {{ $company->swift }}</p>@endif
</footer>
