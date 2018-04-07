<?php

use App\Reply;
use App\Thread;
use App\Attachment;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/reply/{id}/upload', function (Request $request, $replyId) {
    $file = $request->file('attachment');
    $name = $file->getClientOriginalName();
    $file->storeAs('public/attachments/reply/'.$replyId, $name);

    $attachment = new Attachment([
        'name' => $name,
        'path' => '/attachments/reply/'.$replyId.'/'.$name
    ]);

    $reply = Reply::findOrFail($replyId);

    $reply->attachments()->save($attachment);

    return "Uploaded";
});

Route::post('/thread/{id}/upload', function (Request $request, $threadId) {
    $file = $request->file('attachment');
    $name = $file->getClientOriginalName();
    $file->storeAs('public/attachments/thread/'.$threadId, $name);

    $attachment = new Attachment([
        'name' => $name,
        'path' => '/attachments/thread/'.$threadId.'/'.$name
    ]);

    $thread = Thread::findOrFail($threadId);

    $thread->attachments()->save($attachment);

    return "Uploaded";
});

Route::delete('/attachment/{attachment}', function (Attachment $attachment) {
    $attachment->delete();
    return "Deleted";
});
