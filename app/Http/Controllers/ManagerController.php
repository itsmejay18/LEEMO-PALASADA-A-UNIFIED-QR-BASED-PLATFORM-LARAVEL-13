<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function dashboard(): View
    {
        $topVendors = Vendor::query()
            ->withSum('transactions as sales_total', 'total_amount')
            ->orderByDesc('sales_total')
            ->take(6)
            ->get();

        return view('manager.dashboard', [
            'stats' => [
                'active_vendors' => Vendor::active()->count(),
                'available_products' => Product::available()->count(),
                'monthly_sales' => Transaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
                'monthly_collections' => Collection::whereBetween('collection_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount_collected'),
            ],
            'topVendors' => $topVendors,
            'recentCollections' => Collection::with('vendor', 'collector')->latest('collection_date')->take(8)->get(),
        ]);
    }

    public function vendors(): View
    {
        $this->authorize('viewAny', Vendor::class);

        return view('manager.vendors.index', [
            'vendors' => Vendor::with('users', 'marketMap')->withTrashed()->orderBy('vendor_name')->paginate(12),
        ]);
    }

    public function createVendor(): View
    {
        $this->authorize('create', Vendor::class);

        return view('manager.vendors.create');
    }

    public function storeVendor(StoreVendorRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('logo')) {
            $payload['logo_path'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        $payload['is_active'] = $request->boolean('is_active');

        $vendor = Vendor::create($payload);

        $activityLogger->log('vendor.create', [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->vendor_name,
        ], $request->user(), $request->ip());

        return redirect()->route('manager.vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function editVendor(Vendor $vendor): View
    {
        $this->authorize('update', $vendor);

        return view('manager.vendors.edit', [
            'vendor' => $vendor,
        ]);
    }

    public function updateVendor(UpdateVendorRequest $request, Vendor $vendor, ActivityLogger $activityLogger): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('logo')) {
            $payload['logo_path'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        $payload['is_active'] = $request->boolean('is_active');

        $vendor->update($payload);

        $activityLogger->log('vendor.update', [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->vendor_name,
        ], $request->user(), $request->ip());

        return redirect()->route('manager.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroyVendor(Vendor $vendor, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        $activityLogger->log('vendor.delete', [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->vendor_name,
        ], request()->user(), request()->ip());

        return back()->with('success', 'Vendor archived successfully.');
    }

    public function reports(): View
    {
        $dailySales = Transaction::query()
            ->selectRaw('DATE(transaction_date) as report_date, SUM(total_amount) as total_sales')
            ->groupBy('report_date')
            ->orderByDesc('report_date')
            ->limit(14)
            ->get();

        $collectionSummary = Collection::query()
            ->select('status', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(amount_collected) as total_amount'))
            ->groupBy('status')
            ->get();

        return view('manager.reports', [
            'dailySales' => $dailySales,
            'collectionSummary' => $collectionSummary,
            'vendorPerformance' => Vendor::query()
                ->withSum('transactions as sales_total', 'total_amount')
                ->withCount('products')
                ->orderByDesc('sales_total')
                ->take(12)
                ->get(),
        ]);
    }
}
