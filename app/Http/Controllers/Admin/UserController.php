<?php

namespace App\Http\Controllers\Admin; // Note o \Admin aqui

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = \App\Models\User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Importante: mantém o termo de busca ao mudar de página

        return view('pages.admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('pages.admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'status'    => 'required|in:active,inactive',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        $data = $request->only(['name', 'last_name', 'email', 'status']);

        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário ' . $request->name . ' foi cadastrado com sucesso.');
    }

    public function update(Request $request, User $user)
    {

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser && $authUser->is($user) && $request->status === 'inactive') {
            return back()->with('error', 'Você não pode desativar o seu próprio usuário.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:8',
        ]);


        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }


        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuário ' . $request->name . ' foi atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser && $authUser->is($user)) {
            return back()->with('error', 'Você não pode deletar o seu próprio usuário.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('success', 'Usuário removido com sucesso.');
    }
}
