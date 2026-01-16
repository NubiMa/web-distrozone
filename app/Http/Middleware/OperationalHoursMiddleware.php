<?php

namespace App\Http\Middleware;

use App\Models\StoreSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationalHoursMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware checks if the online store is open for business.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isOpen = StoreSetting::isOnlineStoreOpen();

        if (!$isOpen) {
            $openTime = StoreSetting::get('online_open_time', '10:00');
            $closeTime = StoreSetting::get('online_close_time', '17:00');

            return response()->json([
                'success' => false,
                'message' => "Maaf, toko online sedang tutup. Jam operasional: {$openTime} - {$closeTime} WIB",
                'operational_hours' => [
                    'open_time' => $openTime,
                    'close_time' => $closeTime,
                    'is_open' => false,
                ],
            ], 403);
        }

        return $next($request);
    }
}
