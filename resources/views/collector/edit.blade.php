@extends('layouts.app')

@section('title', 'Edit Collection | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Collector Workflow</p>
        <h1 class="page-title mb-3">Update collection #{{ $collection->id }}</h1>
        @include('collector._form', ['collection' => $collection])
    </div>
@endsection
