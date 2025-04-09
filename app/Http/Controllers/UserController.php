<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;

class UserController extends Controller
{
    public function showUser()
    {
        if (auth()->check() && auth()->user()->role == 1) {
            $user = User::where('role', 0)->paginate(5);
            $about = About::pluck('value', 'part_name')->toArray();
            return view("pages.user", compact("user", "about"));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melihat halaman user.');
        }
    }
    public function create()
    {
        if (auth()->check() && auth()->user()->role == 1) {
            return view("add.add-user");
        } else {
            return redirect()->route('user')->with('error', 'Anda tidak memiliki akses untuk melihat halaman user.');
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('user')->with('success', 'Data user berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal menambahkan data user: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        if (auth()->check() && auth()->user()->role == 1) {
            $user = User::find($id);
            return view("edit.edit-user", compact("user"));
        } else {
            return redirect()->route('user')->with('error', 'Anda tidak memiliki akses untuk melihat halaman user.');
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        try {
            $user->update($request->all());
            return redirect()->route('user')->with('success', 'Data user berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal memperbarui data user: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $user = User::find($id);

        try {
            $user->delete();

            return redirect()->route('user')->with('success', 'Data user berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal menghapus data user: ' . $e->getMessage());
        }
    }
    public function verifyEmail($id)
    {
        // if (auth()->user()->role !== 1) {
        //     return redirect()->route('user')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        // }

        $user = User::find($id);
        // $user->email_verified_at = Carbon::now();
        // $user->save();

        // Check if user exists
        if (!$user) {
            return redirect()->route('user')->with('error', 'User not found.');
        }

        // Check if the email is not verified
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('user')->with('status', 'Verification email sent to ' . $user->email);
        }

        return redirect()->route('user')->with('status', 'Email telah diverifikasi.');
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $about = About::pluck('value', 'part_name')->toArray();

        $user = User::where('role', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%");
            })
            ->paginate(5);

        return view('pages.user', ['user' => $user], compact('about'))
            ->with('keyword', $keyword);
    }
}
