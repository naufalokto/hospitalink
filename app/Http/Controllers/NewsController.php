<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show($slug)
    {
        $article = News::findBySlug($slug);
        
        if (!$article) {
            abort(404);
        }
        
        return view('news-detail', ['article' => $article]);
    }

    public function index()
    {
        $articles = News::latest()->get();
        return view('news.index', ['articles' => $articles]);
    }
}
