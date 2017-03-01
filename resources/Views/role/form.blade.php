<div class="one fields">
    <div class="sixteen wide field{{ $errors->has('name') ? ' error' : '' }}">
        <label for="name">Role Name</label>
        <input type="text" id="name" name="name" placeholder="Role Name" value="{{ old('name', $role->name) }}">
    </div>
</div>
