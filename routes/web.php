<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ArticlesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>['guest']],function(){
    Route::get('/', function () {
        return view('login.login');
    });

    //login
    Route::get('/login', [LoginAuthController::class, 'getLogin'])->name('getLogin');
    Route::post('/login', [LoginAuthController::class, 'postLogin'])->name('postLogin');

});

    Route::group(['middleware'=>['login_auth']],function(){
       //logout
    Route::get('/logout', [MasterController::class,'logout'])->name('logout');

    //Main page
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [PagesController::class, 'allTickets'])->name('allTickets');
    Route::get('/my-tickets', [PagesController::class, 'myTickets'])->name('myTickets');
    Route::get('/auditTrails-logs', [PagesController::class, 'auditLogs'])->name('auditLogs');
    Route::get('/articles', [PagesController::class, 'articles'])->name('articles');
    // Route::get('/reports-page', [PagesController::class, 'reportsPage'])->name('reportsPage');

    //Generate reports
    Route::get('/reports-pages', [PagesController::class, 'ticketReports'])->name('ticketReports');
    // web.php
    Route::get('/reports/pdf', [PagesController::class, 'downloadTicketReportsPDF'])->name('downloadTicketReportsPDF');
    Route::get('/reports/survey-pdf', [PagesController::class, 'downloadSurveyReportsPDF'])->name('downloadSurveyReportsPDF');

//     Route::get('/work-progress', function () {
//     return view('partials.workProgress');
// })->name('work.progress');
    Route::post('work-progress/add', [PagesController::class, 'addWorkProgress'])->name('addWorkProgress');
    Route::get('/work-progress/edit/{id}', [PagesController::class, 'editWork'])->name('editWork');
    Route::put('/work-progress/update/{id}', [PagesController::class, 'updateWork'])->name('updateWork');

    Route::delete('work-progress/delete/{id}', [PagesController::class, 'deleteWorkProgress'])->name('deleteWorkProgress');

    // for survey report

    //Adding of Articles
    Route::post('/addArticles', [ArticlesController::class, 'addArticles'])->name('addArticles');
    Route::get('/articles-faQs', [ArticlesController::class, 'articlesUser'])->name('articlesUser');

    //users
    Route::get('/users', [UserController::class, 'userView'])->name('userView');
    Route::post('/users', [UserController::class, 'addUser'])->name('users.addUser');
    Route::get('/users/edit/{id}', [UserController::class, 'userEdit'])->name('userEdit');
    Route::put('/users/update/{id}', [UserController::class, 'userUpdate'])->name('userUpdate');
    Route::put('/users/update-pic/{id}', [UserController::class, 'updateProfilePic'])->name('updateProfilePic');

    Route::get('/search-suggestions', [AccessController::class, 'suggestions'])->name('search.suggestions');

    //access pages for users
    Route::get('/home', [AccessController::class, 'home'])->name('home');
    Route::get('/request-form', [AccessController::class, 'requestForm'])->name('requestForm');
    Route::post('/request-form', [AccessController::class, 'storeRequestForm'])->name('storeRequestForm');
    Route::get('/home/{ticketNo}', [AccessController::class, 'ticketList'])->name('ticketList');
    Route::get('/client-satisfaction/{ticket_no}', [AccessController::class, 'clientSatisfaction'])->name('clientSatisfaction');
    Route::get('/created-ticket/{ticketNo}', [AccessController::class, 'createdTicket'])->name('createdTicket');

    Route::put('/edit-ticketSurvey/{ticket_no}', [AccessController::class, 'updateSurvey'])->name('updateSurvey');

    
    
    Route::get('/fetch-comments/{ticketNo}', [AccessController::class, 'fetchComments'])->name('fetchComments');

    Route::get('/client-logs', [AccessController::class, 'clientLogs'])->name('clientLogs');
    Route::get('/mis-support', [AccessController::class, 'misPersonnel'])->name('misPersonnel');
    


    //admin edit ticket
    Route::get('/edit-ticket/{ticketNo}', [TicketController::class, 'editTicket'])->name('editTicket');
    Route::put('/edit-ticket/{ticketNo}', [TicketController::class, 'updateTicket'])->name('updateTicket');
    Route::post('/ticket-details/{ticketNo}', [TicketController::class, 'closeTicket'])->name('closeTicket');
    Route::get('/ticket-details/{ticketNo}', [TicketController::class, 'ticketDetails'])->name('ticketDetails');

    //ticket comments
    Route::post('/ticket-comments', [TicketController::class, 'storeComments'])->name('storeComments');
    

});
