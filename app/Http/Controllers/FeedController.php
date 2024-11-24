<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeedsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class FeedController extends Controller
{
    /**
     * Display the list of feeds on the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua feed berdasarkan waktu upload terbaru
        $feeds = Feed::latest()->get();

        // Mengambil feed dengan komentar dan informasi pengguna
        #$feeds = Feed::with(['comments.user'])->latest()->get();

        // Mengirim data feeds ke view 'home'
        return view('home', compact('feeds'));
    }

    /**
     * Show the form to create a new feed (post).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Menampilkan form untuk membuat feed baru
        return view('feed.create_feed');
    }

    /**
     * Store a new feed (post) in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input file dan caption
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,mp4,mov|max:153600',  // max 150MB
            'caption' => 'nullable|string|max:255',
        ]);
    
        // Menyimpan file yang diunggah
        $file = $request->file('file');
        $fileName = 'feeds/' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Memastikan file disimpan dalam storage public/feeds
        $file->storeAs('public/feeds', $fileName);
    
        // Membuat entri baru di tabel feeds
        $feed = new Feed();
        $feed->user_id = Auth::id();  // Menyimpan ID pengguna yang sedang login
        $feed->filename = $fileName;
        $feed->file_type = $file->getClientOriginalExtension();
        $feed->caption = $request->caption;
        $feed->save();
    
        // Redirect ke home dengan pesan sukses
        return redirect()->route('home')->with('success', 'Feed created successfully!');
    }

    public function like(Request $request, Feed $feed)
    {
        $user = auth()->user();

        if ($feed->likes()->where('user_id', $user->id)->exists()) {
            // Unlike the post
            $feed->likes()->where('user_id', $user->id)->delete();
            $isLiked = false;
        } else {
            // Like the post
            $feed->likes()->create(['user_id' => $user->id]);
            $isLiked = true;
        }

        return response()->json([
            'status' => 'success',
            'total_likes' => $feed->likes()->count(),
            'is_liked' => $isLiked,
        ]);
    }

    public function comment(Request $request, Feed $feed)
    {
        $request->validate(['comment' => 'required|string|max:255']);

        $feed->comments()->create([
            'user_id' => auth()->id(),
            'text' => $request->comment,
        ]);

        return redirect()->back()->with('message', 'Comment added successfully!');
    }

    public function archive(Request $request)
    {

        // Filter tanggal berdasarkan request
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Query feeds dengan likes_count dan comments_count
        $query = Feed::withCount(['likes', 'comments']);

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        $feeds = $query->orderBy('created_at', 'desc')->get();

        return view('archive.index', compact('feeds', 'start_date', 'end_date'));
    }

    public function download(Request $request)
    {
        $format = $request->input('format'); // xlsx atau pdf
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Ambil data yang difilter dengan relasi likes dan comments
        $feeds = Feed::withCount(['likes', 'comments']); // Menghitung jumlah like dan comment

        if ($start_date && $end_date) {
            $feeds = $feeds->whereBetween('created_at', [$start_date, $end_date]);
        }

        $feeds = $feeds->get();

        if ($format === 'xlsx') {
            return Excel::download(new FeedsExport($feeds), 'feeds_archive.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('archive.pdf', compact('feeds'))
    ->setOption('isHtml5ParserEnabled', true)
    ->setOption('isPhpEnabled', true); // Menambahkan dukungan PHP untuk gambar lokal

            return $pdf->download('feeds_archive.pdf');
        }

        return redirect()->back()->withErrors('Invalid format selected.');
    }



}