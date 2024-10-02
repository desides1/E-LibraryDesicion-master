<script defer>
    const previewImage = () => {
        const image = document.querySelector('#inputImage');
        const previewImages = document.querySelector('#imagePreview');
        const resetButton = document.querySelector('#resetButton');
        const imagePreviewDefault = document.getElementById("imagePreviewDefault");

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

                imagePreviewDefault && (
                    imagePreviewDefault.style.display = 'none'
                )
            };
        } else {
            previewImages.src = '';
            resetButton.style.display = 'none';
        }
    };

    const resetImage = () => {
        const input = document.getElementById('inputImage');
        const preview = document.getElementById('imagePreview');
        const preview2 = document.getElementById('imagePreviewDefault');
        const resetButton = document.getElementById('resetButton');
        const previewCondition = document.getElementById('previewCondition');
        input.style.display = 'inline';

        input.value = null;
        preview.classList.remove('my-3');
        preview.style.width = '0';
        preview.style.height = '0';
        preview.src = '';

        preview2 && (
            preview2.style.width = '0',
            preview2.style.height = '0',
            preview2.src = ''
        );

        resetButton.style.display = 'none';

        previewCondition && (
            previewCondition.style.display = 'none'
        )
    };

    const setPreviewContainerStyles = () => {
        const imagePreviewContainer = document.querySelector('.image-preview-container');

        if (imagePreviewContainer) {
            imagePreviewContainer.style.display = 'flex';
            imagePreviewContainer.style.flexDirection = 'column';
            imagePreviewContainer.style.alignItems = 'start';
        }
    }

    document.addEventListener('DOMContentLoaded', setPreviewContainerStyles);
</script>
