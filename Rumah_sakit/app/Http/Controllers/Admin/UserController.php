<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('poli')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $polis = Poli::all();
        return view('admin.users.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,dokter,pasien',
            'poli_id' => ['nullable', 'required_if:role,dokter', 'exists:polis,id'],
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
                'role' => $request->role,
                'poli_id' => $request->role === 'dokter' ? $request->poli_id : null,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        $polis = Poli::all();
        return view('admin.users.edit', compact('user', 'polis'));
    }

    
    public function update(Request $request, User $user)
{
    Log::info('Updating user', ['id' => $user->id, 'incoming' => $request->all()]);

    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|confirmed|min:8',
        'role' => 'required|in:admin,dokter,pasien',
        'poli_id' => ['nullable', 'required_if:role,dokter', 'exists:polis,id'],
    ]);

    if ($validator->fails()) {
        Log::info('Validation failed on update', ['errors' => $validator->errors()->toArray(), 'input' => $request->all()]);
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'poli_id' => $request->role === 'dokter' ? $request->poli_id : null,
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    try {
        Log::info('Before update user state', ['before' => $user->toArray()]);
        $result = $user->update($data);
        Log::info('Update returned', ['result' => $result]);
        $userFresh = $user->fresh();
        Log::info('After update user state', ['after' => $userFresh ? $userFresh->toArray() : null]);

        // for easier debugging: if AJAX/JSON requested, return JSON so you can view in Network tab
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['ok' => true, 'result' => $result, 'user' => $userFresh]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    } catch (\Throwable $e) {
        Log::error('User update error: '.$e->getMessage(), [
            'user_id' => $user->id,
            'input' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}