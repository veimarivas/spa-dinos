<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Trabajadore;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $estado = $request->get('estado', '');

        $query = User::with('persona');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('persona', function ($q2) use ($search) {
                        $q2->where('carnet', 'like', "%{$search}%")
                            ->orWhere('nombres', 'like', "%{$search}%")
                            ->orWhere('apellido_paterno', 'like', "%{$search}%")
                            ->orWhere('apellido_materno', 'like', "%{$search}%");
                    });
            });
        }

        if ($estado && in_array($estado, ['Activo', 'Inactivo'])) {
            $query->where('estado', $estado);
        }

        $users = $query->paginate(10)->appends([
            'search' => $search,
            'estado' => $estado
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users.partials.table-body', compact('users'))->render(),
                'pagination' => $users->links('pagination::bootstrap-5')->toHtml(),
                'filters' => [
                    'search' => $search,
                    'estado' => $estado
                ]
            ]);
        }

        $roles = Role::all();

        return view('admin.users.listar', compact('users', 'roles'));
    }

    public function verificarEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id' => 'nullable|exists:users,id'
        ]);

        $email = $request->input('email');
        $id = $request->input('id');

        $query = User::where('email', $email);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role' => 'required|exists:roles,name',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        $user = User::findOrFail($request->id);

        // Actualizar información básica
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'estado' => $request->estado
        ]);

        // Sincronizar rol
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'msg' => 'Usuario actualizado correctamente.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $user = User::with('persona')->findOrFail($request->id);

        // La nueva contraseña es el carnet de la persona
        $carnet = $user->persona?->carnet ?? Str::random(8);
        $user->password = Hash::make($carnet);
        $user->save();

        return response()->json([
            'success'  => true,
            'msg'      => 'Contraseña restablecida al carnet correctamente.',
            'password' => $carnet,
            'username' => $user->email,
        ]);
    }

    public function crearCuentaTrabajador(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'email'         => 'required|email|unique:users,email',
            'role'          => 'required|exists:roles,name',
        ]);

        $trabajador = Trabajadore::with('persona')->findOrFail($request->trabajador_id);

        // Verificar que no tenga cuenta ya
        if (User::where('persona_id', $trabajador->persona_id)->exists()) {
            return response()->json(['success' => false, 'msg' => 'Este trabajador ya tiene una cuenta de usuario.'], 422);
        }

        $persona  = $trabajador->persona;
        $nombre   = trim("{$persona->nombres} {$persona->apellido_paterno}" . ($persona->apellido_materno ? " {$persona->apellido_materno}" : ''));
        $password = $persona->carnet ?? Str::random(8);

        $user = User::create([
            'persona_id' => $trabajador->persona_id,
            'name'       => $nombre,
            'email'      => $request->email,
            'password'   => Hash::make($password),
            'estado'     => 'Activo',
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'success'  => true,
            'msg'      => 'Cuenta creada correctamente.',
            'email'    => $request->email,
            'password' => $password,
        ]);
    }

    public function obtenerUserData(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $user = User::with('roles')->findOrFail($request->id);

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames()->toArray()
        ]);
    }
}
