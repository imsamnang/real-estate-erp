<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request, FlasherInterface $flasher)
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $login = trim($data['login']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $login)->first();

        if (! $user || ! Auth::attempt([$field => $login, 'password' => $data['password']], (bool) $request->boolean('remember'))) {
            LoginHistory::create([
                'user_id' => $user?->id,
                'email_or_username' => $login,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1024),
                'status' => 'failed',
                'failure_reason' => 'invalid_credentials',
                'logged_in_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'login' => __('messages.auth.failed'),
            ]);
        }

        if ($user->status !== 'active') {
            Auth::logout();

            LoginHistory::create([
                'user_id' => $user->id,
                'email_or_username' => $login,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1024),
                'status' => 'failed',
                'failure_reason' => 'account_disabled',
                'logged_in_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'login' => __('messages.auth.account_disabled'),
            ]);
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        LoginHistory::create([
            'user_id' => $user->id,
            'email_or_username' => $login,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1024),
            'status' => 'success',
            'logged_in_at' => now(),
        ]);

        $flasher->addSuccess(__('messages.common.sign_in').' — '.$user->name);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request, FlasherInterface $flasher)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $flasher->addInfo(__('messages.common.logout'));

        return redirect()->route('admin.login');
    }
}
