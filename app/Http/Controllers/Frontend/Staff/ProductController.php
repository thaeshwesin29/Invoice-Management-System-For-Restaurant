<?php

namespace App\Http\Controllers\Frontend\Staff;

use Exception;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return view('frontend.staff.product.index', compact('categories'));
    }

    public function getProductList(Request $request)
    {
        $products = Product::when($request->category_id, function($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->inStock()->paginate(9);

        $products = ProductResource::collection($products);

        $data = [
            'data' => $products,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ]
        ];

        return success( $data);
    }

    public function getAddToCartOrder()
    {
        $order = Order::with('order_items')
            ->pending()
            ->where('orderable_type', Staff::class)
            ->where('orderable_id', auth()->guard('staff')->user()->id)
            ->first();

            $data = !is_null($order) ? new OrderResource($order) : [];

        return success($data);
    }

    public function addToCartOrderItems(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $auth_user = auth()->guard('staff')->user();

            $product = Product::find($request->product_id);

            if (!$product) {
                throw new Exception('Product not found!');
            }

            $status = $request->status;

            $order = Order::where('orderable_type', Staff::class)->where('orderable_id', $auth_user->id)->pending()->first();

            if ($order) {
                $order_item = $order->order_items()->where('product_id', $product->id)->first();

                if ($order_item) {
                    if ($status == 'add') {
                        if ($product->isOutOfStock()) {
                            throw new Exception(translate('Out of stock!', 'ပစ္စည်းမရှိတော့ပါ!'));
                        }

                        $quantity = ($order_item->quantity ?? 0) + 1;
                        $price = $product->price * $quantity;

                        $order_item->quantity = $quantity;
                        $order_item->price = $price;

                        $product->decrement('stock_quantity');
                    } elseif ($status == 'remove') {
                        $quantity = ($order_item->quantity ?? 0) - 1;
                        $price = $quantity > 0 ? $order_item->price - $product->price : 0;

                        if ($quantity > 0 && $price > 0) {
                            $order_item->quantity = $quantity;
                            $order_item->price = $price;
                        } else {
                            $order_item->delete();
                            // $order->delete();
                        }

                        $product->increment('stock_quantity');
                    } else {
                        throw new Exception('Invalid status!');
                    }

                    $order_item->update();
                } else {
                    if ($status == 'add') {
                        $order_item = new OrderItem();
                        $order_item->order_id = $order->id;
                        $order_item->product_id = $product->id;
                        $order_item->quantity = 1;
                        $order_item->price = $product->price;
                        $order_item->save();

                        $product->decrement('stock_quantity');
                    }
                }

            } else {
                $order = new Order();
                $order->orderable_type = Staff::class;
                $order->orderable_id = $auth_user->id;
                $order->status = 'pending';
                $order->save();

                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->product_id = $product->id;
                $order_item->quantity = 1;
                $order_item->price = $product->price;
                $order_item->save();

                $product->decrement('stock_quantity');
            }

            DB::commit();
            return successMessage();

        } catch (Exception $e) {
            DB::rollback();
            return errorMessage($e->getMessage());
        }
    }
}
