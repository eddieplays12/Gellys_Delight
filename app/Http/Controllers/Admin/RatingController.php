<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'product'])
            ->latest()
            ->get();

        return view('admin.ratings.index', ['ratings' => $ratings]);
    }
}
