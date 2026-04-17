<?php

namespace App\Http\Controllers;

use App\Models\MarketMap;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function landing(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $marketMaps = MarketMap::with('vendor')->orderBy('stall_number')->get();

        return view('welcome', [
            'featuredVendors' => Vendor::active()->with('marketMap')->orderBy('vendor_name')->take(6)->get(),
            'topProducts' => Product::available()->with('vendor')->orderBy('product_name')->take(8)->get(),
            'search' => $search,
            'searchResults' => $this->searchResults($search),
            'mapMarkers' => $this->mapMarkers($marketMaps),
        ]);
    }

    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $targetCode = $request->string('target')->toString();
        $marketMaps = MarketMap::with('vendor')->orderBy('stall_number')->get();
        $selectedMap = $targetCode
            ? $marketMaps->firstWhere('qr_location_code', $targetCode)
            : null;

        return view('map.index', [
            'search' => $search,
            'searchResults' => $this->searchResults($search),
            'marketMaps' => $marketMaps,
            'mapMarkers' => $this->mapMarkers($marketMaps),
            'selectedMap' => $selectedMap,
        ]);
    }

    public function data(): JsonResponse
    {
        $marketMaps = MarketMap::with('vendor')->orderBy('stall_number')->get();

        return response()->json([
            'markers' => $this->mapMarkers($marketMaps),
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

    protected function mapMarkers($marketMaps): array
    {
        return $marketMaps->map(function (MarketMap $marketMap): array {
            return [
                'stall_number' => $marketMap->stall_number,
                'floor_level' => $marketMap->floor_level,
                'zone_section' => $marketMap->zone_section,
                'qr_location_code' => $marketMap->qr_location_code,
                'latitude' => (float) $marketMap->latitude,
                'longitude' => (float) $marketMap->longitude,
                'vendor_name' => $marketMap->vendor?->vendor_name,
                'target_url' => route('qr.locations.show', $marketMap->qr_location_code),
            ];
        })->all();
    }
}
