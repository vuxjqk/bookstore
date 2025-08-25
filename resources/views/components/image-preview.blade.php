@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Image preview
            const fileInput = document.getElementById('file-input');
            const previewContainer = document.getElementById('image-preview-container');
            const existingImageIdsContainer = document.getElementById('existing-image-ids-container');

            let filesArray = [];
            let existingImagesArray = [
                @if (isset($book))
                    @foreach ($book->images as $image)
                        {
                            id: {{ $image->id }},
                            url: "{{ asset('storage/' . $image->image_path) }}"
                        }
                        {{ $loop->last ? '' : ',' }}
                    @endforeach
                @endif
            ];

            const updatePreview = () => {
                previewContainer.innerHTML = '';
                previewContainer.classList.toggle('hidden', filesArray.length === 0 && existingImagesArray
                    .length === 0);

                existingImagesArray.forEach((img, index) => {
                    const previewBox = createPreviewBox(img.url, `Existing Image ${index + 1}`,
                        () => {
                            existingImagesArray.splice(index, 1);
                            updateExistingImageInputs();
                            updatePreview();
                        });
                    previewContainer.appendChild(previewBox);
                });

                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewBox = createPreviewBox(e.target.result,
                            `Preview ${index + 1}`, () => {
                                filesArray.splice(index, 1);
                                updateInputFiles();
                                updatePreview();
                            });
                        previewContainer.appendChild(previewBox);
                    };
                    reader.readAsDataURL(file);
                });
            };

            const createPreviewBox = (src, alt, onRemove) => {
                const previewBox = document.createElement('div');
                previewBox.classList.add('w-32', 'h-32', 'relative', 'rounded-lg', 'overflow-hidden',
                    'shadow', 'group');

                const image = document.createElement('img');
                image.src = src;
                image.alt = alt;
                image.className =
                    'w-full h-full object-cover group-hover:opacity-50 transition-opacity duration-300';
                image.loading = 'lazy';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                removeBtn.className =
                    'absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition duration-200';
                removeBtn.title = '{{ __('Delete') }}';
                removeBtn.addEventListener('click', onRemove);

                previewBox.appendChild(image);
                previewBox.appendChild(removeBtn);
                return previewBox;
            };

            const updateInputFiles = () => {
                const dataTransfer = new DataTransfer();
                filesArray.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            };

            const updateExistingImageInputs = () => {
                if (existingImageIdsContainer) {
                    existingImageIdsContainer.innerHTML = '';
                    existingImagesArray.forEach(img => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'existing_image_ids[]';
                        input.value = img.id;
                        existingImageIdsContainer.appendChild(input);
                    });
                }
            };

            fileInput.addEventListener('change', (e) => {
                const newFiles = Array.from(e.target.files).filter(file => file.type.match('image.*'));
                filesArray.push(...newFiles);
                updateInputFiles();
                updatePreview();
            });

            updatePreview();
        });
    </script>
@endPushOnce
