<style>
        .lh-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
        .lh-title{margin:0;font-weight:700;display:flex;align-items:center;gap:.5rem}
        .lh-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-btns .btn,.lh-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
        .lh-btns .dropdown{position:relative}
        .lh-filters{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:.5rem}
        .lh-filters .lh-field{flex:1 1 150px;min-width:130px;position:relative}
        .lh-filters .lh-wide{flex:2 1 260px}
        .lh-filters .lh-field i.lh-ico{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;z-index:2}
        .lh-filters .form-control{height:38px;border-radius:8px;font-size:.88rem}
        .lh-filters #search_item{padding-left:2.1rem;background:#e8f4fd;border:1.5px solid #90c0e8}
        .lh-filters #search_item:focus{background:#fff;border-color:#007bff;box-shadow:0 0 0 3px rgba(0,123,255,.12);outline:none}
        .lh-filters .select2-container{width:100%!important}
        .lh-filters .select2-container--default .select2-selection--single{height:38px;border-radius:8px;display:flex;align-items:center}
        .lh-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;font-size:.88rem;padding-left:.6rem}
        .lh-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
        .lh-check{display:inline-flex;align-items:center;gap:.4rem;margin:0;height:38px;font-size:.88rem;font-weight:600;cursor:pointer;user-select:none}
        .lh-check input{width:16px;height:16px;margin:0}
        .top-row .form-label{font-size:.82rem}
        .top-row .select2-container--default .select2-selection--single{height:38px;border-radius:8px;display:flex;align-items:center}
        .top-row .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;padding-left:.6rem}
        .top-row .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
        .top-row .form-control.order_date{width:100%;height:38px!important;min-height:38px;border-radius:8px;font-size:.88rem;padding:0 .6rem;line-height:36px}
        .lh-actionbar{position:sticky;bottom:0;z-index:20;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.75rem;padding:.7rem 1rem;background:#fff;border:1px solid #dbe3ee;border-radius:10px;box-shadow:0 -4px 14px rgba(0,0,0,.08)}
        .lh-actionbar .ab-totals{display:flex;gap:1.5rem;align-items:baseline;font-size:1.05rem}
        .lh-actionbar .ab-totals b{font-size:1.25rem}
        .lh-actionbar .ab-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-actionbar .ab-btns .btn{height:42px;min-width:150px;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:0 1rem;font-size:.9rem;font-weight:600;border-radius:8px;margin:0;white-space:nowrap}
        .lh-actionbar .ab-btns .btn small{font-weight:500;opacity:.85}
        @media (max-width:768px){.lh-actionbar .ab-btns .btn{flex:1 1 45%;min-width:0}}
.pg-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;margin-bottom:1rem}.pg-card h6{font-weight:700;margin-bottom:.6rem}
#linesTable{table-layout:auto}
#linesTable th{font-size:.72rem!important;letter-spacing:.02em!important;text-transform:uppercase;padding:8px 6px!important;white-space:nowrap;vertical-align:middle}
#linesTable td{vertical-align:middle;padding:4px 6px!important;font-size:.85rem}
#linesTable input.form-control{height:32px!important;min-height:32px!important;padding:2px 6px!important;font-size:.85rem!important;line-height:1.2!important;border-radius:6px!important;min-width:0;width:100%;text-align:right}
#linesTable input.form-control[readonly]{background:#f3f4f6;color:#374151;font-weight:600}
#linesTable input[type=number]{-moz-appearance:textfield}
#linesTable input[type=number]::-webkit-outer-spin-button,#linesTable input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
#linesTable input.description{text-align:left}
#linesTable input[type=checkbox]{width:16px;height:16px}
</style>
