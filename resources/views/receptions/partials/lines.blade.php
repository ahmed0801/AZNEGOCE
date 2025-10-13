<tbody>
    @foreach ($reception->lines as $line)
        <tr data-article-code="{{ $line->article_code }}">
            <td>
                <span class="badge rounded-pill text-bg-primary">{{ $line->article_code }}</span>
                <br>{{ $line->item->name ?? '-' }}
            </td>
            <td class="text-center">
                {{ optional($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code))->ordered_quantity ?? '-' }}
            </td>
            <td class="text-center">
                <input
                    type="number"
                    name="lines[{{ $loop->index }}][received_quantity]"
                    value="{{ $line->received_quantity }}"
                    min="0"
                    class="form-control"
                    data-article-code="{{ $line->article_code }}"
                    required
                >
                <input type="hidden" name="lines[{{ $loop->index }}][id]" value="{{ $line->id }}">
            </td>
        </tr>
    @endforeach
</tbody>