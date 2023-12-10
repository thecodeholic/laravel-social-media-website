<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::get('/g/{group:slug}', [GroupController::class, 'profile'])
    ->name('group.profile');

Route::get('/group/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])
    ->name('group.approveInvitation');

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])
        ->name('profile.updateImages');


    // Groups
    Route::post('/group', [GroupController::class, 'store'])
        ->name('group.create');
    Route::put('/group/{group:slug}', [GroupController::class, 'update'])
        ->name('group.update');

    Route::post('/group/update-images/{group:slug}', [GroupController::class, 'updateImage'])
        ->name('group.updateImages');

    Route::post('/group/invite/{group:slug}', [GroupController::class, 'inviteUsers'])
        ->name('group.inviteUsers');

    Route::post('/group/join/{group:slug}', [GroupController::class, 'join'])
        ->name('group.join');

    Route::post('/group/approve-request/{group:slug}', [GroupController::class, 'approveRequest'])
        ->name('group.approveRequest');

    Route::delete('/group/remove-user/{group:slug}', [GroupController::class, 'removeUser'])
        ->name('group.removeUser');

    Route::post('/group/change-role/{group:slug}', [GroupController::class, 'changeRole'])
        ->name('group.changeRole');

//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::get('/post/{post}', [PostController::class, 'view'])
        ->name('post.view');

    Route::post('/post', [PostController::class, 'store'])
        ->name('post.create');

    Route::put('/post/{post}', [PostController::class, 'update'])
        ->name('post.update');

    Route::delete('/post/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');

    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])
        ->name('post.download');

    Route::post('/post/{post}/reaction', [PostController::class, 'postReaction'])
        ->name('post.reaction');

    Route::post('/post/{post}/comment', [PostController::class, 'createComment'])
        ->name('post.comment.create');

    // Comments
    Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])
        ->name('comment.delete');

    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
        ->name('comment.update');

    Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])
        ->name('comment.reaction');

});

require __DIR__ . '/auth.php';
