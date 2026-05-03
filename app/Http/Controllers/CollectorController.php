<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;
use App\Models\Vendor;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CollectorController extends Controller
{
    public function dashboard(): View
    {
        $collector = auth()->user();

        return view('collector.dashboard', [
            'stats' => [
                'assigned_records' => Collection::where('collector_id', $collector->id)->count(),
                'monthly_total' => Collection::where('collector_id', $collector->id)->whereBetween('collection_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount_collected'),
                'pending_verification' => Collection::where('collector_id', $collector->id)->where('status', 'submitted')->count(),
            ],
            'collections' => Collection::with('vendor', 'treasurerRecord')->where('collector_id', $collector->id)->latest('collection_date')->take(8)->get(),
        ]);
    }

    public function index(): View
    {
        return view('collector.index', [
            'collections' => Collection::with('vendor', 'treasurerRecord')
                ->where('collector_id', auth()->id())
                ->latest('collection_date')
                ->paginate(12),
            'vendors' => Vendor::active()->orderBy('vendor_name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Collection::class);

        return view('collector.create', [
            'vendors' => Vendor::active()->orderBy('vendor_name')->get(),
        ]);
    }

    public function store(StoreCollectionRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $payload = $request->validated();
        $payload['collector_id'] = $request->user()->id;
        $payload['status'] = $payload['status'] ?? 'submitted';

        if ($request->hasFile('proof_of_collection')) {
            $payload['proof_of_collection_path'] = $request->file('proof_of_collection')->store('collections/proofs', 'public');
        }

        $collection = Collection::create($payload);

        $activityLogger->log('collection.create', [
            'collection_id' => $collection->id,
            'vendor_id' => $collection->vendor_id,
            'amount_collected' => $collection->amount_collected,
        ], $request->user(), $request->ip());

        return redirect()->route('collector.collections.index')->with('success', 'Collection recorded successfully.');
    }

    public function edit(Collection $collection): View
    {
        $this->authorize('update', $collection);

        return view('collector.edit', [
            'collection' => $collection,
            'vendors' => Vendor::active()->orderBy('vendor_name')->get(),
        ]);
    }

    public function update(UpdateCollectionRequest $request, Collection $collection, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('update', $collection);

        $payload = $request->validated();

        if ($request->hasFile('proof_of_collection')) {
            $payload['proof_of_collection_path'] = $request->file('proof_of_collection')->store('collections/proofs', 'public');
        }

        $collection->update($payload);

        $activityLogger->log('collection.update', [
            'collection_id' => $collection->id,
            'status' => $collection->status,
        ], $request->user(), $request->ip());

        return redirect()->route('collector.collections.index')->with('success', 'Collection updated successfully.');
    }
}
