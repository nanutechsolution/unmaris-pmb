<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            ActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'LOGIN',
                'subject' => 'Auth System',
                'description' => 'Pengguna berhasil masuk ke sistem.',
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
            ]);
        });
    }
}
