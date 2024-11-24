<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FeedsExport implements FromView
{
    public $feeds;

    public function __construct($feeds)
    {
        $this->feeds = $feeds;
    }

    public function view(): View
    {
        return view('archive.excel', ['feeds' => $this->feeds]);
    }
}

