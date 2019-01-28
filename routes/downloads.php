<?php

Route::get('/eventdownload/{filename}', function($filename) {
    if (file_exists(public_path('files/events/' .$filename))) {
        return response()->download(public_path('files/events/' .$filename));
    }
    redirect()->back();
});
