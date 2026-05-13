<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'admin_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('admin_id', $credentials['admin_id'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()
                ->withErrors(['admin_id' => 'Invalid admin ID or password.'])
                ->onlyInput('admin_id');
        }

        $this->setAdminSession($request, $admin);

        return redirect()->route('admin.dashboard');
    }

    public function apiLogin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'admin_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('admin_id', $credentials['admin_id'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['message' => 'Invalid admin ID or password.'], 401);
        }

        $this->setAdminSession($request, $admin);

        return response()->json([
            'admin' => $admin,
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->clearAdminSession($request);

        return redirect()->route('admin.login');
    }

    public function apiLogout(Request $request): JsonResponse
    {
        $this->clearAdminSession($request);

        return response()->json(['message' => 'Logout successful']);
    }

    private function setAdminSession(Request $request, Admin $admin): void
    {
        $request->session()->regenerate();
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->admin_id);
    }

    private function clearAdminSession(Request $request): void
    {
        $request->session()->forget(['admin_id', 'admin_name']);
        $request->session()->regenerateToken();
    }
}
