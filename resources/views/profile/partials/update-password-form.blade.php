<div class="content-card p-4 h-100">
    <p class="section-label">Password Security</p>
    <h2 class="subheading mb-3">Change your password</h2>

    <form method="POST" action="{{ route('password.update') }}" class="row g-3">
        @csrf
        @method('PUT')

        <div class="col-12">
            <label class="form-label" for="current_password">Current Password</label>
            <input id="current_password" type="password" name="current_password" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label" for="password">New Password</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-brand" type="submit">Update Password</button>
        </div>
    </form>
</div>
