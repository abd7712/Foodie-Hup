<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmBook;
use App\Mail\RejectedBook;
use App\Models\Book;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rate;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SuperAdminAndAdminController extends Controller
{
    public function create_table(Request $request)
    {

        $validator=Validator::make($request->all(),[
            "number_of_sets"=>"required|numeric|min:1",
            "location"=>"required|in:inside,outside"
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ],422);
        }

        Table::create([
            "number_of_sets"=>$request->input("number_of_sets"),
            "location"=>$request->input("location"),
            "status"=>"available"
        ]);

        return response([
            "message"=>"table created successfully"
        ],201);
    }

    public function show_tables()
    {
        $tables=Table::all();
        if($tables->isEmpty())
        {
            return response([
                "error"=>"no tables found"
            ],404);
        }
        return response([
            "tables"=>$tables
        ],200);
    }

    public function create_category(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "type"=>"required|string|unique:categories",
        ]);
         
        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ],422);
        }

        Category::create([
            "type"=>$request->input("type")
        ]);

        return response([
            "message"=>"category created successfully"
        ],201);
    }

    public function show_categories()
    {
        $categories=Category::all();
        if($categories->isEmpty())
        {
            return response([
                "error"=>"no categories found"
            ],404);
        }
        return response([
            "categories"=>$categories
        ],200);
    }
  
    public function create_product(Request $request, $category_id)
    {
        $validator=Validator::make($request->all(),[
            "name"=>"required|string",
            "price"=>"required|numeric|min:1",
            "isAvailable"=>"required|boolean",
            "description"=>"string|required",
            "image"=>"required|image|mimes:png,jpg"
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ],422);
        }

        if (!\App\Models\Category::find($category_id)) {
            return response()->json([
                'error' => 'Category not found.'
            ], 404);
        }
        $image=$request->file("image");
        $image_path=$image->store("products_image","public");

        $product = Product::create([
            'category_id' => $category_id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'isAvailable' => $request->input('isAvailable'),
            "image"=>$image_path,
            "description"=>$request->input("description")
        ]);
    
        return response()->json([
            'message' => 'Product created successfully',
        ], 201);
    }

    public function show_products($category_id)
    {
        $category = Category::find($category_id);
    
        if (!$category) {
            return response()->json([
                'error' => 'Category not found.'
            ], 404);
        }
        
        $products = $category->products->map(function($product){
            return[
                "name"=>$product->name,
                "price"=>$product->price,
                "isAvailable"=>$product->isAvailable,
                "description"=>$product->description,
                "image"=>url("storage/".$product->image)
            ];
        });
        if($products->isEmpty())
        {
            return response([
                "error"=>"no products found in this category"
            ],404);
        }
        return response()->json([
            'category' => $category->type,
            'products' => $products
        ], 200);
    }

    public function show_pending_books()
    {
        $books = Book::where("status", "pending")->with("user")->orderBy("created_at", "asc")->get();
    
        if ($books->isEmpty()) {
            return response([
                "error" => "No pending books found"
            ], 404);
        }
    
        $formattedBooks = $books->map(function($book) {
            return [
                "book_id" => $book->id, 
                "created_at" => $book->created_at->format('Y-m-d H:i:s'), 
                "user_name" => $book->user->name, 
                "user_email" => $book->user->email, 
                "status" => $book->status
            ];
        });
    
        return response([
            "pending_books" => $formattedBooks
        ], 200);
    }

    public function show_tables_available_for_book($book_id)
    {
        $book = Book::find($book_id);
    
        if (!$book) {
            return response([
                "error" => "Book not found."
            ], 404);
        }
    
        if ($book->status != "pending") {
            return response([
                "error" => "This book is not in pending status."
            ], 422);
        }
    
        $tablesNotAvailable1 = Book::whereIn("status", ["confirm", "arrive"])
            ->where('date',$book->date)
            ->where("start", "<", $book->end)
            ->where("end", ">", $book->start)
            ->where("number_of_sets",$book->number_of_sets)
            ->where("location",$book->location)
            ->pluck("table_id");

        $tablesNotAvailable2=Book::whereIn("status",["confirm","arrive"])
            ->where("date",$book->date)
            ->where("start","<",Carbon::parse($book->end)->addMinutes(30)->format("H:i"))
            ->where("end",">",Carbon::parse($book->start)->subMinutes(30)->format("H:i"))
            ->where("number_of_sets",$book->number_of_sets)
            ->where("location",$book->location)
            ->pluck("table_id");

        $tablesNotAvailable=$tablesNotAvailable1->merge($tablesNotAvailable2)->unique();

        $tablesAvailable = Table::whereNotIn("id", $tablesNotAvailable)
            ->where("number_of_sets", $book->number_of_sets)
            ->where("location", $book->location)
            ->get();
    
        if ($tablesAvailable->isEmpty()) {
            return response([
                "message" => "No available tables match this booking at the moment."
            ], 200);
        }
    
        return response([
            "available_tables" => $tablesAvailable
        ], 200);
    }

    public function confirm_book($book_id,$table_id)
    {
        $book = Book::find($book_id);
    
        if (!$book) {
            return response([
                "error" => "Book not found."
            ], 404);
        }
    
        if ($book->status != "pending") {
            return response([
                "error" => "This book is not in pending status."
            ], 422);
        }

        $tablesNotAvailable1 = Book::whereIn("status", ["confirm", "arrive"])
            ->where('date',$book->date)
            ->where("start", "<", $book->end)
            ->where("end", ">", $book->start)
            ->where("number_of_sets",$book->number_of_sets)
            ->where("location",$book->location)
            ->pluck("table_id");

        $tablesNotAvailable2=Book::whereIn("status",["confirm","arrive"])
            ->where("date",$book->date)
            ->where("start","<",Carbon::parse($book->end)->addMinutes(30)->format("H:i"))
            ->where("end",">",Carbon::parse($book->start)->subMinutes(30)->format("H:i"))
            ->where("number_of_sets",$book->number_of_sets)
            ->where("location",$book->location)
            ->pluck("table_id");

        $tablesNotAvailable=$tablesNotAvailable1->merge($tablesNotAvailable2)->unique();
        
        $tablesAvailable=Table::whereNotIn("id",$tablesNotAvailable)
            ->where("location",$book->location)
            ->where("number_of_sets",$book->number_of_sets)
            ->pluck("id")->toArray();

        $e=in_array($table_id,$tablesAvailable);

        if(!$e)
        {
            return response([
                "error"=>"wrong choose table"
            ],422);
        }

        $book->update([
            "table_id"=>$table_id,
            "status"=>"confirm"
        ]);

        $message = "🎉 Great news! Your reservation has been confirmed. We look forward to welcoming you. Thank you for choosing us!";
        Mail::to($book->user->email)->send(new ConfirmBook($book->user->name,$book->date,$book->start,$book->end,$book->table_id,$book->location,$message));

        return response([
            "message"=>"book confirmed successfully"
        ],201);
        
    }

    public function rejected_book($book_id,Request $request)
    {   
        $validator=Validator::make($request->all(),[
            "rejected_reason"=>"required|string",
        ]);

        if($validator->fails())
        {
            return response([
                "error"=>$validator->errors()
            ],422);
        }

        $book=Book::find($book_id);

        if($book->status!="pending")
        {
            return response([
                "error" => "This book is not in pending status."
            ], 422);
        }

        $conflict_books=Book::whereIn("status",["confirm","arrive"])
        ->where("date",$book->date)
        ->where("start","<",$book->end)
        ->where("end",">",$book->start)
        ->where("location",$book->location)
        ->where("number_of_sets",$book->number_of_sets)
        ->get();

        $conflict_books_buffer =Book::whereIn("status",["confirm","arrive"])
        ->where("date",$book->date)
        ->where("start","<",Carbon::parse($book->end)->addMinutes(30)->format("H:i"))
        ->where("end",">",Carbon::parse($book->start)->subMinutes(30)->format("H:i"))
        ->where("number_of_sets",$book->number_of_sets)
        ->where("location",$book->location)
        ->get();

        $conflict_books_formatted=$conflict_books->map(function($conflict_book){
            return "• Booking on {$conflict_book->date} from {$conflict_book->start} to {$conflict_book->end} at location '{$conflict_book->location}' with {$conflict_book->number_of_sets} seats.";
        })->implode("|");

        $conflict_books_buffer_formatted = $conflict_books_buffer->map(function($conflict) {
            return "• Booking on {$conflict->date} from {$conflict->start} to {$conflict->end} at location '{$conflict->location}' with {$conflict->number_of_sets} seats. (violates 30-minute buffer rule)";
        })->implode(" | ");
        
        Mail::to($book->user->email)->send(new RejectedBook($book->user->name,$book->date,$book->start,$book->end,$request->input("rejected_reason"),$conflict_books_formatted,$conflict_books_buffer_formatted));
        $book->update([
            "status"=>"rejected"
        ]);

        return response([
            "message"=>"book rejected successfully"
        ],201);

    }

    public function show_confirming_books()
    {
        //dd(now());       
        $books = Book::where("date",today())->where("status", "confirm")->with("user")->orderBy("start","asc")->get();
    
        if ($books->isEmpty()) {
            return response([
                "error" => "No confirm books found today"
            ], 404);
        }
    
        $formattedBooks = $books->map(function($book) {
            return [
                "book_id" => $book->id, 
                "user_name" => $book->user->name,
                "start"=>$book->start,
                "end"=>$book->end, 
                "user_email" => $book->user->email, 
                "status" => $book->status
            ];
        });
    
        return response([
            "confirming_books" => $formattedBooks
        ], 200);
    }

    public function arrive_book($book_id)
    {
        //dd(now());
        $book=Book::find($book_id);

        if($book->status!="confirm")
        {
            return response([
                "error" => "This book is not in confirming status."
            ], 422);
        }

        $now=Carbon::now()->startOfMinute();
        $start=Carbon::parse($book->start);
        $end=Carbon::parse($book->end);

        $min=$now->diffInMinutes($start);


        if($min>0 && $min>15)
        {
            return response([
                "error" => "You can only mark arrival within 15 minutes before or after the reservation time."
            ], 422);
        }

        $book->update([
            "status"=>"arrive"
        ]);

        return response([
            "message" => "Booking marked as arrived successfully."
        ], 200);
        
    }

    public function get_rate()
    {
        $avg_rate=Rate::avg("rate");

        if(is_null($avg_rate))
        {
            return response([
                "message" => "No ratings available yet.",
            ], 200);
        }

        return response([
            "avg"=>round($avg_rate,2)
        ],200);
    }


   


}
