<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;

class UserController extends Controller
{
    public function showUser()
    {
        $user = User::where('role', 0)->paginate(5);
        return view("pages.user", compact("user"));
    }
    public function create()
    {
        return view("add.add-user");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user');
    }
    public function edit($id)
    {
        $user = User::find($id);
        return view("edit.edit-user", compact("user"));
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return redirect()->route('user');
    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user');
    }
    public function verifyEmail($id)
    {
        // Pastikan hanya admin yang bisa melakukan ini
        // if (auth()->user()->role !== 1) {
        //     return redirect()->route('user')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        // }

        // Verifikasi email dan set kolom email_verified_at
        $user = User::find($id);
        // $user->email_verified_at = Carbon::now();
        // $user->save();

        // Check if user exists
        if (!$user) {
            return redirect()->route('user')->with('error', 'User not found.');
        }

        // Check if the email is not verified
        if (!$user->hasVerifiedEmail()) {
            // Send email verification notification
            $user->sendEmailVerificationNotification();

            return redirect()->route('user')->with('status', 'Verification email sent to ' . $user->email);
        }

        return redirect()->route('user')->with('status', 'Email telah diverifikasi.');
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $user = User::where('role', 0) 
        ->where(function($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phone', 'like', "%$keyword%");
        })
        ->paginate(5);

        return view('pages.user', ['user' => $user]);
    }
}
