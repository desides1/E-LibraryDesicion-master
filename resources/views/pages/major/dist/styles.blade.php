@push('styles')
    <style>
        .form-control:disabled,
        .form-control[readonly] {
            background-color: transparent !important;
        }

        .choices__inner {
            background-color: transparent !important;
            font-size: 15px;
        }

        .choices__item--selectable {
            font-weight: 500
        }

        .btn-navy {
            background-color: #005C97;
            color: #FFFFFF;
            border-color: #005C97;
        }

        .btn-navy:hover {
            background-color: #004672;
            border-color: #004672;
        }

        .btn-blues {
            background-color: #2b5876;
            color: #FFFFFF;
            border-color: #2b5876;
        }

        .btn-blues:hover {
            background-color: #284b63;
            border-color: #284b63;
        }
    </style>
@endpush
