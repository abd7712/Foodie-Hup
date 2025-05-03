<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order_Item;
use App\Models\Package;
use App\Models\Product;
use App\Models\Table;
use Carbon\Carbon;
use Hamcrest\Collection\IsEmptyTraversable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show_n_p_r_c()
    {

        $newest_products = Product::where("isAvailable", true)
            ->where("created_at", ">=", now()->subDays(30))
            ->get();

        if($newest_products->isNotEmpty())
        {
            $newest_products =$newest_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable,
                    "category_id"=>$product->category->id,
                    "category_type"=>$product->category->type
                ];
            });
        }
        else{
            $newest_products="No newest products found.";
        }

        $popular_products = Product::where("isAvailable",true)->withSum(['order_items as total_quantity' => function ($query) {
            $query->where("status", "served")
                ->where("created_at", ">=", now()->subDays(30));
        }], 'quantity')
            ->having("total_quantity",">",0)
            ->orderByDesc("total_quantity")
            ->take(15)
            ->get();

        if($popular_products->isNotEmpty())
        {
            $popular_products=$popular_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable,
                    "category_id"=>$product->category->id,
                    "category_type"=>$product->category->type
                ];
            });
        }
        else{
            $popular_products="No popular products found.";
        }

        $random_products = Product::where("isAvailable", true)
            ->inRandomOrder()
            ->take(15)
            ->get();

        if($random_products->isNotEmpty())
        {
            $random_products=$random_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable,
                    "category_id"=>$product->category->id,
                    "category_type"=>$product->category->type
                ];
            });
        }
        else{
            $random_products="No random products found.";
        }

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories="No category found.";
        }

        return response([
            "newest_products" => $newest_products,
            "popular_products" => $popular_products,
            "random_products" => $random_products,
            "categories" => $categories
        ], 200);
    }

    public function show_products($category_id)
    {
        dd(now());

        $category = Category::find($category_id);
        
        if (!$category) {
            return response()->json([
                'error' => 'Category not found.'
            ], 404);
        }
        
        $products = $category->products; 

        return response()->json([
            'category' => $category->type,
            'products' => $products
        ], 200);
    }

    public function book(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "number_of_sets"=>"required|numeric",
            "date"=>"required|date_format:Y-m-d",
            "start"=>"required|date_format:H:i",
            "end"=>"required|date_format:H:i|after:start",
            "location"=>"required|in:inside,outside"
        ]);

        if($validator->fails())
        {
            return response([
                "error"=>$validator->errors()
            ],422);
        }
        $user=Auth::guard("user")->user();

        $maxSets=Table::max("number_of_sets");
        $minSets=Table::min("number_of_sets");

        $now=Carbon::now()->startOfMinute();
        $start=Carbon::parse($request->input("date").' '.$request->input("start"))->startOfMinute();  
        if($now->greaterThan($start))
        {
            return response([
                "error" => "You cannot select a start time in the past. Please choose a valid time."
            ], 422);
        }

        $min=$now->diffInMinutes($start);
        
        if($min<30)
        {
            return response([
                "error" => "You must book at least 30 minutes in advance."
            ], 422);
        }

        $number_of_sets=$request->input("number_of_sets");
        if($number_of_sets>$maxSets || $number_of_sets<$minSets)
        {
            return response([
                "error" => "Number of seats must be between $minSets and $maxSets."
            ], 422);
        }

        $now=Carbon::now();
        $date=$request->input("date");

        if($now->format("Y-m-d") > $date)
        {
            return response([
                "error" => "You cannot book a date in the past."
            ], 422);
        }

        Book::create([
            "user_id"=>$user->id,
            "number_of_sets"=>$request->input("number_of_sets"),
            "date"=>$request->input("date"),
            "start"=>$request->input("start"),
            "end"=>$request->input("end"),
            "status"=>"pending",
            "location"=>$request->input("location")
        ]);
    
        return response([
            "message" => "We've received your booking request! It's not confirmed yet — you'll receive an email once it's approved or rejected."
        ], 201);        
    }

    public function show_my_books()
    {
        $books=Auth::guard("user")->user()->books()->get();
        return response([
            "books"=>$books
        ],200);
    }
  
    public function show_n_p_r_c_for_arrived_book($book_id)
    {
        $newest_products = Product::where("isAvailable", true)
            ->where("created_at", ">=", now()->subDays(30))
            ->get();

        if($newest_products->isNotEmpty())
        {
            $newest_products =$newest_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable
                ];
            });
        }
        else{
            $newest_products="No newest products found.";
        }

        $popular_products = Product::where("isAvailable",true)->withSum(['order_items as total_quantity' => function ($query) {
            $query->where("status", "served")
                ->where("created_at", ">=", now()->subDays(30));
        }], 'quantity')
            ->having("total_quantity",">",0)
            ->orderByDesc("total_quantity")
            ->take(15)
            ->get();

        if($popular_products->isNotEmpty())
        {
            $popular_products=$popular_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable
                ];
            });
        }
        else{
            $popular_products="No popular products found.";
        }

        $random_products = Product::where("isAvailable", true)
            ->inRandomOrder()
            ->take(15)
            ->get();

        if($random_products->isNotEmpty())
        {
            $random_products=$random_products->map(function($product){
                return [
                    "name"=>$product->name,
                    "price"=>$product->price,
                    "description"=>$product->description,
                    "image"=>url("storage/".$product->image),
                    "isAvailable"=>$product->isAvailable
                ];
            });
        }
        else{
            $random_products="No random products found.";
        }

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories="No category found.";
        }

        return response([
            "newest_products" => $newest_products,
            "popular_products" => $popular_products,
            "random_products" => $random_products,
            "categories" => $categories
        ], 200);
    }

    public function show_products_for_arrived_book($book_id,$category_id)
    {
        $category = Category::find($category_id);
        
        if (!$category) {
            return response()->json([
                'error' => 'Category not found.'
            ], 404);
        }
        
        $products = $category->products; 

        return response()->json([
            'category' => $category->type,
            'products' => $products
        ], 200);
    }

    
    public function add_to_cart(Request $request, $product_id, $book_id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response([
                "errors" => $validator->errors()
            ], 422);
        }
    
        $user = Auth::guard("user")->user();
    
        $product = Product::find($product_id);
        if (!$product) {
            return response([
                "error" => "Product not found."
            ], 404);
        }
    
        if (!$product->isAvailable) {
            return response([
                "error" => "Product is currently unavailable."
            ], 403);
        }
    
        $book = $user->books()->find($book_id);
        if (!$book) {
            return response([
                "error" => "Booking not found or doesn't belong to user."
            ], 404);
        }
    
        $cart = $user->carts()
            ->where("book_id", $book->id)
            ->where("product_id", $product->id)
            ->first();
    
        if ($cart) {
            return response([
                "message" => "This item is already in your cart. Please update the quantity or note from your cart page."
            ], 200);
        }
    
        $user->carts()->create([
            "product_id" => $product->id,
            "book_id" => $book->id,
            "quantity" => $request->input("quantity"),
            "note" => $request->input("note"),
        ]);
    
        return response([
            "message" => "Product added to cart successfully."
        ], 201);
    }

    public function show_my_carts($book_id)
    {
        $user = Auth::guard("user")->user();

        $carts = $user->carts()->with("product")->get();

        if ($carts->isEmpty()) {
            return response([
                "message" => "Your cart is currently empty."
            ], 200);
        }

        $formattedCarts = $carts->map(function ($cart) {
            return [
                "cart_id"      => $cart->id,
                "product_id"   => $cart->product_id,
                "product_name" => $cart->product->name,
                "price"        => $cart->product->price,
                "quantity"     => $cart->quantity,
                "note"         => $cart->note,
                "image"        => url("storage/".$cart->product->image)
            ];
        });

        return response([
            "carts" => $formattedCarts,
        ], 200);
    }


    public function order($book_id)
    {
        $user=Auth::guard("user")->user();

        $carts=$user->carts()->where("book_id",$book_id)->get();
        
        $order=$user->orders()->where("book_id",$book_id)->first();

        if(!$order)
        {
            $order=$user->orders()->create([
                "book_id"=>$book_id
            ]);
        }

        if ($carts->count() == 0) {
            return response([
                "message" => "No items found in your cart to place an order."
            ], 400);
        }

        $book=Book::find($book_id);

        $end=Carbon::parse($book->end);
        $now=Carbon::now()->startOfMinute();
        $min=$now->diffInMinutes($end);


        if($book->orders->request_invoice==true)
        {
            return response([
                "message" => "Your invoice has already been requested. No further orders can be made. Thank you for understanding! 🌟"
            ], 403);
        }

        if($min<=10 && $min>0)
        {
            return response([
                "message" => "Sorry, you can't place an order now. Your booking will end soon."
            ], 403);
        }

        if($min <=15 && $min>10)
        {
            foreach($carts as $cart)
            {
                $type=$cart->product->category->type;
                $types=["hot_juice","cold_juice"];
                if(!in_array($type,$types))
                {
                    return response([
                        "message" => "You can only order juices during the last 15 minutes of your booking."
                    ], 403);
                }
            }
        }

        foreach($carts as $cart)
        {
            $order->order_items()->create([
                "product_id"=>$cart->product_id,
                "quantity"=>$cart->quantity,
                "note"=>$cart->note,
                "unit_price"=>$cart->product->price,
                "total_price"=>$cart->quantity*$cart->product->price,
                "status"=>"pending"
            ]);
        }

        $user->carts()->where("book_id",$book_id)->delete();

        return response([
            "message" => "Your order has been placed successfully. You can track it from your booking page."
        ], 201);
    }

    public function show_my_orders($book_id)
    {
        $user=Auth::guard("user")->user();

        $order=$user->orders()->where("book_id",$book_id)->first();
        $order_items = $order->order_items;
        return response([
            "order items"=>$order_items
        ],200);
    }

    public function request_invoice($book_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_invoice" => "required|boolean"
        ]);
    
        if ($validator->fails()) {
            return response([
                "error" => $validator->errors()
            ], 422);
        }
    
        $user = Auth::guard("user")->user();
        $order = $user->orders()->where("book_id", $book_id)->first();
    
        if (!$order) {
            return response([
                "error" => "Order not found for this booking."
            ], 404);
        }
        if($order->request_invoice== false && $request->input("request_invoice")==false)
        {
            return response([
                "message" => "Invoice has already been false.!"
            ], 200);
        }

        $order_items = $order->order_items()->get();
        
        if ($order_items->isEmpty()) {
            return response([
                "error" => "You cannot request an invoice without any ordered items."
            ], 403);
        }

        $e=$order->order_items()->whereIn("status",["pending","progress","ready"])->exists();
        
        if($e)
        {
            return response([
                "error" => "There are items in this order that are either pending, in progress, or ready. You cannot proceed with this action."
            ], 422);
        }

        if ($order->request_invoice == true) {
            return response([
                "message" => "Invoice has already been requested. Thank you!"
            ], 200);
        }
    
        $total = $order_items->sum('total_price');
    
        $order->update([
            "request_invoice" => true,
            "total_price" => $total
        ]);

        $book->update([
            "status"=>"completed"
        ]);
        
        return response([
            "message" => "Your invoice request has been submitted successfully! 🧾 Thank you for dining with us.",
            "total_price" => $total
        ], 201);
    }
    
    public function rate($book_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "rate" => "required|numeric|between:1,5"
        ]);
    
        if ($validator->fails()) {
            return response([
                "error" => $validator->errors()
            ], 422);
        }
    
        $user = Auth::guard("user")->user();
        $book = $user->books()->find($book_id);
    
        if (!$book) {
            return response([
                "error" => "Booking not found."
            ], 404);
        }
    
        if ($book->status != "completed") {
            return response([
                "error" => "You can only rate a completed booking."
            ], 403);
        }
    
        $existingRate = $user->rates()->where("book_id", $book->id)->first();
    
        if ($existingRate) {
            return response([
                "error" => "You have already rated this booking."
            ], 409);
        }
    
        $rate = $user->rates()->create([
            "book_id" => $book->id,
            "rate" => $request->input("rate")
        ]);
    
        return response([
            "message" => "Thank you for your rating!",
            "rate" => $rate
        ], 201);
    }

    
    
}
