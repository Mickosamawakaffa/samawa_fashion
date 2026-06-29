<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri!');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Role user berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.customers.index')->with('success', 'User berhasil dihapus');
    }
}
