<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Addresses\Address;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Checkout\CheckoutRepository;
use App\Shop\Countries\Country;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\OrderStatuses\Repositories\OrderStatusRepository;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class GuestCheckoutController extends Controller
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->customerRepo = $customerRepository;
    }

    /**
     * Show the guest checkout form
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->cartRepo->getCartItems();

        if ($products->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $countries = Country::where('status', 1)->get();

        return view('front.checkout-guest', [
            'products' => $products,
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'total' => $this->cartRepo->getTotal(2),
            'countries' => $countries,
        ]);
    }

    /**
     * Process the guest checkout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|min:3',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|min:10',
            'address_1' => 'required|min:5',
            'city' => 'required',
            'country_id' => 'required|integer',
            'zip' => 'required',
        ]);

        try {
            // Check if customer with this email already exists
            $customer = Customer::where('email', $request->input('customer_email'))->first();

            if (!$customer) {
                // Create new customer
                $customer = Customer::create([
                    'name' => $request->input('customer_name'),
                    'email' => $request->input('customer_email'),
                    'password' => Hash::make(Str::random(16)),
                    'status' => 1
                ]);
            }

            // Create address for the customer
            $address = Address::create([
                'alias' => 'Shipping Address',
                'address_1' => $request->input('address_1'),
                'address_2' => $request->input('address_2', ''),
                'city' => $request->input('city'),
                'state_code' => $request->input('state_code', ''),
                'zip' => $request->input('zip'),
                'country_id' => $request->input('country_id'),
                'customer_id' => $customer->id,
                'phone' => $request->input('customer_phone'),
                'status' => true
            ]);

            // Get order status
            $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
            $orderStatus = $orderStatusRepo->findByName('ordered');

            if (!$orderStatus) {
                $orderStatus = $orderStatusRepo->findById(1);
            }

            // Calculate totals
            $subtotal = $this->cartRepo->getSubTotal();
            $tax = $this->cartRepo->getTax();
            $total = $this->cartRepo->getTotal(2);

            // Create the order
            $checkoutRepo = new CheckoutRepository;
            $order = $checkoutRepo->buildCheckoutItems([
                'reference' => Uuid::uuid4()->toString(),
                'courier_id' => 1, // Default courier
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'order_status_id' => $orderStatus->id,
                'payment' => 'bank-transfer',
                'discounts' => 0,
                'total_products' => $subtotal,
                'total' => $total,
                'total_paid' => 0,
                'total_shipping' => 0,
                'tax' => $tax
            ]);

            // Clear the cart
            Cart::destroy();

            return redirect()->route('checkout.success')->with('message', 'Order placed successfully! You will receive an email confirmation shortly.');
        } catch (\Exception $e) {
            Log::error('Guest checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was a problem processing your order. Please try again.')->withInput();
        }
    }
}

