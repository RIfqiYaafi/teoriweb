<?php

namespace App\Http\Controllers\Api;

use App\Models\Post; // Ensure this line is here
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // Get all users (since you're now working with users, not posts)
        $users = User::latest()->paginate(5);
        
        // Return the collection of users as a resource
        return response()->json([
            'success' => true,
            'message' => 'List of Users',
            'data' => $users
        ]);
    }

    /**
     * store
     *
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',  // email validation with uniqueness check
            'password' => 'required|string|min:8|confirmed', // password validation with confirmation check
            'username' => 'required|string|unique:users,username',  // username validation
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Continue with storing the user
        // Assuming password is hashed before saving:
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'username' => $request->username,
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * show
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($id);

        // Jika pengguna tidak ditemukan, kembalikan response error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Jika pengguna ditemukan, kembalikan data pengguna
        return response()->json([
            'success' => true,
            'message' => 'User details retrieved successfully',
            'data' => $user
        ]);
    }

    /**
     * update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($id);

        // Jika pengguna tidak ditemukan, kembalikan response error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,  // email harus unik kecuali untuk pengguna saat ini
            'username' => 'required|string|unique:users,username,' . $id,  // username harus unik kecuali untuk pengguna saat ini
            'password' => 'nullable|string|min:8|confirmed',  // password hanya dibutuhkan jika ingin mengubah
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Perbarui data pengguna
        $user->email = $request->email;
        $user->username = $request->username;

        // Jika ada password baru, maka update password
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        // Simpan perubahan
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * delete
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($id);

        // Jika pengguna tidak ditemukan, kembalikan response error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Hapus data pengguna
        $user->delete();

        // Kembalikan respons sukses setelah penghapusan
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}