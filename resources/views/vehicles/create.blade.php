
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Associer un véhicule</title>
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
        .form-control, .form-select {
            border-radius: 8px;
        }
        .btn-primary {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Associer un véhicule au client : {{ $customer->name }}
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('customer.vehicle.store', $customer->id) }}" id="vehicleForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="brand_id" class="form-label">Marque :</label>
                            <select id="brand_id" name="brand_id" class="form-select select3" required>
                                <option value="">Sélectionner une marque</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="brand_name" id="brand_name">
                        </div>
                        <div class="col-md-4">
                            <label for="model_id" class="form-label">Modèle :</label>
                            <select id="model_id" name="model_id" class="form-select select3" required>
                                <option value="">Sélectionner un modèle</option>
                            </select>
                            <input type="hidden" name="model_name" id="model_name">
                        </div>
                        <div class="col-md-4">
                            <label for="engine_id" class="form-label">Motorisation :</label>
                            <select id="engine_id" name="engine_id" class="form-select select3" required>
                                <option value="">Sélectionner une motorisation</option>
                            </select>
                            <input type="hidden" name="engine_description" id="engine_description">
                            <input type="hidden" name="linkage_target_id" id="linkage_target_id">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="license_plate" class="form-label">Immatriculation :</label>
                            <input type="text" id="license_plate" name="license_plate" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Associer le véhicule</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select3').select2({
                placeholder: "-- Sélectionner une option --",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                theme: "classic"
            });

            $('#brand_id').change(function () {
                var brandId = $(this).val();
                var brandName = $(this).find('option:selected').data('name');
                $('#brand_name').val(brandName);
                if (brandId) {
                    $.ajax({
                        url: "{{ route('getModels') }}",
                        type: "GET",
                        data: { brand_id: brandId },
                        success: function (response) {
                            $('#model_id').empty();
                            $('#model_id').append('<option value="">Sélectionner un modèle</option>');
                            $.each(response, function (index, model) {
                                $('#model_id').append('<option value="' + model.id + '" data-name="' + model.name + '">' + model.name + '</option>');
                            });
                        },
                        error: function () {
                            alert('Erreur lors du chargement des modèles.');
                        }
                    });
                } else {
                    $('#model_id').empty();
                    $('#model_id').append('<option value="">Sélectionner un modèle</option>');
                }
                $('#engine_id').empty();
                $('#engine_id').append('<option value="">Sélectionner une motorisation</option>');
            });

            $('#model_id').change(function () {
                var modelId = $(this).val();
                var modelName = $(this).find('option:selected').data('name');
                $('#model_name').val(modelName);
                if (modelId) {
                    $.ajax({
                        url: "{{ route('getEngines') }}",
                        type: "GET",
                        data: { model_id: modelId },
                        success: function (response) {
                            $('#engine_id').empty();
                            $('#engine_id').append('<option value="">Sélectionner une motorisation</option>');
                            $.each(response, function (index, engine) {
                                $('#engine_id').append('<option value="' + engine.id + '" data-description="' + engine.description + '" data-linking-target-id="' + engine.linkageTargetId + '">' + engine.description + '</option>');
                            });
                        },
                        error: function () {
                            alert('Erreur lors du chargement des motorisations.');
                        }
                    });
                } else {
                    $('#engine_id').empty();
                    $('#engine_id').append('<option value="">Sélectionner une motorisation</option>');
                }
            });

            $('#engine_id').change(function () {
                var engineDescription = $(this).find('option:selected').data('description');
                var linkageTargetId = $(this).find('option:selected').data('linking-target-id');
                $('#engine_description').val(engineDescription);
                $('#linkage_target_id').val(linkageTargetId);
            });

            $('#vehicleForm').on('submit', function (e) {
                if (!confirm('Voulez-vous associer ce véhicule ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
