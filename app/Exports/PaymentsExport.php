<?php
namespace App\Exports;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class PaymentsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
}

    public function collection(): Collection
    {
        $query = Payment::with([
            'payable',
            'customer',
            'supplier',
            'paymentMode',
            'transfers.toAccount',
            'account'
        ]);

        if ($this->request->filled('date_from')) {
            $query->where('payment_date', '>=', $this->request->date_from);
}

        if ($this->request->filled('date_to')) {
            $query->where('payment_date', '<=', $this->request->date_to);
}

        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
}

        if ($this->request->filled('supplier_id')) {
            $query->where('supplier_id', $this->request->supplier_id);
}

        if ($this->request->filled('payment_mode')) {
            $query->where('payment_mode', $this->request->payment_mode);
}

        if ($this->request->filled('lettrage_code')) {
            $query->where('lettrage_code', 'like', '%'. $this->request->lettrage_code. '%');
}

        return $query->orderBy('updated_at', 'desc')->get()->map(function ($payment) {
            $paymentMode = $payment->paymentMode;
            $account = $payment->account?? ($paymentMode? ($paymentMode->debitAccount?? $paymentMode->creditAccount): null);
            $transfer = $payment->transfers->first();

            $accountText = $account? $account->name. ' ('. $account->account_number. ')': '-';
            if ($transfer) {
                $accountText.= ' | Transféré vers '. $transfer->toAccount->name. ' ('. $transfer->toAccount->account_number. ')';
}

            $document = '-';
            switch ($payment->payable_type) {
                case 'App\\Models\\Invoice':
                    $document = 'Facture Vente: '. ($payment->payable->numdoc?? 'N/A');
                    break;
                case 'App\\Models\\PurchaseInvoice':
                    $document = 'Facture Achat: '. ($payment->payable->numdoc?? 'N/A');
                    break;
                case 'App\\Models\\SalesNote':
                    $document = 'Avoir Vente: '. ($payment->payable->numdoc?? 'N/A');
                    break;
                case 'App\\Models\\PurchaseNote':$document = 'Avoir Achat: '. ($payment->payable->numdoc?? 'N/A');
                    break;
}

            return (object)[
                'Date de Paiement' => \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y'),
                'Client/Fournisseur' => $payment->customer? $payment->customer->name. ' (Client)': ($payment->supplier? $payment->supplier->name. ' (Fournisseur)': '-'),
                'Document' => $document,
                'Mode de Paiement' => $payment->payment_mode,
                'Compte Associé' => $accountText,
                'Montant (€)' => number_format($payment->amount, 2, ',', ' '),
                'Code Lettrage' => $payment->lettrage_code?? '-',
                'Référence' => $payment->reference?? '-',
                'Notes' => $payment->notes?? '-',
            ];
});
}

    public function headings(): array
    {
        return [
            'Date de Paiement',
            'Client/Fournisseur',
            'Document',
            'Mode de Paiement',
            'Compte Associé',
            'Montant (€)',
            'Code Lettrage',
            'Référence',
            'Notes',
        ];
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFDDDDDD']
                ]
            ],
        ];
}
}