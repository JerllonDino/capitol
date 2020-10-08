<?php

Route::get('/', function () {
    return redirect()->route('session.login');
});

Route::get('login', 'SessionController@index')->name('session.index');
Route::post('login', 'SessionController@login')->name('session.login');
Route::get('logout', 'SessionController@logout')->name('session.logout');

# Routes requiring logged-in users
Route::group(['middleware' => 'auth'], function() {
    Route::resource('user', 'UserController');
    Route::resource('group', 'GroupController');
    Route::resource('group.permission', 'GroupPermissionController',
        ['only' => ['index', 'show', 'update', 'edit']]
    );
    Route::resource('backup', 'BackupController',
        ['only' => ['index', 'store', 'show', 'destroy']]
    );
    Route::post('bakcup/restore/{id}', 'BackupController@restore')->name('backup.restore');

    Route::get('audit', 'AuditController@index')->name('audit.index');
    Route::get('dashboard', 'SessionController@show_dashboard')->name('profile.dashboard');
    Route::post('datatables/filter', 'DatatablesController@filter')->name('datatables.filter');
    Route::post('group/destroy/{id}', 'GroupController@destroyChoice')->name('group.delete');

    Route::get('settings', 'SettingsController@edit')->name('settings.edit');
    Route::post('settings', 'SettingsController@update')->name('settings.update');

    Route::get('profile', 'SessionController@edit_profile')->name('profile.edit');
    Route::post('profile/{id}', 'SessionController@update_profile')->name('profile.update');

    Route::get('datatables/{category}', 'DatatablesController@getdata')->name('datatables');
    Route::post('ajax','AjaxController@index')->name('ajax');

});
