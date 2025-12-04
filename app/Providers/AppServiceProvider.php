<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketController;
use App\Models\Comments;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
   public function boot()
{
    // Composer for administrators (pages.main)
    View::composer('pages.main', function ($view) {
        $latestComments = collect();

        if (Auth::check() && Auth::user()->role === 'Administrator') {
            $latestComments = Comments::with('user')
                ->where('com_stat', 0) // only unseen
                ->whereNotNull('user_id') // only user comments
                ->orderBy('created_at', 'desc')
                ->get(); // no limit for admin
        }

        $view->with('latestComments', $latestComments);
    });

    // Composer for regular users (access.layout)
   View::composer('access.layout', function ($view) {
    $latestComments = collect();

    if (Auth::check() && Auth::user()->role !== 'Administrator') {
        $currentUser = Auth::user();

        $latestComments = Comments::with('user')
            ->where('com_stat', 0) // only unseen
            ->where(function ($q) use ($currentUser) {
                // Fetch comments where the user is either the creator or the assigned admin
                $q->where('user_id', $currentUser->id)
                  ->orWhere('admin_id', $currentUser->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(6) // limit number for dropdown
            ->get();
    }

    $view->with('latestComments', $latestComments);
});
}
}
