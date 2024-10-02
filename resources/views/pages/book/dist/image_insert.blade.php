<script defer>
    const previewImage = () => {
        const image = document.querySelector('#inputImage');
        const previewImages = document.querySelector('#imagePreview');
        const resetButton = document.querySelector('#resetButton');

        if (image.files && image.files[0]) {
            const reader = new FileReader();

            reader.readAsDataURL(image.files[0]);

            reader.onload = (event) => {
                const imageDataUrl = event.target.result;

                previewImages.src = imageDataUrl;
                previewImages.style.width = '200px';
                previewImages.style.height = '300px';
                previewImages.classList.add('my-3');
                resetButton.style.display = 'block';
            };
        } else {
            previewImages.src = '';
            resetButton.style.display = 'none';
        }
    };

    const resetImage = () => {
        const input = document.getElementById('inputImage');
        const preview = document.getElementById('imagePreview');
        const resetButton = document.getElementById('resetButton');

        input.value = null;
        preview.classList.remove('my-3');
        preview.style.width = '0';
        preview.style.height = '0';
        preview.src = '';
        resetButton.style.display = 'none';
    };
</script>
