<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Vendor;
use App\Services\ActivityLogger;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function dashboard(): View
    {
        $vendor = $this->currentVendor();

        return view('vendor.dashboard', [
            'vendor' => $vendor,
            'stats' => [
                'daily_sales' => Transaction::where('vendor_id', $vendor->id)->whereDate('transaction_date', today())->sum('total_amount'),
                'weekly_sales' => Transaction::where('vendor_id', $vendor->id)->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount'),
                'monthly_sales' => Transaction::where('vendor_id', $vendor->id)->whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
                'products' => $vendor->products()->count(),
            ],
            'recentSales' => Transaction::with('customer')->where('vendor_id', $vendor->id)->latest('transaction_date')->take(8)->get(),
            'topProducts' => TransactionItem::query()
                ->select('product_id', DB::raw('SUM(quantity) as units_sold'))
                ->whereHas('product', fn ($query) => $query->where('vendor_id', $vendor->id))
                ->with('product')
                ->groupBy('product_id')
                ->orderByDesc('units_sold')
                ->take(5)
                ->get(),
        ]);
    }

    public function products(): View
    {
        $vendor = $this->currentVendor();

        return view('vendor.products.index', [
            'vendor' => $vendor,
            'products' => Product::where('vendor_id', $vendor->id)->withTrashed()->latest()->paginate(12),
        ]);
    }

    public function createProduct(): View
    {
        $this->authorize('create', Product::class);

        return view('vendor.products.create', [
            'vendor' => $this->currentVendor(),
        ]);
    }

    public function storeProduct(StoreProductRequest $request, QrCodeService $qrCodeService, ActivityLogger $activityLogger): RedirectResponse
    {
        $vendor = $this->currentVendor();

        $product = $vendor->products()->create(array_merge(
            $request->validated(),
            ['is_available' => $request->boolean('is_available')]
        ));

        $qrCodeService->generateProductQr($product);

        $activityLogger->log('product.create', [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
        ], $request->user(), $request->ip());

        return redirect()->route('vendor.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product): View
    {
        $this->authorize('update', $product);

        return view('vendor.products.edit', [
            'product' => $product,
            'vendor' => $this->currentVendor(),
        ]);
    }

    public function updateProduct(UpdateProductRequest $request, Product $product, QrCodeService $qrCodeService, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('update', $product);

        $product->update(array_merge(
            $request->validated(),
            ['is_available' => $request->boolean('is_available')]
        ));

        if (! $product->qr_code_path) {
            $qrCodeService->generateProductQr($product);
        }

        $activityLogger->log('product.update', [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
        ], $request->user(), $request->ip());

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        $activityLogger->log('product.delete', [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
        ], request()->user(), request()->ip());

        return back()->with('success', 'Product archived successfully.');
    }

    public function toggleAvailability(Product $product, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('update', $product);

        $product->update([
            'is_available' => ! $product->is_available,
        ]);

        $activityLogger->log('product.availability.toggle', [
            'product_id' => $product->id,
            'is_available' => $product->is_available,
        ], request()->user(), request()->ip());

        return back()->with('success', 'Product availability updated.');
    }

    public function publicProfile(Vendor $vendor): View
    {
        return view('vendor.profile', [
            'vendor' => $vendor->load([
                'marketMap',
                'products' => fn ($query) => $query->available()->orderBy('product_name'),
            ]),
        ]);
    }

    protected function currentVendor(): Vendor
    {
        $vendor = auth()->user()?->vendor;

        abort_unless($vendor, 403, 'Vendor account is not linked to a stall.');

        return $vendor;
    }
}
