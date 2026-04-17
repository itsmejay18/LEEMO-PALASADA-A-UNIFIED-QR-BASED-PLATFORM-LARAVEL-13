<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanResolveRequest;
use App\Models\MarketMap;
use App\Models\Product;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class QRController extends Controller
{
    public function showProduct(Product $product): View
    {
        return view('qr.product', [
            'product' => $product->load('vendor'),
            'cartQuantity' => session('cart.'.$product->id.'.quantity', 0),
        ]);
    }

    public function productImage(Product $product, QrCodeService $qrCodeService): Response
    {
        return response($qrCodeService->productSvg($product), 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function downloadProduct(Product $product, QrCodeService $qrCodeService): Response
    {
        return response($qrCodeService->productSvg($product), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="product-'.$product->id.'-qr.svg"',
        ]);
    }

    public function showLocation(string $qrLocationCode): View
    {
        $marketMap = MarketMap::with('vendor')->where('qr_location_code', $qrLocationCode)->firstOrFail();
        $markers = MarketMap::with('vendor')->orderBy('stall_number')->get()->map(function (MarketMap $map): array {
            return [
                'stall_number' => $map->stall_number,
                'floor_level' => $map->floor_level,
                'zone_section' => $map->zone_section,
                'qr_location_code' => $map->qr_location_code,
                'latitude' => (float) $map->latitude,
                'longitude' => (float) $map->longitude,
                'vendor_name' => $map->vendor?->vendor_name,
                'target_url' => route('qr.locations.show', $map->qr_location_code),
            ];
        })->all();

        return view('qr.location', [
            'marketMap' => $marketMap,
            'mapMarkers' => $markers,
        ]);
    }

    public function locationImage(string $qrLocationCode, QrCodeService $qrCodeService): Response
    {
        $marketMap = MarketMap::where('qr_location_code', $qrLocationCode)->firstOrFail();

        return response($qrCodeService->locationSvg($marketMap), 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function downloadLocation(string $qrLocationCode, QrCodeService $qrCodeService): Response
    {
        $marketMap = MarketMap::where('qr_location_code', $qrLocationCode)->firstOrFail();

        return response($qrCodeService->locationSvg($marketMap), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="'.$marketMap->qr_location_code.'-qr.svg"',
        ]);
    }

    public function productApi(Product $product): JsonResponse
    {
        $product->load('vendor');

        return response()->json([
            'type' => 'product',
            'id' => $product->id,
            'product_name' => $product->product_name,
            'description' => $product->description,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'is_available' => $product->is_available,
            'vendor' => [
                'id' => $product->vendor->id,
                'vendor_name' => $product->vendor->vendor_name,
                'stall_number' => $product->vendor->stall_number,
            ],
            'web_url' => route('qr.products.show', $product),
            'qr_image_url' => route('qr.products.image', $product),
        ]);
    }

    public function locationApi(string $qrLocationCode): JsonResponse
    {
        $marketMap = MarketMap::with('vendor')->where('qr_location_code', $qrLocationCode)->firstOrFail();

        return response()->json([
            'type' => 'location',
            'stall_number' => $marketMap->stall_number,
            'floor_level' => $marketMap->floor_level,
            'zone_section' => $marketMap->zone_section,
            'qr_location_code' => $marketMap->qr_location_code,
            'latitude' => $marketMap->latitude,
            'longitude' => $marketMap->longitude,
            'vendor' => $marketMap->vendor ? [
                'id' => $marketMap->vendor->id,
                'vendor_name' => $marketMap->vendor->vendor_name,
                'stall_number' => $marketMap->vendor->stall_number,
            ] : null,
            'web_url' => route('qr.locations.show', $marketMap->qr_location_code),
            'qr_image_url' => route('qr.locations.image', $marketMap->qr_location_code),
        ]);
    }

    public function resolve(ScanResolveRequest $request): JsonResponse
    {
        $code = trim($request->validated()['code']);

        if (filter_var($code, FILTER_VALIDATE_URL)) {
            return response()->json([
                'type' => 'url',
                'redirect_url' => $code,
            ]);
        }

        if (is_numeric($code) && $product = Product::find((int) $code)) {
            return response()->json([
                'type' => 'product',
                'redirect_url' => route('qr.products.show', $product),
            ]);
        }

        if ($location = MarketMap::where('qr_location_code', $code)->first()) {
            return response()->json([
                'type' => 'location',
                'redirect_url' => route('qr.locations.show', $location->qr_location_code),
            ]);
        }

        return response()->json([
            'message' => 'QR code not recognized.',
        ], 404);
    }
}
