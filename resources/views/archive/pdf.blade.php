<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feeds Archive</title>
</head>
<body>
    <h1>Feeds Archive</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Foto/Video</th>
                <th>Caption</th>
                <th>Tanggal Post</th>
                <th>Likes</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feeds as $feed)
                <tr>
                    <td>
                        @if(in_array($feed->file_type, ['jpg', 'jpeg', 'png']))
                        <img src="{{ public_path('storage/feeds/' . $feed->filename) }}" alt="Post" width="100">
                        @elseif(in_array($feed->file_type, ['mp4', 'mov']))
                            <a href="{{ asset('storage/feeds/' . $feed->filename) }}" target="_blank">Lihat Video</a>
                        @endif
                    </td>
                    <td>{{ $feed->caption }}</td>
                    <td>{{ $feed->created_at->format('d-m-Y') }}</td>
                    <td>{{ $feed->likes_count }}</td>
                    <td>{{ $feed->comments_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
