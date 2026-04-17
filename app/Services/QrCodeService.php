<?php

namespace App\Services;

use App\Models\MarketMap;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateProductQr(Product $product): string
    {
        $path = 'qrcodes/products/product-'.$product->getKey().'.svg';
        $content = $this->sanitizeContent(route('qr.products.show', $product));

        Storage::disk('public')->put($path, $this->makeSvg($content));

        $product->forceFill(['qr_code_path' => $path])->saveQuietly();

        return $path;
    }

    public function generateLocationQr(MarketMap $marketMap): string
    {
        $path = $this->locationPath($marketMap->qr_location_code);
        $content = $this->sanitizeContent(route('qr.locations.show', $marketMap->qr_location_code));

        Storage::disk('public')->put($path, $this->makeSvg($content));

        return $path;
    }

    public function productSvg(Product $product): string
    {
        $path = $product->qr_code_path ?: $this->generateProductQr($product);

        return Storage::disk('public')->get($path);
    }

    public function locationSvg(MarketMap $marketMap): string
    {
        $path = $this->locationPath($marketMap->qr_location_code);

        if (! Storage::disk('public')->exists($path)) {
            $this->generateLocationQr($marketMap);
        }

        return Storage::disk('public')->get($path);
    }

    public function locationPath(string $qrLocationCode): string
    {
        return 'qrcodes/locations/'.Str::slug($qrLocationCode).'.svg';
    }

    protected function makeSvg(string $content): string
    {
        return (string) QrCode::format('svg')->size(320)->margin(1)->generate($content);
    }

    protected function sanitizeContent(string $content): string
    {
        $clean = strip_tags($content);
        $clean = preg_replace('/[\x00-\x1F\x7F]+/', '', $clean) ?? '';

        return Str::limit($clean, 2048, '');
    }
}
