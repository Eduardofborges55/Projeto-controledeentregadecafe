<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\loginRequest;
use App\Models\GerenciamentoDeFila;

class UserController extends Controller
{
   public function criarUsuario(TokenRequest $request)
{
    $validado = $request->validated();

    $user = new User();
    $user->name = $validado['name'];
    $user->email = $validado['email'];
    $user->password = Hash::make($validado['password']);
    $user->save();

    // ✅ Fila automática - após salvar o usuário
    $ultimaPosicao = GerenciamentoDeFila::max('position') ?? 0;

    GerenciamentoDeFila::create([
        'user_id' => $user->id,
        'position' => $ultimaPosicao + 1,
        'ativo' => true,
    ]);

    return response()->json([
        'message' => 'Usuário criado e adicionado à fila com sucesso!',
        'user' => $user
    ], 201);
}


    public function listarUsuarios()
    {
        return User::all();
    }

    public function mostrarUsuario($id)
    {
        return User::findOrFail($id);
    }

    public function atualizarUsuario(Request $request, $id)
    {
        
        $validado = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::findOrFail($id);
        $user->fill($validado);
        $user->save();

        return ['Message' => 'Usuário atualizado com sucesso!'];
    }

    public function deletarUsuario($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return ['Message' => 'Usuário deletado com sucesso!'];
    }


    public function loginUsuario(loginRequest $request)
    {
        $dados = $request-> validated();
        $user = User::where('email', $dados['email'])->first();

        if (!$user || !Hash::check($dados ['password'], $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $user->tokens()->delete();

        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'token' => $token
        ]);
    }

    public function criarAdmin(TokenRequest $request)
    {
        $validado = $request->validated();

        $admin = new User();
        $admin->name = $validado['name'];
        $admin->email = $validado['email'];
        $admin->password = Hash::make($validado['password']);
        $admin->role = 'admin';
        $admin->save();
        return ['Message' => 'Admin criado com sucesso!'];
    }

    public function listarAdmins()
    {
        return User::where('role', 'admin')->get();
    }

    public function mostrarAdmin($id)
    {
        return User::findOrFail($id);
    }

    public function atualizarAdmin (Request $request, $id)
    {
        
        $validado = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ]);
        $admin = User::findOrFail($id);
        $admin->fill($validado);
        $admin->save();

        return ['Message' => 'Admin atualizado com sucesso!'];
    }

    public function deletarAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return ['Message' => 'Admin deletado com sucesso!'];
    }
}
?>