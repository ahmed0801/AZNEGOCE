<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tickets de Dépôt</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
            font-family: sans-serif;
            font-size: 3mm;
        }

        table {
            width: 210mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            width: 70mm;
            height: 43mm;
            border: 0.1mm dashed black;
            box-sizing: border-box;
            vertical-align: top;
            text-align: center;
            padding: 0;
            overflow: hidden;
        }

        .content {
            padding: 1mm;
            box-sizing: border-box;
            word-wrap: break-word;
        }

        .barcode {
            margin-top: 1mm;
        }
    </style>
</head>
<body>

@php
    use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

    $allTickets = [];

    foreach ($tickets as $reception) {
        foreach ($reception['Lignes'] as $ligne) {
            $copies = (int) $ligne['LivreNonFact'];
            for ($i = 0; $i < $copies; $i++) {
                $allTickets[] = $ligne;
            }
        }
    }

    // Tri par ArticleNo
    usort($allTickets, fn($a, $b) => strcmp($a['ArticleNo'], $b['ArticleNo']));
    $count = count($allTickets);
@endphp

<table>
    @for ($i = 0; $i < $count; $i++)
        @if ($i % 3 == 0)
            <tr>
        @endif

        <td>
            <div class="content" style="margin-top:5mm;">
            <strong style="font-size: 5mm;">{{ $allTickets[$i]['ArticleNo'] }}</strong><br><br>
            {{ $allTickets[$i]['Description'] }}<br>
                Emplacement: {{ $allTickets[$i]['Emplacement'] }}<br>
                <div class="barcode" style="text-align:center; margin-top:3mm; margin-left:6mm;">
                    {!! DNS1D::getBarcodeHTML($allTickets[$i]['ArticleNo'], 'C128', 1.2, 15) !!}
                    <br>
                    {{ date('d/m/Y') }}
                </div>
            </div>
        </td>

        @if ($i % 3 == 2 || $i == $count - 1)
            @if ($i % 3 != 2)
                {{-- Compléter la ligne si incomplète --}}
                @for ($j = 0; $j < 2 - ($i % 3); $j++)
                    <td></td>
                @endfor
            @endif
            </tr>
        @endif
    @endfor
</table>

</body>
</html>
