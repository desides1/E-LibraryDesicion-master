@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.selectMajor').select2({
                dropdownParent: $('#formInsert'),
                width: '100%',
            });

            $('.selectMajors').select2({
                dropdownParent: $('#formSuggestion'),
                width: '100%',
            });
        });

        // window.addEventListener('load', function() {
        //     var cards = document.querySelectorAll('.blog-item');

        //     var maxHeight = 0;
        //     cards.forEach(function(card) {
        //         var cardHeight = card.offsetHeight;
        //         if (cardHeight > maxHeight) {
        //             maxHeight = cardHeight;
        //         }
        //     });

        //     cards.forEach(function(card) {
        //         card.style.height = maxHeight + 'px';
        //     });
        // });

        const statusRadios = ['Dosen', 'Mahasiswa', 'Karyawan'];
        const nimValid = document.getElementById('nimValid');
        const majorValid = document.getElementById('majorValid');
        const unitValid = document.getElementById('unitValid');
        const inputNumber = document.getElementById('inputNumber');
        const inputMajor = document.getElementById('inputMajor');
        const inputUnit = document.getElementById('inputUnit');
        const labelNIM = document.getElementById('labelNIM');
        const labelMajor = document.getElementById('labelMajor');
        const labelUnit = document.getElementById('labelUnit');

        const toggleElement = (element, condition) => {
            element.style.display = condition ? '' : 'none';
        };

        const setRequired = (element, condition) => {
            if (condition) {
                element.setAttribute('required', 'required');
            } else {
                element.removeAttribute('required');
            }
        };

        statusRadios.forEach(status => {
            const radio = document.getElementById(status);
            radio.addEventListener('change', () => {
                const isMahasiswaOrDosen = status === 'Mahasiswa' || status === 'Dosen';
                const allStatu = status === 'Mahasiswa' || status === 'Dosen' || status === 'Karyawan';
                const isKaryawan = status === 'Karyawan';

                toggleElement(nimValid, true)
                toggleElement(majorValid, isMahasiswaOrDosen);
                toggleElement(unitValid, isKaryawan);

                setRequired(inputMajor, isMahasiswaOrDosen);
                setRequired(inputUnit, isKaryawan);
                setRequired(inputNumber, allStatu);

                labelNIM.textContent = status === 'Mahasiswa' ? 'NIM' : 'NIP./NIPPPK./NIK.';
                labelMajor.textContent = isMahasiswaOrDosen ? 'Program Studi' : '';
                labelUnit.textContent = isKaryawan ? 'Unit Poliwangi' : '';

                inputNumber.placeholder =
                    `Masukkan ${status === 'Mahasiswa' ? 'NIM' : 'NIP./NIPPPK./NIK.'} Anda`;
                inputMajor.placeholder = `Masukkan ${isMahasiswaOrDosen ? 'Program Studi' : ''} Anda`;
                inputUnit.placeholder = `Masukkan ${isKaryawan ? 'Unit Poliwangi' : ''} Anda`;
            });
        });


        const restrictInput = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;
                inputText = inputText.replace(/[^a-zA-Z ]/g, '');
                inputText = inputText.replace(/ +/g, ' ');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                inputElement.value = inputText;
            });
        };

        const restrictInputName = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;

                inputText = inputText.replace(/[^a-zA-Z., ]/g, '');
                inputText = inputText.replace(/ +/g, ' ');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                const regex = /[a-zA-Z][., ]*/g;
                const matches = inputText.match(regex);

                if (matches !== null) {
                    inputElement.value = matches.join('');
                } else {
                    inputElement.value = '';
                }
            });
        };

        const validateInput = (event) => {
            const input = event.target.value;
            const regex = /^[1-9][0-9.]*$/;
            if (!regex.test(input)) {
                event.target.value = input.slice(0, -1);
            }
        };

        const restrictNumber = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;

                inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

                inputText = inputText.replace(/\.+/g, '.');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                inputText = inputText.replace(/^0+/, '');

                inputElement.value = inputText;
            });
        };

        const restrictNumbers = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;

                inputText = inputText.replace(/[^a-zA-Z0-9 -]/g, '').replace(/^[^1-9]+/, '');

                inputText = inputText.replace(/\.+/g, '.');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                inputText = inputText.replace(/^0+/, '');

                inputElement.value = inputText;
            });
        };

        const restrictNumberPn = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;

            inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

            inputText = inputText.replace(/\.+/g, '.');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputText = inputText.replace(/^0+/, '');

            const inputValue = parseInt(inputText);

            var selectedStock = parseInt(new Date().getFullYear());

            if (inputValue > selectedStock) {
                inputText = selectedStock.toString();
            }

            inputElement.value = inputText;
        });
    };
    </script>
@endpush
