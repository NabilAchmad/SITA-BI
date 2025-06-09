<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-user-circle me-2 text-primary"></i>Admin Profile</h3>
        <button class="btn btn-success"><i class="fas fa-save me-1"></i> Save Changes</button>
    </div>

    <!-- Avatar Upload -->
    <div class="text-center position-relative mb-4" style="width: 140px; margin: 0 auto;">
        <div style="position: relative; display: inline-block;">
            <img id="avatarPreview" src="{{ asset('assets/img/team/erland.jpg') }}"
                class="rounded-circle border border-3 border-primary shadow-sm" alt="Admin Avatar"
                style="width: 140px; height: 140px; object-fit: cover;">

            <label for="avatarInput"
                class="position-absolute bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center shadow"
                style="width: 34px; height: 34px; bottom: 2px; right: 2px; cursor: pointer;">
                <i class="fas fa-pencil-alt small"></i>
            </label>
        </div>

        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*"
            onchange="previewAvatar(event)">
    </div>


    <!-- Form Fields -->
    <div class="row mb-3">
        <label for="name" class="col-sm-2 col-form-label fw-semibold">
            <i class="fas fa-user me-1 text-primary"></i>Name:
        </label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" value="John Doe" class="form-control">
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-sm-2 col-form-label fw-semibold">
            <i class="fas fa-envelope me-1 text-primary"></i>Email:
        </label>
        <div class="col-sm-10">
            <input type="email" id="email" name="email" value="admin@example.com" class="form-control">
        </div>
    </div>

    <div class="row mb-3">
        <label for="role" class="col-sm-2 col-form-label fw-semibold">
            <i class="fas fa-user-shield me-1 text-primary"></i>Role:
        </label>
        <div class="col-sm-10">
            <input type="text" id="role" name="role" value="Administrator" class="form-control" disabled>
        </div>
    </div>

    <div class="row mb-4">
        <label for="joined" class="col-sm-2 col-form-label fw-semibold">
            <i class="fas fa-calendar-alt me-1 text-primary"></i>Joined:
        </label>
        <div class="col-sm-10">
            <input type="text" id="joined" name="joined" value="January 1, 2020" class="form-control" disabled>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Update Profile
        </button>
    </div>
</form>


<script>
    function previewAvatar(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('avatarPreview').src = reader.result;
        }
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
