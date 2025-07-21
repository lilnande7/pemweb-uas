<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\InstrumentCategory;
use App\Models\Customer;
use App\Models\RentalOrder;
use App\Models\RentalOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function index()
    {
        $featuredInstruments = Instrument::with('category')
            ->available()
            ->take(8)
            ->get();
        
        $categories = InstrumentCategory::active()
            ->withCount('availableInstruments')
            ->get();

        $stats = [
            'total_instruments' => Instrument::active()->count(),
            'total_categories' => InstrumentCategory::active()->count(),
            'happy_customers' => Customer::active()->count(),
            'completed_rentals' => RentalOrder::where('status', 'returned')->count(),
        ];

        return view('frontend.index', compact('featuredInstruments', 'categories', 'stats'));
    }

    public function catalog(Request $request)
    {
        $query = Instrument::with('category')->available();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('daily_rate', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('daily_rate', '<=', $request->max_price);
        }

        // Search by name or brand
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('daily_rate', 'asc');
                break;
            case 'price_high':
                $query->orderBy('daily_rate', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $instruments = $query->paginate(12);
        $categories = InstrumentCategory::active()->get();

        return view('frontend.catalog', compact('instruments', 'categories'));
    }

    public function instrumentDetail($id)
    {
        $instrument = Instrument::with('category')
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedInstruments = Instrument::with('category')
            ->where('category_id', $instrument->category_id)
            ->where('id', '!=', $instrument->id)
            ->available()
            ->take(4)
            ->get();

        return view('frontend.instrument-detail', compact('instrument', 'relatedInstruments'));
    }

    public function booking($instrumentId)
    {
        $instrument = Instrument::with('category')
            ->where('id', $instrumentId)
            ->available()
            ->firstOrFail();

        return view('frontend.booking', compact('instrument'));
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'instrument_id' => 'required|exists:instruments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $instrument = Instrument::findOrFail($request->instrument_id);
        
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        
        $unitPrice = $instrument->getRateForDays($days);
        $subtotal = $unitPrice * $request->quantity;
        $tax = $subtotal * 0.10; // 10% tax
        $securityDeposit = $instrument->daily_rate * $request->quantity * 0.5; // 50% of daily rate
        $total = $subtotal + $tax + $securityDeposit;

        return response()->json([
            'days' => $days,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'security_deposit' => $securityDeposit,
            'total' => $total,
        ]);
    }

    public function storeBooking(Request $request)
    {
        // Add debugging
        Log::info('storeBooking called via frontend', ['request_data' => $request->all()]);
        
        // Validate frontend-specific fields first
        $request->validate([
            'terms_agreed' => 'required|accepted',
        ]);
        
        // Validate main booking fields
        $request->validate([
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
            'delivery_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
        ]);

        Log::info('Validation passed');

        DB::beginTransaction();
        
        try {
            $instrument = Instrument::findOrFail($request->instrument_id);
            
            // Check availability using correct field names
            if (!$instrument->is_available || $instrument->quantity_available < $request->quantity) {
                Log::error('Instrument not available', [
                    'is_available' => $instrument->is_available,
                    'quantity_available' => $instrument->quantity_available,
                    'requested_quantity' => $request->quantity
                ]);
                return back()->withErrors(['error' => 'Instrument is not available for rent or insufficient quantity.']);
            }

            Log::info('Instrument found and available', ['instrument_id' => $instrument->id]);

            // Create or find customer with address field
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

            // Create rental order
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

            Log::info('Transaction committed successfully');

            return redirect()->route('frontend.booking-success', $rentalOrder->id)
                ->with('success', 'Booking berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Frontend booking failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    public function bookingSuccess($orderId)
    {
        $order = RentalOrder::with(['customer', 'items.instrument'])
            ->findOrFail($orderId);

        return view('frontend.booking-success', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = RentalOrder::with(['customer', 'items.instrument.category'])
            ->where('order_number', $request->order_number)
            ->whereHas('customer', function($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->first();

        if (!$order) {
            return back()->withErrors(['error' => 'Order not found or email does not match.']);
        }

        return view('frontend.track-order', compact('order'));
    }
}
