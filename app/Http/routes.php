<?php

Route::group(['prefix' => 'api/v1/'], function(){
	Route::resource('lesson', 'LessonController');
	Route::resource('tag', 'TagController');
	Route::resource('user', 'UserController');
});
