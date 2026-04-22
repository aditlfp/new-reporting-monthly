<?php

namespace App\Http\Middleware;

use App\Models\UserSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $settings = UserSettings::query()->where('user_id', auth()->id())->first();
            $stored = is_array($settings?->data_theme) ? $settings->data_theme : [];
            $rawThemeMode = (string) ($stored['theme_mode'] ?? 'light');
            if ($rawThemeMode === 'silk') {
                $rawThemeMode = 'light';
            }
            $themeMode = in_array($rawThemeMode, ['dark', 'light'], true)
                ? $rawThemeMode
                : 'light';

            View::share('themeSettings', [
                'theme_mode' => $themeMode,
                'splash_on_login' => filter_var($stored['splash_on_login'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        return $next($request);
    }
}
