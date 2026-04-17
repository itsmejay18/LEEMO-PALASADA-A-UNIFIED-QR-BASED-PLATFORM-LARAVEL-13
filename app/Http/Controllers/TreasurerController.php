<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTreasurerRecordRequest;
use App\Models\Collection;
use App\Models\TreasurerRecord;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TreasurerController extends Controller
{
    public function dashboard(): View
    {
        return view('treasurer.dashboard', [
            'stats' => [
                'pending_collections' => Collection::where('status', 'submitted')->count(),
                'verified_today' => TreasurerRecord::whereDate('verification_date', today())->sum('amount_verified'),
                'monthly_verified' => TreasurerRecord::whereBetween('verification_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount_verified'),
            ],
            'pendingCollections' => Collection::with('vendor', 'collector')->where('status', 'submitted')->latest('collection_date')->take(8)->get(),
            'recentRecords' => TreasurerRecord::with('collection.vendor')->latest('verification_date')->take(8)->get(),
        ]);
    }

    public function index(): View
    {
        return view('treasurer.index', [
            'collections' => Collection::with('vendor', 'collector', 'treasurerRecord')->latest('collection_date')->paginate(12),
        ]);
    }

    public function verify(Collection $collection): View
    {
        abort_if($collection->treasurerRecord, 422, 'This collection has already been verified.');

        return view('treasurer.verify', [
            'collection' => $collection->load('vendor', 'collector'),
            'suggestedReceiptNumber' => 'OR-'.now()->format('Y').'-'.str_pad((string) ($collection->id + 1000), 5, '0', STR_PAD_LEFT),
        ]);
    }

    public function store(StoreTreasurerRecordRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $collection = Collection::findOrFail($request->integer('collection_id'));

        abort_if($collection->treasurerRecord, 422, 'This collection has already been verified.');

        $record = TreasurerRecord::create([
            'treasurer_id' => $request->user()->id,
            'collection_id' => $collection->id,
            'amount_verified' => $request->validated()['amount_verified'],
            'verification_date' => $request->validated()['verification_date'],
            'official_receipt_number' => $request->validated()['official_receipt_number'],
            'notes' => $request->validated()['notes'] ?? null,
        ]);

        $collection->update([
            'status' => $request->validated()['collection_status'],
        ]);

        $activityLogger->log('collection.verify', [
            'collection_id' => $collection->id,
            'treasurer_record_id' => $record->id,
            'status' => $collection->status,
        ], $request->user(), $request->ip());

        return redirect()->route('treasurer.receipts.show', $record)->with('success', 'Collection verification recorded successfully.');
    }

    public function receipt(TreasurerRecord $treasurerRecord): View
    {
        $this->authorize('view', $treasurerRecord);

        return view('treasurer.receipt', [
            'record' => $treasurerRecord->load('treasurer', 'collection.vendor', 'collection.collector'),
        ]);
    }
}
