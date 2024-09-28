<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log; // นำเข้า Log
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        Log::info('Redirecting to Google for authentication.'); // Log การ redirect
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            // รับข้อมูลผู้ใช้จาก Google
            $googleUser = Socialite::driver('google')->user();
         

           // ตรวจสอบว่าอีเมลเป็น @tsu.ac.th หรือไม่
if (!Str::endsWith($googleUser->getEmail(), '@tsu.ac.th')) {
    
    return Redirect::route('login')->with('error', 'ต้องใช้อีเมล @tsu.ac.th เท่านั้น');
}


            // ตรวจสอบว่าผู้ใช้มีในฐานข้อมูลหรือไม่
            $user = User::where('email', $googleUser->getEmail())->first();
          

            if ($user) {
                // เข้าสู่ระบบ
                Auth::login($user);
                session()->regenerate(); // สร้าง session ใหม่หลังจากเข้าสู่ระบบสำเร็จ
                
            } else {
                // สร้างผู้ใช้ใหม่
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // ใช้ Str::random แทน str_random
                    'role' => 2, // กำหนด role ให้กับผู้ใช้ใหม่ (หรือค่าที่เหมาะสม)
                    'email_verified_at' => now(), // ตั้งค่าอีเมลว่าได้รับการตรวจสอบ
                    'google_id' => $googleUser->getId(), // บันทึก Google ID ถ้าต้องการ
                ]);
                
                // เข้าสู่ระบบด้วยบัญชีใหม่
                Auth::login($user);
                session()->regenerate(); // สร้าง session ใหม่หลังจากเข้าสู่ระบบสำเร็จ
               
            }

            // Redirect ตามบทบาทของผู้ใช้
            if ($user->role == 'admin') {
                
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role == 'it') {
           
                return redirect()->intended('/it/dashboard');
            } else {
                
                return redirect()->intended('/dashboard');
            }
        } catch (\Exception $e) {
           
            return redirect()->route('login')->with('error', 'การเข้าสู่ระบบล้มเหลว: ' . $e->getMessage());
        }
    }
}
