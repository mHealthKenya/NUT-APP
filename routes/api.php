<?php

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

Route::post('call', ['uses' => 'VoiceLogController@sendVoice']);
Route::post('caller_one', ['uses' => 'VoiceLogController@getDigits']);
Route::post('digits', ['uses' => 'VoiceLogController@saveDigits']);
Route::post('message', ['uses' => 'VoiceLogController@send']);
Route::post('addcaregiver', ['uses' => 'VoiceLogController@add_care_giver']);
Route::post('updatecaregiver', ['uses' => 'VoiceLogController@update_care_giver']);
Route::post('getcaregivers', ['uses' => 'VoiceLogController@get_care_givers']);
Route::post('login', ['uses' => 'VoiceLogController@login']);
Route::post('sendvoice', ['uses' => 'VoiceLogController@sendVoice']);
Route::post('voicereceiver', ['uses' => 'VoiceLogController@voice_receiver']);