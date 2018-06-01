<?php

Route::resource('users', 'UserController')->except(['create', 'edit']);
