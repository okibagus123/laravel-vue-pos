<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8', // Password opsional saat edit
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Hanya ubah password jika form password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        // Mencegah akun yang sedang login menghapus dirinya sendiri
      if (auth()->user()->is($user)) {
            return redirect()->back()->withErrors(['error' => 'Tidak dapat menghapus akun yang sedang digunakan!']);
        }

        $user->delete();
        return redirect()->back();
    }
}