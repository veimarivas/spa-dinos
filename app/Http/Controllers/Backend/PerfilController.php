<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('persona', 'roles');
        return view('admin.perfil.index', compact('user'));
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual'    => 'required|string',
            'password_nuevo'     => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return response()->json(['success' => false, 'msg' => 'La contraseña actual no es correcta.'], 422);
        }

        $user->password = Hash::make($request->password_nuevo);
        $user->save();

        return response()->json(['success' => true, 'msg' => 'Contraseña actualizada correctamente.']);
    }
}
