<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="container mx-auto p-4">
        <!-- Tombol Kembali ke Home -->
        <div class="flex items-center mb-6">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-500 mr-2 transition duration-300">
                <i class="fas fa-arrow-left"></i> <!-- Ikon Panah Kiri -->
            </a>
            <span class="text-3xl font-bold">Archive</span> <!-- Teks Archive tetap terpisah -->
        </div>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('archive') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="date" name="start_date" class="form-input rounded-md border-gray-300" value="{{ $start_date }}">
                <input type="date" name="end_date" class="form-input rounded-md border-gray-300" value="{{ $end_date }}">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Filter</button>
            </div>
        </form>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left">Foto/Video</th>
                        <th class="py-3 px-4 text-left">Tanggal Post</th>
                        <th class="py-3 px-4 text-left">Caption</th>
                        <th class="py-3 px-4 text-left">Likes</th>
                        <th class="py-3 px-4 text-left">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feeds as $feed)
                    <tr class="border-b">
                        <td class="py-3 px-4">
                            @if(in_array($feed->file_type, ['jpg', 'jpeg', 'png']))
                            <img src="{{ asset('storage/feeds/' . $feed->filename) }}" width="100" alt="Image related to the post">
                            @elseif(in_array($feed->file_type, ['mp4', 'mov']))
                            <video width="100" controls>
                                <source src="{{ asset('storage/feeds/' . $feed->filename) }}" type="video/{{ $feed->file_type }}">
                            </video>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $feed->created_at->format('Y-m-d') }}</td>
                        <td class="py-3 px-4">{{ $feed->caption }}</td>
                        <td class="py-3 px-4">{{ $feed->likes_count}}</td>
                        <td class="py-3 px-4">{{ $feed->comments_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Download -->
        <form method="POST" action="{{ route('archive.download') }}" class="mt-6">
            @csrf
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="end_date" value="{{ $end_date }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select name="format" class="form-select rounded-md border-gray-300" required>
                    <option value="" disabled selected>Pilih Format</option>
                    <option value="xlsx">Excel</option>
                    <option value="pdf">PDF</option>
                </select>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">Download</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-300 mt-8 py-4">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-gray-600">
                &copy; 2023 Personal Light Instagram. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>