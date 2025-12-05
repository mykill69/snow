<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketController;
use App\Models\Comments;
use App\Models\TicketDtl;

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
//    public function boot()
// {
//     // Composer for administrators (pages.main)
//     View::composer('pages.main', function ($view) {
//         $latestComments = collect();

//         if (Auth::check() && Auth::user()->role === 'Administrator') {
//             $latestComments = Comments::with('user')
//                 ->where('com_stat', 0) // only unseen
//                 ->whereNotNull('user_id') // only user comments
//                 ->orderBy('created_at', 'desc')
//                 ->get(); // no limit for admin
//         }

//         $view->with('latestComments', $latestComments);
//     });

//     // Composer for regular users (access.layout)
//    View::composer('access.layout', function ($view) {
//     $latestComments = collect();

//     if (Auth::check() && Auth::user()->role !== 'Administrator') {
//         $currentUser = Auth::user();

//         $latestComments = Comments::with('user')
//             ->where('com_stat', 0) // only unseen
//             ->where(function ($q) use ($currentUser) {
//                 // Fetch comments where the user is either the creator or the assigned admin
//                 $q->where('user_id', $currentUser->id)
//                   ->orWhere('admin_id', $currentUser->id);
//             })
//             ->orderBy('created_at', 'desc')
//             ->take(6) // limit number for dropdown
//             ->get();
//     }

//     $view->with('latestComments', $latestComments);
// });
// }
public function boot()
{
    // Admin notifications (pages.main)
    // View::composer('pages.main', function ($view) {
    //     $latestComments = collect();

    //     if (Auth::check() && Auth::user()->role === 'Administrator') {
    //         $latestComments = Comments::with('user')
    //             ->where('com_stat', 0) // only unseen
    //             ->whereNotNull('user_id') // only user comments
    //             ->orderBy('created_at', 'desc')
    //             ->get(); // no limit for admin
    //     }

    //     $view->with('latestComments', $latestComments);
    // });


View::composer('pages.main', function ($view) {
    $currentUser = Auth::user();
    $latestComments = collect();

    // Only for Administrators
    if ($currentUser && $currentUser->role === 'Administrator') {
        // Fetch all unseen comments from users (Staff)
        $latestComments = Comments::with('user', 'admin')
            ->where('com_stat', 0)        // only unseen
            ->whereNotNull('user_id')     // only user comments
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Group by ticket_no for badge count
    $unseenCounts = $latestComments
        ->groupBy('ticket_no')
        ->map(fn($comments) => $comments->count());

    $view->with([
        'latestComments' => $latestComments,
        'unseenCounts' => $unseenCounts,
    ]);
});


    // View::composer('access.layout', function ($view) {

    //     $latestComments = collect();

    //     $currentUser = Auth::user();

    //     $latestComments = TicketDtl::where('user_id', $currentUser->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->groupBy('ticket_no');

    //     $ticketChat = Comments::all();

    //     $view->with([
    //         'latestComments' => $latestComments,
    //         'ticketChat' => $ticketChat, // exact name
    //     ]);
    // });


// View::composer('access.layout', function ($view) {
//     $currentUser = Auth::user();

//     $latestComments = TicketDtl::where('user_id', $currentUser->id)
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->groupBy('ticket_no');

//     // Count unseen messages per ticket
//     $ticketChat = Comments::where('user_id', $currentUser->id)
//         ->get()
//         ->groupBy('ticket_no');

//     $unseenCounts = Comments::where('com_stat_user', 0)
//     ->where(function ($q) use ($currentUser) {
//         $q->where('user_id', $currentUser->id)
//           ->orWhere('admin_id', $currentUser->id); // if needed
//     })
//     ->get()
//     ->groupBy('ticket_no')
//     ->map(fn($comments) => $comments->count());

//     $view->with([
//         'latestComments' => $latestComments,
//         'ticketChat' => $ticketChat,
//         'unseenCounts' => $unseenCounts,
//     ]);
// });

// View::composer('access.layout', function ($view) {
//     $currentUser = Auth::user();

//     $latestComments = TicketDtl::where('user_id', $currentUser->id)
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->groupBy('ticket_no');

//     // Count unseen messages per ticket
//     $ticketChat = Comments::where('user_id', $currentUser->id)
//         ->get()
//         ->groupBy('ticket_no');

//     $unseenCounts = Comments::where('com_stat_user', 0)
//     ->where(function ($q) use ($currentUser) {
//         $q->where('user_id', $currentUser->id)
//           ->orWhere('admin_id', $currentUser->id); // if needed
//     })
//     ->get()
//     ->groupBy('ticket_no')
//     ->map(fn($comments) => $comments->count());

//     $view->with([
//         'latestComments' => $latestComments,
//         'ticketChat' => $ticketChat,
//         'unseenCounts' => $unseenCounts,
//     ]);
// });

View::composer('access.layout', function ($view) {
    $currentUser = Auth::user();

    $latestComments = TicketDtl::where('user_id', $currentUser->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('ticket_no');

    // All comments for this user
    $ticketChat = Comments::where('user_id', $currentUser->id)
        ->get()
        ->groupBy('ticket_no');

    // Count unseen messages per ticket **for this user only**
    $unseenCounts = Comments::where('com_stat_user', 0)
        ->where('user_id', $currentUser->id)
        ->get()
        ->groupBy('ticket_no')
        ->map(fn($comments) => $comments->count());

    $view->with([
        'latestComments' => $latestComments,
        'ticketChat' => $ticketChat,
        'unseenCounts' => $unseenCounts,
    ]);
});
    }
}
