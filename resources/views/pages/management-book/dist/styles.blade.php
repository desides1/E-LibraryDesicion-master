@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print"
        onload="this.media='all'" />
    <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: 1px solid #dce7f1;
            line-height: 38.18px;
        }

        .select2-container .select2-selection--single {
            height: 38.18px;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background: transparent !important;
        }
    </style>
@endpush
