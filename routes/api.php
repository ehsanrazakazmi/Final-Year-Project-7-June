<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use App\Http\Controllers\PagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Two rules hold throughout this file:
|
|   1. The acting user comes from the token ($request->user()), never from a
|      URL segment or a request body field. Endpoints used to take a
|      {userId}, so any signed-in account could read another person's orders
|      and wishlist, or place an order in someone else's name.
|
|   2. auth:sanctum establishes who you are; api.role establishes what you
|      may do. Administrative endpoints - listing every order, editing
|      orders, managing categories - are gated to the role that owns them.
|
| The React Native client was updated alongside these routes; see the notes
| on each endpoint where its shape changed.
|
*/

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response([
        'user' => $user,
        'token' => $user->createToken($request->device_name)->plainTextToken,
    ], 201);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', fn (Request $request) => $request->user());

    /*
    |----------------------------------------------------------------------
    | Reference data - readable by any signed-in user
    |----------------------------------------------------------------------
    */

    Route::get('/home_data', [PagesController::class, 'home_data']);

    Route::get('/categories', fn () => response()->json(DB::table('categories')->get()));

    /**
     * Read-only access to a small set of catalogue tables.
     *
     * This once ran DB::table($table) on whatever name the caller supplied,
     * so /api/tables/users returned every password hash, email and
     * remember_token. The allowlist is deliberate: anything not named here
     * is refused, so a table added later stays private by default.
     *
     * 'wishlists' was removed from the list. It is per-user data, and
     * returning the whole table handed every user's wishlist to anyone who
     * asked. The app reads its own via /api/wishlists below; the only other
     * caller was a commented-out line in a screen that is never mounted.
     */
    Route::get('/tables/{table}', function ($table) {
        $allowed = ['categories', 'availabilities', 'services'];

        abort_unless(in_array($table, $allowed, true), 404);

        return response()->json(['data' => DB::table($table)->get()]);
    });

    /*
    |----------------------------------------------------------------------
    | The signed-in user's own data
    |----------------------------------------------------------------------
    |
    | These took the user id as a URL segment or body field. They now read
    | it from the token, so the id is simply gone from the route. The RN
    | screens were changed to match.
    |
    */

    // was GET /users/{userId}/wishlists
    Route::get('/wishlists', function (Request $request) {
        $wishlistProducts = DB::table('wishlists')
            ->join('services', 'wishlists.services_id', '=', 'services.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->where('wishlists.user_id', $request->user()->id)
            ->select('services.*', 'categories.name as category_name')
            ->get();

        return response()->json(['data' => $wishlistProducts]);
    });

    // was GET /order_status/{userId}
    Route::get('/order_status', function (Request $request) {
        $orders = DB::table('orders')->where('user_id', $request->user()->id)->get();
        $data = [];

        foreach ($orders as $order) {
            $items = DB::table('items')->where('order_id', $order->id)->get();

            foreach ($items as $item) {
                $service = DB::table('services')->where('id', $item->service_id)->first();
                $availability = DB::table('availabilities')->where('id', $item->availability_id)->first();

                // COMPATIBILITY: the app reads item.color.code / .code1 / .name
                // (Order_status.js). The table and columns were renamed to
                // availabilities/available_from/available_to, so the old shape
                // is rebuilt here. Remove once the app is updated.
                $color = $availability === null ? null : (object) [
                    'id' => $availability->id,
                    'name' => $availability->name,
                    'code' => $availability->available_from,
                    'code1' => $availability->available_to,
                ];

                $data[] = [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'service' => $service,
                    'color' => $color,
                    'quantity' => $item->quantity,
                ];
            }
        }

        return response()->json($data);
    });

    // was GET /item_present/{serviceId}/{userid}
    Route::get('/item_present/{serviceId}', function (Request $request, $serviceId) {
        $isPresent = DB::table('wishlists')
            ->where('services_id', $serviceId)
            ->where('user_id', $request->user()->id)
            ->exists();

        return response()->json(['is_present' => $isPresent]);
    });

    // was POST /wishlist_add with user_id in the body
    Route::post('/wishlist_add', function (Request $request) {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $wishlist = new Wishlist;
        $wishlist->user_id = $request->user()->id;
        $wishlist->services_id = $validated['service_id'];
        $wishlist->save();

        return response()->json(['message' => 'product is successfully added to wishlist']);
    });

    // was DELETE /wishlist_remove/{user_id}/{id}
    Route::delete('/wishlist_remove/{serviceId}', function (Request $request, $serviceId) {
        DB::table('wishlists')
            ->where('user_id', $request->user()->id)
            ->where('services_id', $serviceId)
            ->delete();

        return response()->json(['success' => true]);
    });

    /**
     * Place an order.
     *
     * was POST /cart_add with user_id in the body - which meant an order
     * could be placed in any other account's name. The owner is now the
     * token holder. Contact details stay in the body because the app
     * collects them per order, but they no longer decide who owns it.
     */
    Route::post('/cart_add', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'quantity' => 'required|integer|min:1',
            'service_id' => 'required|exists:services,id',
            // COMPATIBILITY: the app posts 'colors_id' (Wishlist.js).
            'colors_id' => 'required|exists:availabilities,id',
        ]);

        $service = DB::table('services')->where('id', $validated['service_id'])->first();

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'pending',
            // Stored at checkout so the figure stays what was ordered even if
            // the price changes later, matching CheckoutController.
            'total' => (float) ($service->price ?? 0) * (int) $validated['quantity'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            'order_id' => $orderId,
            'service_id' => $validated['service_id'],
            'availability_id' => $validated['colors_id'],
            'category_id' => $service->category_id ?? null,
            'quantity' => $validated['quantity'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $orderId,
        ]);
    });

    /*
    |----------------------------------------------------------------------
    | Technician
    |----------------------------------------------------------------------
    */

    Route::middleware('api.role:technician')->group(function () {

        /**
         * Jobs waiting in this technician's trade.
         *
         * was GET /tech_orders/{id}, where {id} was the category - supplied
         * by the caller, so a technician could list any trade's jobs, each
         * row carrying a resident's address. The category now comes from the
         * account.
         *
         * The app already called /api/tech_orders with no segment
         * (Technician_Screens/Orders.js), which matched no route and 404'd,
         * so this also makes that screen work for the first time.
         */
        Route::get('/tech_orders', function (Request $request) {
            $orders = DB::table('items')
                ->join('orders', 'orders.id', '=', 'items.order_id')
                ->where('items.category_id', $request->user()->category_id)
                ->where('orders.status', 'shipped')
                ->select('orders.id as order_id', 'orders.address', 'orders.status', 'items.quantity as job')
                ->get();

            return response()->json($orders);
        });

        /**
         * Accept a job.
         *
         * Previously any signed-in account could accept any order by id. A
         * technician may now only accept work in their own trade.
         *
         * The status written here was 'accept' while the web portal wrote
         * 'accepted', so an order accepted from the phone never matched the
         * web dashboard's filters. Both are 'accepted' now.
         */
        Route::put('/orders/{id}/status', function (Request $request, $id) {
            $order = Order::find($id);

            if (! $order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $inTrade = DB::table('items')
                ->where('order_id', $order->id)
                ->where('category_id', $request->user()->category_id)
                ->exists();

            if (! $inTrade) {
                return response()->json(['message' => 'That job is not in your trade.'], 403);
            }

            $order->status = 'accepted';
            $order->save();

            return response()->json(['message' => 'Order status updated successfully']);
        });
    });

    /*
    |----------------------------------------------------------------------
    | Administration
    |----------------------------------------------------------------------
    |
    | All of these were reachable by any signed-in resident. /orders alone
    | returned every order in the system with the customer's name, email,
    | phone and address.
    |
    | The update endpoints took request()->all() straight into update().
    | Order and Category both declare $guarded = [], so the caller chose the
    | columns - user_id, total and status included. Each now validates an
    | explicit list.
    |
    */

    Route::middleware('api.role:admin')->group(function () {

        Route::get('/orders', fn () => response()->json(
            DB::table('orders')->orderByDesc('updated_at')->get()
        ));

        Route::put('/orders/{id}', function (Request $request, $id) {
            $order = Order::find($id);

            if (! $order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $order->update($request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'sometimes|string|max:32',
                'address' => 'sometimes|string|max:255',
                'status' => 'sometimes|string|max:32',
            ]));

            return response()->json(['message' => 'Order updated successfully']);
        });

        // Kept separate from PUT /orders/{id} because the app calls this one
        // (Admin_Screens/Orders.js). Same allowlist.
        Route::put('/order_update/{orderId}', function (Request $request, $orderId) {
            $order = Order::find($orderId);

            if (! $order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $order->update($request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'sometimes|string|max:32',
                'address' => 'sometimes|string|max:255',
                'status' => 'sometimes|string|max:32',
            ]));

            return response()->json(['message' => 'Order updated successfully.']);
        });

        Route::post('/category_add', function (Request $request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            Category::create(['name' => $validated['name']]);

            return response()->json(['message' => 'Category created successfully']);
        });

        Route::put('/category_update/{categoryId}', function (Request $request, $categoryId) {
            $category = Category::find($categoryId);

            if (! $category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            $category->update($request->validate([
                'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            ]));

            return response()->json(['message' => 'Category updated successfully.']);
        });
    });
});
