<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * index
     * Menampilkan semua order yang ada beserta informasi user yang terkait, dengan paginasi
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mendapatkan semua order dengan informasi user, paginated
        $orders = Order::with('user')->latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'List of Orders',
            'data' => $orders
        ]);
    }

    /**
     * store
     * Membuat order baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // pastikan user_id valid di tabel users
            'total_price' => 'required|numeric',
            'status' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Membuat order baru
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }
    

    /**
     * show
     * Menampilkan detail order berdasarkan ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Mencari order berdasarkan ID
        $order = Order::with('user', 'orderItems')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved successfully',
            'data' => $order
        ]);
    }

    /**
     * update
     * Mengupdate informasi order berdasarkan ID
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Mencari order berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Validasi input data
        $validator = Validator::make($request->all(), [
            'total_price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Memperbarui data order
        $order->total_price = $request->total_price;
        $order->status = $request->status;

        // Simpan perubahan
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * destroy
     * Menghapus order berdasarkan ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Mencari order berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Menghapus order
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    /**
     * Relasi antara Order dan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
 * addItemsToOrder
 * Menambahkan item ke dalam order yang ada
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function addItemsToOrder(Request $request, $id)
{
    // Mencari order berdasarkan ID
    $order = Order::find($id);

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }

    // Validasi input data
    $validator = Validator::make($request->all(), [
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Menambahkan item ke dalam order
    foreach ($request->items as $item) {
        $order->orderItems()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // Refresh data order untuk mengembalikan semua item terbaru
    $order->load('orderItems');

    return response()->json([
        'success' => true,
        'message' => 'Items added to order successfully',
        'data' => $order
    ]);
}

}
