@extends('layouts.app')

@section('title', 'Record Collection | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Collector Workflow</p>
        <h1 class="page-title mb-3">Record a vendor collection</h1>
        @include('collector._form')
    </div>
@endsection
