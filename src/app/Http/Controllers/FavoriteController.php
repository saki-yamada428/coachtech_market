<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Item $item)
    {
        auth()->user()->favoriteItems()->syncWithoutDetaching([$item->id]);
        return back();
    }

    public function destroy(Item $item)
    {
        auth()->user()->favoriteItems()->detach($item->id);
        return back();
    }
}
