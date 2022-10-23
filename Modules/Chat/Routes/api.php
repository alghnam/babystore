<?php

use Illuminate\Http\Request;
use Modules\Chat\Http\Controllers\API\Admin\ChatController as ChatControllerAdmin;
use Modules\Chat\Http\Controllers\API\User\ChatController as ChatControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('chats')->group(function(){
        Route::get('/get-all-chats-recived-paginates', [ChatControllerAdmin::class,'getAllChatsRecivedPaginates'])->name('api.admin.chats.get-all');
        Route::get('/get-all-chats-sended-paginates', [ChatControllerAdmin::class,'getAllChatsSendedPaginates'])->name('api.admin.chats.get-all-chats-sended');
        Route::get('trash-sended', [ChatControllerAdmin::class,'trashAllChatsSended'])->name('api.admin.chats.trash-sended');
        Route::get('trash-recieved', [ChatControllerAdmin::class,'trashAllChatsRecieved'])->name('api.admin.chats.trash-recieved');
        Route::get('restore-all-sended', [ChatControllerAdmin::class,'restoreAllChatsSended'])->name('api.admin.chats.restore-all-s');
        Route::get('restore-all-recieved', [ChatControllerAdmin::class,'restoreAllChatsRecieved'])->name('api.admin.chats.restore-all-r');
        Route::get('restore/{id}', [ChatControllerAdmin::class,'restore'])->name('api.admin.chats.restore');
        Route::post('store', [ChatControllerAdmin::class,'store'])->name('api.admin.chats.store');
        Route::get('show/{id}', [ChatControllerAdmin::class,'show'])->name('api.admin.chats.show');
        Route::post('update/{id}', [ChatControllerAdmin::class,'update'])->name('api.admin.chats.update');
        Route::get('destroy/{id}', [ChatControllerAdmin::class,'destroy'])->name('api.admin.chats.destroy');        
        Route::get('force-delete/{id}', [ChatControllerAdmin::class,'forceDelete'])->name('api.admin.chats.force-delete');

    });
});

Route::prefix('chats')->middleware(['auth:api'])->namespace('API')->group(function(){
        Route::get('rooms', [ChatControllerUser::class,'rooms'])->name('api.admin.chats.rooms');
        Route::get('messages-room/{roomId}', [ChatControllerUser::class,'messages'])->name('api.admin.chats.messages');
        Route::post('new-message/{roomId}', [ChatControllerUser::class,'newMessage'])->name('api.admin.chats.new-message');
});