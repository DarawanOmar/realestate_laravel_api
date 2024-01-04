<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Api;

// Authirizted
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::post('logout', [Api::class,'logout']);
    Route::post('email/verification-notification', [Api::class,'sendverificationEmail']);
    Route::get('profile', [Api::class,'profile']);
    Route::get('profile/properties', [Api::class,'profileProperties']);
    Route::post('profile/properties/add', [Api::class,'AddProperty']);
    Route::delete('profile/properties/delete/{id}', [Api::class,'delete']);
    Route::post('profile/properties/upload/image/{id}', [Api::class,'uploadImage']);
    Route::put('profile/updateproperties/{id}', [Api::class,'updateproperty']);
    Route::get('checkadded/{id}', [Api::class,'checkadded']);
    Route::get('propertyfavoraite2', [Api::class,'propertyfavoraite2']);
    Route::post('createtablefavoraite', [Api::class,'favoraitecreate']);
    Route::post('favoraiteaddcomment', [Api::class,'favoraiteaddcomment']);
    Route::post('propertyfavoraite', [Api::class,'addpropertyfavoraite']);
    Route::post('propertyfavoraite/addcomment/{id}', [Api::class,'addcomment']);
    Route::get('propertyfavoraite/{id}', [Api::class,'propertyfavoraite']);
    Route::post('addimageprofile', [Api::class,'addImageProfile']);
    Route::post('test', [Api::class,'testing']);
    Route::post('addbio', [Api::class,'addBio']);
    Route::post('changepassword', [Api::class,'chnagepassword']);
    Route::post('changename', [Api::class,'changename']);
    Route::post('changeemail', [Api::class,'changeemail']);
    
    Route::post('addnewemail', [Api::class,'addnewemail']);
    Route::post('addnickname', [Api::class,'addnickname']);
});


// unauthorized
Route::post('contact',[Api::class, 'postcontact']);
Route::put('contact/{id}',[Api::class, 'updatecontact']);
Route::delete('contact/{id}',[Api::class, 'deletecontact']);
Route::get('contact',[Api::class, 'getcontact']);

Route::get('home',[Api::class, 'home']);
Route::get('properties',[Api::class, 'properties']);
Route::get('sortprice',[Api::class, 'sortPrice']);
Route::get('filterbetween',[Api::class, 'filterbetween']);
Route::get('allproperties',[Api::class, 'allproperties']);
Route::get('properties/{id}',[Api::class, 'property']);
Route::get('propertiesUser/{id}',[Api::class, 'propertiesUser']);
Route::get('users',[Api::class, 'users']);
Route::get('users/{id}',[Api::class, 'user']);
Route::post('login',[Api::class, 'login']);
Route::post('register',[Api::class, 'register']);
Route::post('verify-email/{id}/{hash}',[Api::class, 'verify'])->name('verification.verify');
Route::post('forgot',[Api::class, 'forgot']);
Route::post('restpassword',[Api::class, 'reset'])->name('password.reset');
Route::get('catigories',[Api::class, 'catigories']);
Route::get('cities',[Api::class, 'cities']);



