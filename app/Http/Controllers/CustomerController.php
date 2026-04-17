<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\ToggleBookmarkRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function dashboard(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('customer.dashboard', [
            'stats' => [
                'orders' => $user->customerTransactions()->count(),
                'spent' => $user->customerTransactions()->sum('total_amount'),
                'bookmarks' => $user->bookmarkedVendors()->count(),
                'cart_items' => count(session('cart', [])),
            ],
            'transactions' => $user->customerTransactions()->with('vendor')->latest('transaction_date')->take(5)->get(),
            'favoriteVendors' => $user->bookmarkedVendors()->with('marketMap')->take(6)->get(),
        ]);
    }

    public function transactions(): View
    {
        $this->authorize('viewAny', Transaction::class);

        return view('customer.transactions', [
            'transactions' => Transaction::with('vendor', 'items.product')
                ->where('customer_id', auth()->id())
                ->latest('transaction_date')
                ->paginate(12),
        ]);
    }

    public function bookmarks(): View
    {
        return view('customer.bookmarks', [
            'vendors' => auth()->user()->bookmarkedVendors()->with('marketMap', 'products')->paginate(12),
        ]);
    }

    public function toggleBookmark(ToggleBookmarkRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $vendorId = $request->integer('vendor_id');
        $changes = $request->user()->bookmarkedVendors()->toggle([$vendorId]);

        $activityLogger->log('bookmark.toggle', [
            'vendor_id' => $vendorId,
            'attached' => $changes['attached'],
            'detached' => $changes['detached'],
        ], $request->user(), $request->ip());

        return back()->with('success', 'Vendor bookmark list updated.');
    }

    public function cart(): View
    {
        return view('customer.cart', $this->cartViewData());
    }

    public function addToCart(CartItemRequest $request, Product $product, ActivityLogger $activityLogger): RedirectResponse
    {
        abort_if(! $product->is_available || $product->stock_quantity < 1, 422, 'This product is currently unavailable.');

        $cart = session('cart', []);
        $quantity = min($request->integer('quantity'), $product->stock_quantity);
        $existingQuantity = $cart[$product->id]['quantity'] ?? 0;

        $cart[$product->id] = [
            'quantity' => min($existingQuantity + $quantity, $product->stock_quantity),
        ];

        session(['cart' => $cart]);

        $activityLogger->log('cart.add', [
            'product_id' => $product->id,
            'quantity' => $cart[$product->id]['quantity'],
        ], $request->user(), $request->ip());

        return back()->with('success', 'Product added to cart.');
    }

    public function updateCart(CartItemRequest $request, Product $product, ActivityLogger $activityLogger): RedirectResponse
    {
        $cart = session('cart', []);

        abort_unless(isset($cart[$product->id]), 404);

        $cart[$product->id]['quantity'] = min($request->integer('quantity'), $product->stock_quantity);
        session(['cart' => $cart]);

        $activityLogger->log('cart.update', [
            'product_id' => $product->id,
            'quantity' => $cart[$product->id]['quantity'],
        ], $request->user(), $request->ip());

        return back()->with('success', 'Cart updated successfully.');
    }

    public function removeFromCart(Product $product, ActivityLogger $activityLogger): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        $activityLogger->log('cart.remove', [
            'product_id' => $product->id,
        ], request()->user(), request()->ip());

        return back()->with('success', 'Product removed from cart.');
    }

    public function checkout(): View|RedirectResponse
    {
        $data = $this->cartViewData();

        if ($data['cartItems']->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is currently empty.');
        }

        return view('customer.checkout', $data);
    }

    public function placeOrder(CheckoutRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('create', Transaction::class);

        $cartItems = $this->buildCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is currently empty.');
        }

        $paymentMethod = $request->validated()['payment_method'];
        $paymentStatus = $request->validated()['payment_status'] ?? ($paymentMethod === 'cash' ? 'paid' : 'pending');
        $transactions = [];

        DB::transaction(function () use ($cartItems, $paymentMethod, $paymentStatus, &$transactions): void {
            $grouped = $cartItems->groupBy(fn (array $item) => $item['product']->vendor_id);

            foreach ($grouped as $vendorId => $items) {
                $total = $items->sum('subtotal');

                $transaction = Transaction::create([
                    'customer_id' => auth()->id(),
                    'vendor_id' => (int) $vendorId,
                    'total_amount' => $total,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'transaction_date' => now(),
                ]);

                foreach ($items as $item) {
                    $lockedProduct = Product::whereKey($item['product']->id)->lockForUpdate()->firstOrFail();

                    if ($lockedProduct->stock_quantity < $item['quantity']) {
                        abort(422, 'One or more items no longer have enough stock.');
                    }

                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $lockedProduct->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $lockedProduct->price,
                        'subtotal' => $item['subtotal'],
                    ]);

                    $lockedProduct->update([
                        'stock_quantity' => $lockedProduct->stock_quantity - $item['quantity'],
                        'is_available' => ($lockedProduct->stock_quantity - $item['quantity']) > 0,
                    ]);
                }

                $transactions[] = $transaction;
            }
        });

        session()->forget('cart');

        $activityLogger->log('transaction.checkout', [
            'transaction_ids' => collect($transactions)->pluck('id')->all(),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
        ], $request->user(), $request->ip());

        return redirect()->route('customer.transactions')->with('success', 'Checkout complete. Your transaction history has been updated.');
    }

    protected function cartViewData(): array
    {
        $cartItems = $this->buildCartItems();

        return [
            'cartItems' => $cartItems,
            'cartTotal' => $cartItems->sum('subtotal'),
        ];
    }

    protected function buildCartItems(): Collection
    {
        $cart = collect(session('cart', []));

        if ($cart->isEmpty()) {
            return collect();
        }

        $products = Product::with('vendor')->whereIn('id', $cart->keys())->get()->keyBy('id');

        return $cart->map(function (array $entry, $productId) use ($products): ?array {
            $product = $products->get((int) $productId);

            if (! $product) {
                return null;
            }

            $quantity = min((int) $entry['quantity'], $product->stock_quantity);

            if ($quantity < 1) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $quantity * (float) $product->price,
            ];
        })->filter()->values();
    }
}
