<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        /* Custom styling for the file input */
        .file-input {
            display: none;
        }

        .file-label {
            display: inline-block;
            background-color: #3b82f6; /* Blue color */
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .file-label:hover {
            background-color: #2563eb; /* Darker blue on hover */
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }
    </style>
</head>
<body class="bg-white text-black font-sans">

    <div class="max-w-4xl mx-auto p-4">
        <!-- Header -->
        <div class="flex items-center justify-between flex-col sm:flex-row">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <!-- Display Profile Picture -->
                    <img 
                        alt="Profile picture" 
                        class="rounded-full w-20 h-20 sm:w-24 sm:h-24" 
                        src="{{ Auth::user()->profile_picture_url ? asset('storage/' . Auth::user()->profile_picture_url) : 'https://via.placeholder.com/100' }}" />
                </div>
                <div class="mt-2 sm:mt-0">
                    <div class="flex flex-col items-start">
                        <!-- Display Username -->
                        <p class="text-xl sm:text-2xl font-bold">
                            {{ Auth::user()->username }}
                        </p>
                        <!-- Display Bio below username -->
                        <p class="text-sm sm:text-base text-gray-700 mt-1">
                            {{ Auth::user()->bio }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Edit, Archive, Create, and Logout buttons on the right side in a row -->
            <div class="flex space-x-2 mt-4 sm:mt-0">
                <button class="bg-gray-200 px-4 py-2 rounded text-sm" onclick="window.location='{{ route('profile.edit') }}'">
                    Edit profile
                </button>
                <button class="bg-gray-200 px-4 py-2 rounded text-sm" onclick="window.location='{{ route('archive') }}'">
                    View archive
                </button>
                <!-- Create button with plus icon -->
                <button class="bg-gray-200 text-black px-4 py-2 rounded text-sm flex items-center space-x-1" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    <span>Create</span>
                </button>
                <!-- Logout button next to Create button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-gray-200 text-black px-4 py-2 rounded text-sm flex items-center space-x-1">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex justify-center space-x-8 mt-6 border-t border-gray-300 pt-2">
            <a class="text-black font-bold flex items-center space-x-2">
                <!-- Icon kotak-kotak kecil -->
                <i class="fas fa-th"></i>
                <span>POSTS</span>
            </a>
        </div>

        <!-- Feed Display Section -->
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($feeds as $feed)
                <div class="bg-gray-100 p-4 rounded-md shadow-md" data-id="{{ $feed->id }}">
                    @if(in_array($feed->file_type, ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/feeds/' . $feed->filename) }}" alt="Feed Image" class="w-full h-48 object-cover rounded-md cursor-pointer" onclick="openFeedDetailModal({{ $loop->index }})">
                    @elseif(in_array($feed->file_type, ['mp4', 'mov']))
                        <video class="w-full h-48 object-cover rounded-md cursor-pointer" onclick="openFeedDetailModal({{ $loop->index }})" controls>
                            <source src="{{ asset('storage/feeds/' . $feed->filename) }}" type="video/{{ $feed->file_type }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <p class="text-red-500">Unsupported file type</p>
                    @endif
                    <p class="mt-2 text-gray-700 text-sm">{{ $feed->caption }}</p>
                    <p class="text-xs text-gray-500">{{ $feed->created_at->format('d M Y, H:i') }}</p>

                    <!-- Like and Comment Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Like Button -->
                        <button onclick="likePost({{ $feed->id }})" id="like-btn-{{ $feed->id }}" class="flex items-center space-x-2">
                            <i class="{{ $feed->is_liked_by_user ? 'fas' : 'far' }} fa-heart"
                            id="like-icon-{{ $feed->id }}"
                            style="font-size: 1.25rem; color: {{ $feed->is_liked_by_user ? 'red' : '#6b7280' }}">
                            </i>
                            <span id="like-count-{{ $feed->id }}">{{ $feed->likes->count() }}</span>
                        </button>

                        <!-- Comment Button -->
                        <button onclick="toggleCommentBox({{ $feed->id }})" class="text-gray-500 flex items-center space-x-2">
                            <i class="fas fa-comment"></i>
                            <span>{{ $feed->comments->count() }} Comments</span>
                        </button>
                    </div>

                    <!-- Comment Section -->
                    <div id="comment-box-{{ $feed->id }}" class="hidden mt-4">
                        <form onsubmit="postComment(event, {{ $feed->id }})">
                            @csrf
                            <textarea id="comment-input-{{ $feed->id }}" class="w-full p-2 border rounded" placeholder="Add a comment"></textarea>
                            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Post</button>
                        </form>
                        <ul class="mt-4">
                            @foreach($feed->comments as $comment)
                                <li class="text-sm text-gray-700">{{ $comment->user->username }}: {{ $comment->comment }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImageModal()">&times;</span>
        <div class="prev" onclick="changeImage(-1)">&#10094;</div>
        <div class="next" onclick="changeImage(1)">&#10095;</div>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Modal for Feed Detail -->
    <div id="feedDetailModal" class="modal">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto relative">
                <!-- Close Button -->
                <button 
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" 
                    onclick="closeFeedDetailModal()"
                    aria-label="Close Modal">
                    <i class="fas fa-times fa-lg"></i>
                </button>

                <!-- Modal Content -->
                <div class="p-4">
                    <!-- Image or Video Display -->
                    <div class="mb-4 relative">
                        <img id="feedDetailImage" class="hidden w-full h-auto rounded-md" />
                        <video id="feedDetailVideo" class="hidden w-full h-auto rounded-md" controls>
                            <source id="feedDetailVideoSource" src="" type="video/mp4">
                        </video>
                    </div>

                    <!-- Caption Section -->
                    <div class="mb-2">
                        <p id="feedDetailCaption" class="text-gray-700 text-base"></p>
                        <p id="feedDetailLikes" class="text-gray-500 text-sm mt-1"></p>
                    </div>

                    <!-- Comment Section -->
                    <div id="feedDetailComments" class="border-t pt-4 mt-4 max-h-40 overflow-y-auto space-y-2">
                        <!-- Comments will be dynamically inserted here -->
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button class="prev absolute top-1/2 left-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full hover:bg-opacity-75" onclick="changeFeedDetail(-1)">
                    &#10094;
                </button>
                <button class="next absolute top-1/2 right-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full hover:bg-opacity-75" onclick="changeFeedDetail(1)">
                    &#10095;
                </button>
            </div>
        </div>
    </div>


    <!-- Modal for creating posts -->
    <div id="createModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <h2 class="text-xl font-bold mb-4">Create New Post</h2>
            <!-- Form -->
            <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Upload Image or Video</label></br>
                    <!-- Custom file input button -->
                    <label for="file" class="file-label">Upload Your Post</label>
                    <input type="file" name="file" id="file" accept="image/*,video/*" class="file-input" onchange="previewFile(event)" required>
                </div>
                <!-- Preview section -->
                <div class="mb-4" id="file-preview-container">
                    <img id="file-preview" src="" alt="Preview" class="hidden w-full h-48 object-cover rounded-md" />
                    <video id="video-preview" class="hidden w-full h-48 object-cover rounded-md" controls>
                        <source id="video-source" src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="mb-4">
                    <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                    <textarea name="caption" id="caption" rows="4" class="mt-2 p-2 w-full border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-sm text-black rounded-md" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md">Post</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-300 mt-8 py-4">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-gray-600">
                &copy; 2023 Personal Light Instagram. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        let currentImageIndex = 0;
        const images = [];
        let currentFeedIndex = 0;
        const feeds = [];

        function openImageModal(index) {
            currentImageIndex = index;
            const feedImages = document.querySelectorAll('.grid img');
            feedImages.forEach((img, i) => {
                images[i] = img.src; // Store image sources
            });
            document.getElementById('modalImage').src = images[currentImageIndex];
            document.getElementById('imageModal').style.display = "block";
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = "none";
        }

        function changeImage(direction) {
            currentImageIndex += direction;
            if (currentImageIndex < 0) {
                currentImageIndex = images.length - 1; // Loop to last image
            } else if (currentImageIndex >= images.length) {
                currentImageIndex = 0; // Loop to first image
            }
            document.getElementById('modalImage').src = images[currentImageIndex];
        }

        function likePost(feedId) {
            fetch(`/feeds/${feedId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const likeCount = document.getElementById(`like-count-${feedId}`);
                    likeCount.textContent = data.total_likes;

                    const likeIcon = document.getElementById(`like-icon-${feedId}`);
                    if (data.is_liked) {
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas');
                        likeIcon.style.color = 'red';
                    } else {
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                        likeIcon.style.color = '#6b7280'; // Grey color
                    }
                }
            });
        }

        function postComment(event, feedId) {
            event.preventDefault();
            const comment = document.getElementById(`comment-input-${feedId}`).value;

            fetch(`/feeds/${feedId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ comment }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload(); // Reload untuk menampilkan komentar baru
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        function toggleCommentBox(feedId) {
            const commentBox = document.getElementById(`comment-box-${feedId}`);
            commentBox.classList.toggle('hidden');
        }

        function openFeedDetailModal(index) {
            currentFeedIndex = index;
            const feedItems = document.querySelectorAll('.grid > div');
            feedItems.forEach((feed, i) => {
                const image = feed.querySelector('img');
                const video = feed.querySelector('video');
                const caption = feed.querySelector('p.mt-2');
                const likeCount = feed.querySelector('span#like-count-' + feed.dataset.id);
                const comments = feed.querySelectorAll('ul > li');

                feeds[i] = {
                    imageSrc: image ? image.src : null,
                    videoSrc: video ? video.querySelector('source').src : null,
                    caption: caption.innerText,
                    likeCount: likeCount.innerText,
                    comments: Array.from(comments).map(comment => comment.innerText),
                };
            });

            console.log(feeds);

            const currentFeed = feeds[currentFeedIndex];

            if (currentFeed.imageSrc) {
                document.getElementById('feedDetailImage').src = currentFeed.imageSrc;
                document.getElementById('feedDetailImage').classList.remove('hidden');
                document.getElementById('feedDetailVideo').classList.add('hidden');
            } else if (currentFeed.videoSrc) {
                document.getElementById('feedDetailVideoSource').src = currentFeed.videoSrc;
                document.getElementById('feedDetailVideo').load();
                document.getElementById('feedDetailVideo').classList.remove('hidden');
                document.getElementById('feedDetailImage').classList.add('hidden');
            }

            document.getElementById('feedDetailCaption').innerText = currentFeed.caption;
            document.getElementById('feedDetailLikes').innerText = currentFeed.likeCount + ' Likes';

            const commentsContainer = document.getElementById('feedDetailComments');
            commentsContainer.innerHTML = ''; // Clear existing comments
            if (currentFeed.comments.length > 0) {
                currentFeed.comments.forEach(comment => {
                    const li = document.createElement('li');
                    li.classList.add('text-sm', 'text-gray-700');
                    li.innerHTML = comment;
                    commentsContainer.appendChild(li);
                });
            } else {
                // Tampilkan pesan jika tidak ada komentar
                const noCommentMsg = document.createElement('p');
                noCommentMsg.classList.add('text-gray-500', 'text-sm');
                noCommentMsg.innerText = 'No comments yet. Be the first to comment!';
                commentsContainer.appendChild(noCommentMsg);
            }

            document.getElementById('feedDetailModal').style.display = "block";
        }

        function closeFeedDetailModal() {
            document.getElementById('feedDetailModal').style.display = "none";
        }

        function changeFeedDetail(direction) {
            currentFeedIndex += direction;
            if (currentFeedIndex < 0) {
                currentFeedIndex = feeds.length - 1; // Loop to last feed
            } else if (currentFeedIndex >= feeds.length) {
                currentFeedIndex = 0; // Loop to first feed
            }
            openFeedDetailModal(currentFeedIndex);
        }

        // Function to open modal for creating posts
        function openModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        // Function to close modal for creating posts
        function closeModal() {
            document.getElementById('createModal').classList.add('hidden');
            // Clear the file input and preview when modal is closed
            document.getElementById('file').value = '';
            document.getElementById('file-preview').classList.add('hidden');
            document.getElementById('video-preview').classList.add('hidden');
        }

        // Function to show preview of image or video
        function previewFile(event) {
            const file = event.target.files[0];
            const previewImage = document.getElementById('file-preview');
            const previewVideo = document.getElementById('video-preview');
            const videoSource = document.getElementById('video-source');

            // Clear previous previews
            previewImage.classList.add('hidden');
            previewVideo.classList.add('hidden');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('hidden');
                    } else if (file.type.startsWith('video/')) {
                        videoSource.src = e.target.result;
                        previewVideo.classList.remove('hidden');
                    }
                };

                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>