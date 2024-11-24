<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        // JavaScript untuk menampilkan preview gambar yang diupload
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imagePreview = document.getElementById('profileImagePreview');
                imagePreview.src = e.target.result; // Update the image source to the selected file
            };

            if (file) {
                reader.readAsDataURL(file); // Read the file as a data URL (base64)
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="max-w-4xl w-full mx-auto p-4 bg-white shadow-md rounded-lg mt-10">
        <!-- Tombol Kembali ke Home -->
        <div class="flex items-center mb-6">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-500 mr-2 transition duration-300">
                <i class="fas fa-arrow-left"></i> <!-- Ikon Panah Kiri -->
            </a>
            <span class="text-3xl font-bold">Edit Profile</span> <!-- Teks Edit Profile -->
        </div>

        <!-- Pesan sukses -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Success!</strong>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Input Profile Picture -->
            <div class="mb-4 w-full text-center">
                <div class="flex flex-col sm:flex-row items-center justify-center border p-4 rounded-md">
                    <!-- Preview Gambar Profil -->
                    <img id="profileImagePreview" alt="Current profile picture"
                         class="rounded-full w-32 h-32 object-cover mb-4 sm:mb-0 sm:mr-4"
                         src="{{ $user->profile_picture_url ? asset('storage/' . $user->profile_picture_url) : 'https://via.placeholder.com/150' }}"/>

                    <div class="ml-4 text-center sm:text-left">
                        <!-- Input file untuk memilih gambar -->
                        <input type="file" name="profile_picture" id="profile_picture" class="hidden" onchange="previewImage(event)">
                        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md"
                                onclick="document.getElementById('profile_picture').click()">
                            Change Photo
                        </button>
                    </div>
                </div>
                @error('profile_picture')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Username -->
            <div class="mb-4 w-full">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                       class="mt-1 block w-full text-sm border-gray-300 rounded-md">
                @error('username')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Bio -->
            <div class="mb-4 w-full">
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="4"
                          class="mt-1 block w-full text-sm border-gray-300 rounded-md">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full sm:w-auto">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

</body>
</html>
