<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::macro('inUserTimezone', function() {
            return $this->tz(auth()->user()?->timezone ?? 'America/New_York');
        });

        Str::macro('possessive', function ($string) {
            return $string . '\'' . (
                Str::endsWith($string, ['s', 'S']) ? '' : 's'
            );
        });
    }
}
