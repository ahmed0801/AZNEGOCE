<div class="totals-box">
    <table>
        @foreach($vat as $rate => $v)
            @if(count($vat) > 1)
            <tr><td class="label">Base HT (TVA {{ number_format((float)$rate, 2, ',', ' ') }}%) :</td><td class="amount">{{ number_format($v['base'], 2, ',', ' ') }} €</td></tr>
            @endif
            <tr><td class="label">TVA {{ number_format((float)$rate, 2, ',', ' ') }}% :</td><td class="amount">{{ number_format($v['tva'], 2, ',', ' ') }} €</td></tr>
        @endforeach
        <tr><td class="label">{{ $labelHT ?? 'Total HT' }} :</td><td class="amount">{{ number_format($netHT, 2, ',', ' ') }} €</td></tr>
        <tr><td class="label">{{ $labelTTC ?? 'Total TTC' }} :</td><td class="amount"><strong>{{ number_format($netTTC, 2, ',', ' ') }} €</strong></td></tr>
    </table>
</div>
