<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TicketDtl;
use App\Models\Survey;
use App\Models\Logs;
use App\Models\Article;
use App\Models\Comments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
   public function addArticles(Request $request)
{
    // Validate input
    $request->validate([
        'title' => 'required|string|max:255',
        'article_type' => 'required|integer', // this is the article type
        'content' => 'required|string|max:255',
    ]);

    // Generate unique article code
    do {
    $code = 'ART' . mt_rand(10000000, 99999999);
} while (Article::where('article_code', $code)->exists());

    // Create new article
    $article = new Article();
    $article->article_code = $code;
    $article->admin_id = auth()->id(); // or set manually if needed
    $article->article_type = $request->article_type;
    $article->title = $request->title;
    $article->content = $request->content;
    $article->save();

   return redirect()->back()->with('success', 'Article added successfully.');
}

public function articlesUser()
{
    $articles = Article::with('admin')->oldest()->paginate(4);

    // Fetch authors for dropdown (distinct admins who uploaded articles)
    $authors = \App\Models\User::whereIn('id', Article::pluck('admin_id')->unique())->get();

    // Optional: Add category list if needed
    $categories = [
        1 => 'Frequently Asked Questions',
        2 => 'Knowledge Base',
    ];

    return view('access.articlesFaqs', compact('articles', 'authors', 'categories'));
}

}
