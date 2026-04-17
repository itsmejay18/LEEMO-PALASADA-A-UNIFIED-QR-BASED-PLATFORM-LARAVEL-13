<div class="content-card p-4 h-100">
    <p class="section-label">Profile Details</p>
    <h2 class="subheading mb-3">Update your personal information</h2>

    <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
        @csrf
        @method('PATCH')

        <div class="col-12">
            <label class="form-label" for="profile_name">Name</label>
            <input id="profile_name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label" for="profile_email">Email</label>
            <input id="profile_email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="col-12">
            <button class="btn btn-brand" type="submit">Save Profile</button>
        </div>
    </form>
</div>
