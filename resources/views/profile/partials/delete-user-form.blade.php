<div class="content-card p-4 border-danger-subtle">
    <p class="section-label text-danger">Account Removal</p>
    <h2 class="subheading mb-3">Delete this account</h2>
    <p class="text-muted">Deleting your account will sign you out and archive your profile. Enter your current password to confirm.</p>

    <form method="POST" action="{{ route('profile.destroy') }}" class="row g-3 mt-1">
        @csrf
        @method('DELETE')

        <div class="col-md-6">
            <label class="form-label" for="delete_password">Current Password</label>
            <input id="delete_password" type="password" name="password" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-danger" type="submit">Delete Account</button>
        </div>
    </form>
</div>
