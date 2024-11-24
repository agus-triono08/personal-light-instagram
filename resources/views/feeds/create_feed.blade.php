<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Feed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="max-w-lg w-full bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Create Feed</h2>

        <!-- Form untuk mengunggah gambar/video dan caption -->
        <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Upload Image/Video -->
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Create new post</label>
            </br>
                <!-- Custom file input button -->
                <div class="flex items-center space-x-2">
                    <label for="file" class="bg-blue-500 text-white px-6 py-2 rounded-md cursor-pointer">
                        Upload Your Post
                    </label>
                    <input type="file" name="file" id="file" class="hidden" accept="image/*,video/*" required onchange="previewMedia()">
                </div>
                @error('file')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Preview Gambar atau Video -->
            <div id="preview" class="mb-4">
                <p class="text-sm text-gray-500">Preview will appear here...</p>
            </div>

            <!-- Caption -->
            <div class="mb-4">
                <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                <textarea name="caption" id="caption" rows="4" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Write your caption here..."></textarea>
                @error('caption')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">
                    Submit Feed
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewMedia() {
            const fileInput = document.getElementById('file');
            const previewContainer = document.getElementById('preview');
            const file = fileInput.files[0];

            // Clear any previous previews
            previewContainer.innerHTML = '';

            if (file) {
                const fileURL = URL.createObjectURL(file);
                const fileType = file.type.split('/')[0];

                if (fileType === 'image') {
                    const img = document.createElement('img');
                    img.src = fileURL;
                    img.alt = "Preview Image";
                    img.classList.add('w-full', 'h-auto', 'rounded-md', 'border');
                    previewContainer.appendChild(img);
                } else if (fileType === 'video') {
                    const video = document.createElement('video');
                    video.src = fileURL;
                    video.controls = true;
                    video.classList.add('w-full', 'h-auto', 'rounded-md', 'border');
                    previewContainer.appendChild(video);
                }
            }
        }
    </script>

</body>
</html>
