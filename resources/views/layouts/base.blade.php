<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-1">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-gray-500 text-sm">
                    © 2026 Amazon Product Planner. All rights reserved.
                </div>
            </footer>
        </div>

        <!-- Drag & Drop Upload Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setupDragAndDrop();
            });

            function setupDragAndDrop() {
                const dropZones = document.querySelectorAll('[data-dropzone]');
                
                dropZones.forEach(zone => {
                    // Prevent default drag behaviors
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        zone.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    // Highlight drop zone when item is dragged over it
                    ['dragenter', 'dragover'].forEach(eventName => {
                        zone.addEventListener(eventName, () => {
                            zone.classList.add('border-blue-500', 'bg-blue-50');
                        }, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        zone.addEventListener(eventName, () => {
                            zone.classList.remove('border-blue-500', 'bg-blue-50');
                        }, false);
                    });

                    // Handle dropped files
                    zone.addEventListener('drop', handleDrop, false);
                    
                    // Handle file input click
                    const fileInput = zone.querySelector('input[type="file"]');
                    if (fileInput) {
                        zone.addEventListener('click', () => fileInput.click());
                        fileInput.addEventListener('change', () => handleFileSelect(fileInput));
                    }
                });
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files, this);
            }

            function handleFileSelect(input) {
                handleFiles(input.files, input.closest('[data-dropzone]'));
            }

            function handleFiles(files, zone) {
                const productId = zone.dataset.productId;
                const type = zone.dataset.imageType;
                
                [...files].forEach(file => {
                    if (file.type.startsWith('image/')) {
                        uploadFile(file, productId, type, zone);
                    }
                });
            }

            function uploadFile(file, productId, type, zone) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('type', type);

                const progressBar = zone.querySelector('.upload-progress');
                if (progressBar) {
                    progressBar.style.display = 'block';
                }

                fetch(`/products/${productId}/images`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add image to gallery
                        const gallery = zone.querySelector('.image-gallery');
                        if (gallery) {
                            const imgHTML = `
                                <div class="relative group" data-image-id="${data.image.id}">
                                    <img src="${data.image.url}" alt="${data.image.file_name}" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition delete-image" data-product-id="${productId}" data-image-id="${data.image.id}">
                                        ✕
                                    </button>
                                    <p class="text-xs text-gray-600 mt-1">${data.image.file_size}</p>
                                </div>
                            `;
                            gallery.insertAdjacentHTML('beforeend', imgHTML);
                            
                            // Attach delete handler
                            document.querySelector(`[data-image-id="${data.image.id}"] .delete-image`)?.addEventListener('click', deleteImage);
                        }
                        alert('Bild erfolgreich hochgeladen!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Fehler beim Hochladen!');
                })
                .finally(() => {
                    if (progressBar) {
                        progressBar.style.display = 'none';
                    }
                });
            }

            function deleteImage(e) {
                e.preventDefault();
                const button = e.target;
                const productId = button.dataset.productId;
                const imageId = button.dataset.imageId;

                if (confirm('Bild wirklich löschen?')) {
                    fetch(`/products/${productId}/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-image-id="${imageId}"]`).remove();
                            alert('Bild gelöscht!');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        </script>
    </body>
</html>
