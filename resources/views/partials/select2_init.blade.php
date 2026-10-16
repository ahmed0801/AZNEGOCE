{{-- Listes déroulantes recherchables (select2) — à inclure juste avant </body>, après jQuery.
     Cible : les filtres des listes (.lh-filters select), les selects marqués .select2-filter ou .js-s2.
     Charge la bibliothèque select2 si la page ne l'a pas déjà. --}}
<style>
.lh-filters .select2-container{width:100%!important}
.lh-filters .select2-container--default .select2-selection--single{height:38px!important;min-height:38px!important;border-radius:8px!important;display:flex;align-items:center;font-size:.88rem!important;padding:0 .4rem!important}
.lh-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px!important}
.lh-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px!important}
.select2-container--open{z-index:20000}
</style>
<script>
(function () {
    if (!window.jQuery) return;
    if (!jQuery.fn.select2) {
        var l = document.createElement('link'); l.rel = 'stylesheet'; l.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'; document.head.appendChild(l);
        document.write('<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"><\/script>');
    }
})();
</script>
<script>
jQuery(function ($) {
    if (!$.fn.select2) return;
    $('.lh-filters select, select.select2-filter, select.js-s2').each(function () {
        var $s = $(this);
        if ($s.data('select2') || $s.hasClass('no-s2')) return;
        var first = $s.find('option:first-child');
        $s.select2({
            width: '100%',
            placeholder: first.val() === '' ? first.text() : undefined,
            allowClear: first.val() === '',
            minimumResultsForSearch: 0,
            dropdownParent: $s.closest('.modal').length ? $s.closest('.modal') : $(document.body),
            language: { noResults: function () { return 'Aucun résultat'; }, searching: function () { return 'Recherche…'; } }
        });
    });
    // Ouvre le champ de recherche dès l'ouverture de la liste
    $(document).on('select2:open', function () {
        var el = document.querySelector('.select2-container--open .select2-search__field');
        if (el) el.focus();
    });
});
</script>
