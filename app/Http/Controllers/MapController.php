<?php

namespace App\Http\Controllers;

use App\Models\MarketMap;
use App\Models\Collection;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MapController extends Controller
{
    public function landing(): View
    {
        return view('welcome');
    }

    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $targetCode = $request->string('target')->toString();
        $filters = [
            'occupancy' => $request->string('occupancy')->toString(),
            'payment' => $request->string('payment')->toString(),
            'contract' => $request->string('contract')->toString(),
            'category' => $request->string('category')->toString(),
        ];
        $paidVendorIds = $this->paidVendorIds();
        $allMarketMaps = MarketMap::with('vendor')->orderBy('stall_number')->get();
        $marketMaps = $this->filterMarketMaps($allMarketMaps, $filters, $paidVendorIds);
        $selectedMap = $targetCode
            ? $allMarketMaps->firstWhere('qr_location_code', $targetCode)
            : null;

        return view('map.index', [
            'search' => $search,
            'searchResults' => $this->searchResults($search),
            'marketMaps' => $marketMaps,
            'mapMarkers' => $this->mapMarkers($marketMaps, $paidVendorIds),
            'selectedMap' => $selectedMap,
            'filters' => $filters,
            'categories' => Vendor::query()
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'mapStats' => [
                'occupied' => $allMarketMaps->filter(fn (MarketMap $map) => filled($map->vendor))->count(),
                'vacant' => $allMarketMaps->filter(fn (MarketMap $map) => blank($map->vendor))->count(),
                'paid' => $allMarketMaps->filter(fn (MarketMap $map) => $this->paymentStatus($map, $paidVendorIds) === 'paid')->count(),
                'unpaid' => $allMarketMaps->filter(fn (MarketMap $map) => $this->paymentStatus($map, $paidVendorIds) === 'unpaid')->count(),
                'expiring' => $allMarketMaps->filter(fn (MarketMap $map) => $this->contractStatus($map->vendor) === 'expiring')->count(),
            ],
        ]);
    }

    public function data(): JsonResponse
    {
        $marketMaps = MarketMap::with('vendor')->orderBy('stall_number')->get();

        return response()->json([
            'markers' => $this->mapMarkers($marketMaps, $this->paidVendorIds()),
        ]);
    }

    protected function searchResults(string $search)
    {
        if ($search === '') {
            return collect();
        }

        return Vendor::query()
            ->with('marketMap')
            ->where(function ($query) use ($search): void {
                $query->where('vendor_name', 'like', '%'.$search.'%')
                    ->orWhere('stall_number', 'like', '%'.$search.'%')
                    ->orWhereHas('products', fn ($products) => $products->where('product_name', 'like', '%'.$search.'%'));
            })
            ->orderBy('vendor_name')
            ->take(12)
            ->get();
    }

    protected function mapMarkers($marketMaps, $paidVendorIds): array
    {
        return $marketMaps->map(function (MarketMap $marketMap) use ($paidVendorIds): array {
            $vendor = $marketMap->vendor;

            return [
                'stall_number' => $marketMap->stall_number,
                'floor_level' => $marketMap->floor_level,
                'zone_section' => $marketMap->zone_section,
                'qr_location_code' => $marketMap->qr_location_code,
                'latitude' => (float) $marketMap->latitude,
                'longitude' => (float) $marketMap->longitude,
                'vendor_name' => $vendor?->vendor_name,
                'vendor_category' => $vendor?->category,
                'contract_end_date' => $vendor?->contract_end_date?->toDateString(),
                'occupancy_status' => $vendor ? 'occupied' : 'vacant',
                'payment_status' => $this->paymentStatus($marketMap, $paidVendorIds),
                'contract_status' => $this->contractStatus($vendor),
                'target_url' => route('qr.locations.show', $marketMap->qr_location_code),
            ];
        })->all();
    }

    protected function filterMarketMaps($marketMaps, array $filters, $paidVendorIds)
    {
        return $marketMaps
            ->when($filters['occupancy'] !== '', fn ($maps) => $maps->filter(function (MarketMap $map) use ($filters): bool {
                return $filters['occupancy'] === 'vacant'
                    ? blank($map->vendor)
                    : filled($map->vendor);
            }))
            ->when($filters['payment'] !== '', fn ($maps) => $maps->filter(fn (MarketMap $map): bool => $this->paymentStatus($map, $paidVendorIds) === $filters['payment']))
            ->when($filters['contract'] !== '', fn ($maps) => $maps->filter(fn (MarketMap $map): bool => $this->contractStatus($map->vendor) === $filters['contract']))
            ->when($filters['category'] !== '', fn ($maps) => $maps->filter(fn (MarketMap $map): bool => $map->vendor?->category === $filters['category']))
            ->values();
    }

    protected function paidVendorIds()
    {
        return Collection::query()
            ->where('status', 'verified')
            ->whereBetween('collection_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->pluck('vendor_id')
            ->unique()
            ->values();
    }

    protected function paymentStatus(MarketMap $marketMap, $paidVendorIds): string
    {
        if (! $marketMap->vendor) {
            return 'vacant';
        }

        return $paidVendorIds->contains($marketMap->vendor->id) ? 'paid' : 'unpaid';
    }

    protected function contractStatus(?Vendor $vendor): string
    {
        if (! $vendor) {
            return 'vacant';
        }

        if (! $vendor->contract_end_date) {
            return 'untracked';
        }

        $contractEnd = Carbon::parse($vendor->contract_end_date)->endOfDay();

        if ($contractEnd->isPast()) {
            return 'expired';
        }

        return $contractEnd->lessThanOrEqualTo(now()->addDays(30)->endOfDay())
            ? 'expiring'
            : 'active';
    }
}
