<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // إذا كان المستخدم مسجل دخول بالفعل، وجهه للوحة التحكم
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('dashboard.auth.sign-in');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // إذا كان هناك intended URL، وجه إليه، وإلا وجه للوحة التحكم
            return redirect()->route('dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }

        return back()->withErrors([
            'email' => 'البيانات المدخلة غير صحيحة.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    /**
     * Show registration form (optional)
     */
    public function showRegisterForm()
    {
        return view('dashboard.auth.register');
    }

    /**
     * Handle registration request (optional)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء الحساب بنجاح');
    }
}
