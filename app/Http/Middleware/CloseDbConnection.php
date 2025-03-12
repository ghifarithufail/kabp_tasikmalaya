<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloseDbConnection
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Tutup koneksi database setelah request selesai
        DB::disconnect();

        return $response;
    }
}
