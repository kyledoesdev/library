<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::command('kyles-library:refresh-books')
    ->timezone('America/New_York')
    ->daily()
    ->onSuccess(function() {
        Log::info('Reminded users to complete rankings successfully.');
    })
    ->onFailure(function() {
        Log::info('Something went wrong sending ranking reminder emails.');
    });
