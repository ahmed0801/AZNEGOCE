
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Catalogue TecDoc</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .card {
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .card-text {
            font-size: 0.9rem;
            color: #666;
        }
        .viewArticlesButton {
            margin-bottom: 10px;
        }

        .text-noir {
    color: #000!important;
}

    </style>
</head>
<body>
    <div class="container mt-5">

    <!-- Bouton retour -->
    <div class="mb-3">
        <a href="/newcustomer" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </a>
    </div>


        <div class="card">
            <div class="card-header text-noir">
                Catalogue TecDoc pour le véhicule : {{ $vehicle->license_plate }} ({{ $vehicle->brand_name }} {{ $vehicle->model_name }})
            </div>
            <div class="card-body">
                <div id="categoriesResult" class="mt-4"></div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "{{ route('getCategories') }}",
                type: "GET",
                data: {
                    brand_id: {{ $vehicle->brand_id }},
                    model_id: {{ $vehicle->model_id }},
                    engine_id: {{ $vehicle->engine_id }},
                    linking_target_id: {{ $vehicle->linkage_target_id }}
                },
                success: function (response) {
                    $('#categoriesResult').empty();
                    if (response.length > 0) {
                        let row = $('<div class="row g-3"></div>');
                        $.each(response, function (index, category) {
                            let col = $('<div class="col-md-4"></div>');
                            let card = $(`
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <h5 class="card-title">${category.assemblyGroupName}</h5>
                                        <p class="card-text">${category.count} article(s)</p>
                                        <button class="btn btn-primary w-100 viewArticlesButton" 
                                                data-category-id="${category.assemblyGroupNodeId}" 
                                                data-linking-target-id="${category.linkingTargetId || {{ $vehicle->linkage_target_id }}}">
                                            Voir les articles
                                        </button>
                                    </div>
                                </div>
                            `);
                            col.append(card);
                            row.append(col);
                        });
                        $('#categoriesResult').append(row);
                    } else {
                        $('#categoriesResult').append('<p>Aucune catégorie trouvée.</p>');
                    }
                },
                error: function () {
                    alert('Erreur lors de la récupération des catégories.');
                }
            });

            $(document).on('click', '.viewArticlesButton', function (e) {
                e.preventDefault();
                var categoryId = $(this).data('category-id');
                var linkingTargetId = $(this).data('linking-target-id');
                var url = '{{ route("persoget") }}' + '?assemblyGroupNodeId=' + categoryId + '&linkingTargetId=' + linkingTargetId + '&category_id=' + categoryId;
                var width = 1000;
                var height = 600;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;
                // window.open(url, 'popupWindow', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',scrollbars=yes');
                window.location.href = url;
            });
        });
    </script>
</body>
</html>
