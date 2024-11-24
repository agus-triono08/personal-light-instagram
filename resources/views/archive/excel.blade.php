<table>
    <thead>
        <tr>
            <th><b>Foto/Video</b></th>
            <th><b>Caption</b></th>
            <th><b>Post</b></th>
            <th><b>Likes</b></th>
            <th><b>Comments</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach($feeds as $feed)
            <tr>
                <td>{{ $feed->filename }}</td>
                <td>{{ $feed->caption }}</td>
                <td>{{ $feed->created_at->format('d-m-Y') }}</td>
                <td>{{ $feed->likes_count }}</td>
                <td>{{ $feed->comments_count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
