@push('scripts')
    <script defer>
        try {
            document.getElementById('btnUsulkan').addEventListener('click', () => {
                document.getElementById('formSuggestion').style.display = '';
                document.getElementById('btnUsulkan').style.display = 'none';
            });
        } catch (error) {}

        try {
            document.getElementById('btnBatal').addEventListener('click', () => {
                document.getElementById('formSuggestion').style.display = 'none';
                document.getElementById('btnUsulkan').style.display = '';
            });
        } catch (error) {}
    </script>
@endpush
