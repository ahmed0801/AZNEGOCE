<?php
namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
}

    public function collection(): Collection
    {
        return Payment::with([
            'payable',
            'customer',
            'supplier',
            'paymentMode',
            'transfers.toAccount',
            'account'
        ])
        ->when($this->request->filled('date_from'), fn($q) => $q->where('payment_date', '>=', $this->request->date_from))
        ->when($this->request->filled('date_to'), fn($q) => $q->where('payment_date', '<=', $this->request->date_to))
        ->when($this->request->filled('customer_id'), fn($q) => $q->where('customer_id', $this->request->customer_id))
        ->when($this->request->filled('supplier_id'), fn($q) => $q->where('supplier_id', $this->request->supplier_id))
        ->when($this->request->filled('payment_mode'), fn($q) => $q->where('payment_mode', $this->request->payment_mode))
        ->when($this->request->filled('lettrage_code'), fn($q) => $q->where('lettrage_code', 'like', '%'. $this->request->lettrage_code. '%'))
        ->orderBy('updated_at', 'desc')
        ->get(); // ✅ Ne pas faire de map ici
}

    public function map($payment): array
    {
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
            case 'App\\Models\\PurchaseNote':
                $document = 'Avoir Achat: '. ($payment->payable->numdoc?? 'N/A');
                break;
}

        return [
            \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y'),
            $payment->customer? $payment->customer->name. ' (Client)': ($payment->supplier? $payment->supplier->name. ' (Fournisseur)': '-'),
            $document,
            $payment->payment_mode,
            $accountText,
            number_format($payment->amount, 2, ',', ' '),
            $payment->lettrage_code?? '-',
            $payment->reference?? '-',
            $payment->notes?? '-',
        ];
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