<div class="one fields">
    <div class="sixteen wide field{{ $errors->has('name') ? ' error' : '' }}">
        <label for="name">Permission Name</label>
        <input type="text" id="name" name="name" placeholder="Permission Name" value="{{ old('name', $permission->name) }}">
    </div>
</div>
