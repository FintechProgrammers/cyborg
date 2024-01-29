<div class="mb-3">
    <label for="example-text-input" class="form-label">Name</label>
    <input class="form-control" type="text" name="name" value="{{ isset($admin) ? $admin->name : '' }}">
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Email</label>
    <input class="form-control" type="email" name="email" value="{{ isset($admin) ? $admin->email : '' }}" {{ isset($admin) ? 'readonly' : '' }}>
    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
@if (!isset($admin))
    <div class="mb-3">
        <label for="example-text-input" class="form-label">Password</label>
        <input class="form-control" type="password" name="password" placeholder="Enter password">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
@endif
<div class="mb-3">
    <label for="example-text-input" class="form-label">Roles</label>
    <select name="roles[]" class="form-control select" id="" multiple>
        <option value="">--select--roles--</option>
        @foreach ($roles as $item)
            <option value="{{ $item->name }}"
                {{ isset($admin) ? (in_array($item['name'], json_decode($admin->getRoleNames())) ? 'selected' : null) : '' }}>
                {{ $item->name }}</option>
        @endforeach
    </select>
    @error('roles')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="mt-4">
    <button type="submit" class="btn btn-primary w-lg">
        Submit
    </button>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select').select2();
        })
    </script>
@endpush
