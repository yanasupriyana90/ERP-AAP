<div class="form-group">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
    <small>Kosongkan jika tidak ingin mengubah password</small>
</div>

<div class="form-group">
    <label>Konfirmasi Password</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>

<div class="form-group">
    <label>Departemen</label>
    <select name="department_id" class="form-control">
        <option value="">Pilih Departemen</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Role</label>
    <select name="role" class="form-control">
        @foreach(['Superuser', 'Direktur', 'Manager', 'Supervisor', 'Staff'] as $role)
            <option value="{{ $role }}" {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}>
                {{ $role }}
            </option>
        @endforeach
    </select>
</div>
