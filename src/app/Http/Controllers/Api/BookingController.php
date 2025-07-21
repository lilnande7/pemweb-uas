<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Customer;
use App\Models\RentalOrder;
use App\Models\RentalOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/booking/create-order",
     *     summary="Create a new rental order",
     *     description="Create a new rental order for an instrument",
     *     tags={"Booking"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"instrument_id","rental_start_date","rental_end_date","quantity","first_name","last_name","email","phone","city","postal_code","id_card_number","delivery_method"},
     *             @OA\Property(property="instrument_id", type="integer", example=1, description="ID of the instrument to rent"),
     *             @OA\Property(property="rental_start_date", type="string", format="date", example="2025-07-22", description="Start date of rental"),
     *             @OA\Property(property="rental_end_date", type="string", format="date", example="2025-07-25", description="End date of rental"),
     *             @OA\Property(property="quantity", type="integer", example=1, description="Quantity to rent"),
     *             @OA\Property(property="first_name", type="string", example="John", description="Customer first name"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Customer last name"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Customer email"),
     *             @OA\Property(property="phone", type="string", example="081234567890", description="Customer phone number"),
     *             @OA\Property(property="city", type="string", example="Jakarta", description="Customer city"),
     *             @OA\Property(property="postal_code", type="string", example="12345", description="Customer postal code"),
     *             @OA\Property(property="id_card_number", type="string", example="1234567890123456", description="Customer ID card number"),
     *             @OA\Property(property="address", type="string", example="Jl. Example No. 123", description="Customer address (optional)"),
     *             @OA\Property(property="delivery_method", type="string", enum={"pickup","delivery"}, example="pickup", description="Delivery method"),
     *             @OA\Property(property="notes", type="string", example="Please handle with care", description="Additional notes (optional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="order_id", type="integer", example=1),
     *                 @OA\Property(property="order_number", type="string", example="ORD-2025-0001"),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="string", example="081234567890")
     *                 ),
     *                 @OA\Property(
     *                     property="instrument",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acoustic Guitar"),
     *                     @OA\Property(property="daily_rate", type="string", example="50000.00")
     *                 ),
     *                 @OA\Property(
     *                     property="rental_period",
     *                     type="object",
     *                     @OA\Property(property="start_date", type="string", format="date", example="2025-07-22"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2025-07-25"),
     *                     @OA\Property(property="days", type="integer", example=4)
     *                 ),
     *                 @OA\Property(
     *                     property="pricing",
     *                     type="object",
     *                     @OA\Property(property="subtotal", type="number", example=200000),
     *                     @OA\Property(property="tax_amount", type="number", example=20000),
     *                     @OA\Property(property="security_deposit", type="number", example=25000),
     *                     @OA\Property(property="total_amount", type="number", example=245000)
     *                 ),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Instrument not available",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Instrument is not available for rent or insufficient quantity.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while processing your order")
     *         )
     *     )
     * )
     * Create a new rental order via API
     */
    public function createOrder(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'instrument_id' => 'required|exists:instruments,id',
            'rental_start_date' => 'required|date|after_or_equal:today',
            'rental_end_date' => 'required|date|after:rental_start_date',
            'quantity' => 'required|integer|min:1|max:1',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'id_card_number' => 'required|string|max:255',
            'address' => 'nullable|string',
            'delivery_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        Log::info('API createOrder called', ['request_data' => $request->all()]);

        DB::beginTransaction();
        
        try {
            $instrument = Instrument::findOrFail($request->instrument_id);
            
            // Check availability
            if (!$instrument->is_available || $instrument->quantity_available < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Instrument is not available for rent or insufficient quantity.'
                ], 400);
            }

            Log::info('Instrument found and available', ['instrument_id' => $instrument->id]);

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'address' => $request->address ?? ($request->city . ', ' . $request->postal_code),
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'id_card_number' => $request->id_card_number,
                ]
            );

            Log::info('Customer created/found', ['customer_id' => $customer->id]);

            // Calculate pricing
            $startDate = \Carbon\Carbon::parse($request->rental_start_date);
            $endDate = \Carbon\Carbon::parse($request->rental_end_date);
            $days = $startDate->diffInDays($endDate) + 1; // Include start day
            
            $dailyRate = $instrument->daily_rate;
            $subtotal = $dailyRate * $days * $request->quantity;
            $taxAmount = $subtotal * 0.10; // 10% tax
            $securityDeposit = $dailyRate * $request->quantity * 0.5; // 50% of daily rate as deposit
            $total = $subtotal + $taxAmount + $securityDeposit;

            Log::info('Pricing calculated', [
                'days' => $days,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'deposit' => $securityDeposit,
                'total' => $total
            ]);

            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(RentalOrder::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create rental order with same structure as admin panel
            $rentalOrder = RentalOrder::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'user_id' => 1, // Default admin user
                'rental_start_date' => $startDate->toDateString(),
                'rental_end_date' => $endDate->toDateString(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'security_deposit' => $securityDeposit,
                'total_amount' => $total,
                'outstanding_amount' => $total,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            Log::info('Rental order created', ['order_id' => $rentalOrder->id]);

            // Create rental order item
            $orderItem = RentalOrderItem::create([
                'rental_order_id' => $rentalOrder->id,
                'instrument_id' => $instrument->id,
                'quantity' => $request->quantity,
                'unit_price' => $dailyRate,
                'rental_days' => $days,
                'total_price' => $subtotal,
            ]);

            Log::info('Rental order item created', ['item_id' => $orderItem->id]);

            DB::commit();

            // Return successful response with order details
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $rentalOrder->id,
                    'order_number' => $orderNumber,
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->first_name . ' ' . $customer->last_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone
                    ],
                    'instrument' => [
                        'id' => $instrument->id,
                        'name' => $instrument->name,
                        'daily_rate' => $dailyRate
                    ],
                    'rental_period' => [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'days' => $days
                    ],
                    'pricing' => [
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'security_deposit' => $securityDeposit,
                        'total_amount' => $total
                    ],
                    'status' => 'pending',
                    'payment_status' => 'pending'
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order creation failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/booking/order/{orderNumber}",
     *     summary="Get order details by order number",
     *     description="Retrieve detailed information about a rental order using its order number",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="orderNumber",
     *         in="path",
     *         required=true,
     *         description="Order number to retrieve",
     *         @OA\Schema(type="string", example="ORD-2025-0001")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="order_id", type="integer", example=1),
     *                 @OA\Property(property="order_number", type="string", example="ORD-2025-0001"),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="string", example="081234567890"),
     *                     @OA\Property(property="city", type="string", example="Jakarta"),
     *                     @OA\Property(property="postal_code", type="string", example="12345"),
     *                     @OA\Property(property="id_card_number", type="string", example="1234567890123456")
     *                 ),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="instrument_id", type="integer", example=1),
     *                         @OA\Property(property="instrument_name", type="string", example="Acoustic Guitar"),
     *                         @OA\Property(property="quantity", type="integer", example=1),
     *                         @OA\Property(property="unit_price", type="string", example="50000.00"),
     *                         @OA\Property(property="rental_days", type="integer", example=4),
     *                         @OA\Property(property="total_price", type="string", example="200000.00")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="rental_period",
     *                     type="object",
     *                     @OA\Property(property="start_date", type="string", format="date", example="2025-07-22"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2025-07-25")
     *                 ),
     *                 @OA\Property(
     *                     property="pricing",
     *                     type="object",
     *                     @OA\Property(property="subtotal", type="string", example="200000.00"),
     *                     @OA\Property(property="tax_amount", type="string", example="20000.00"),
     *                     @OA\Property(property="security_deposit", type="string", example="25000.00"),
     *                     @OA\Property(property="total_amount", type="string", example="245000.00"),
     *                     @OA\Property(property="outstanding_amount", type="string", example="245000.00")
     *                 ),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="pending"),
     *                 @OA\Property(property="notes", type="string", example="Please handle with care"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", example="2025-07-21T10:30:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2025-07-21T10:30:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching order details")
     *         )
     *     )
     * )
     * Get order details by order number
     */
    public function getOrder($orderNumber)
    {
        try {
            $order = RentalOrder::with(['customer', 'items.instrument', 'user'])
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer' => [
                        'id' => $order->customer->id,
                        'name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                        'email' => $order->customer->email,
                        'phone' => $order->customer->phone,
                        'city' => $order->customer->city,
                        'postal_code' => $order->customer->postal_code,
                        'id_card_number' => $order->customer->id_card_number
                    ],
                    'items' => $order->items->map(function ($item) {
                        return [
                            'instrument_id' => $item->instrument->id,
                            'instrument_name' => $item->instrument->name,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'rental_days' => $item->rental_days,
                            'total_price' => $item->total_price
                        ];
                    }),
                    'rental_period' => [
                        'start_date' => $order->rental_start_date,
                        'end_date' => $order->rental_end_date
                    ],
                    'pricing' => [
                        'subtotal' => $order->subtotal,
                        'tax_amount' => $order->tax_amount,
                        'security_deposit' => $order->security_deposit,
                        'total_amount' => $order->total_amount,
                        'outstanding_amount' => $order->outstanding_amount
                    ],
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get order failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching order details'
            ], 500);
        }
    }
}
