<?php

namespace App\Http\Controllers;

use App\Models\HeaderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HeaderItemController extends Controller
{
    public function markRead(Request $request, HeaderItem $headerItem): RedirectResponse
    {
        abort_unless($headerItem->user_id === $request->user()->id, 403);

        if (! $headerItem->read_at) {
            $headerItem->forceFill(['read_at' => now()])->save();
        }

        return back();
    }
}
