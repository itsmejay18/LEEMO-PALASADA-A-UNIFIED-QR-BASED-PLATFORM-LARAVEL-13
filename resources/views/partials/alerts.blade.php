@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <div class="fw-semibold mb-2">Please review the following:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
