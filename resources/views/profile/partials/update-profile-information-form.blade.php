<div class="content-card p-4 h-100">
    <p class="section-label">Profile Details</p>
    <h2 class="subheading mb-3">Update your personal information</h2>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        @method('PATCH')

        <div class="col-12 d-flex align-items-center gap-3">
            @php
                $profilePhotoUrl = $user->profile_photo_path
                    ? asset('storage/'.$user->profile_photo_path)
                    : Vite::asset('resources/vendor/coreui-kit/assets/img/avatars/8.jpg');
            @endphp
            <img class="profile-photo-preview" src="{{ $profilePhotoUrl }}" alt="{{ $user->name }} profile photo">
            <div class="flex-grow-1">
                <label class="form-label" for="profile_photo">Profile Photo</label>
                <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/*">
            </div>
        </div>

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
