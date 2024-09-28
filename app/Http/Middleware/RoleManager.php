<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            // ถ้าไม่ได้ล็อกอิน ให้กลับไปหน้า login
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        // ตรวจสอบ role และอนุญาตเฉพาะ role ที่ตรงกัน
        switch ($role) {
            case 'admin':
                if ($authUserRole == 0) {
                    return $next($request);
                }
                break;

            case 'it':
                if ($authUserRole == 1) {
                    return $next($request);
                }
                break;

            case 'user':
                if ($authUserRole == 2) {
                    return $next($request);
                }
                break;
        }

        // หาก role ไม่ตรงกัน ส่งไปที่หน้าที่เหมาะสมตามบทบาทของผู้ใช้
        switch ($authUserRole) {
            case 0:
                return redirect()->route('admin');
            case 1:
                return redirect()->route('it');
            case 2:
                return redirect()->route('dashboard');
        }

        // หากไม่ตรงเงื่อนไขทั้งหมด ส่งกลับไปหน้า login
        return redirect()->route('login');
    }
}
