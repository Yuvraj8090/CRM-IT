@csrf

<div class="mb-3">
    <label>Key</label>
    <input type="text" name="key" class="form-control" value="{{ old('key', $setting->key ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Display Name</label>
    <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $setting->display_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Value</label>
    <input type="text" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}">
</div>

<div class="mb-3">
    <label>Details</label>
    <textarea name="details" class="form-control">{{ old('details', $setting->details ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Type</label>
    <select name="type" class="form-control" required>
        <option value="text" {{ (old('type', $setting->type ?? '') == 'text') ? 'selected' : '' }}>Text</option>
        <option value="number" {{ (old('type', $setting->type ?? '') == 'number') ? 'selected' : '' }}>Number</option>
        <option value="boolean" {{ (old('type', $setting->type ?? '') == 'boolean') ? 'selected' : '' }}>Boolean</option>
    </select>
</div>

<div class="mb-3">
    <label>Order</label>
    <input type="number" name="order" class="form-control" value="{{ old('order', $setting->order ?? '') }}">
</div>

<div class="mb-3">
    <label>Group</label>
    <input type="text" name="group" class="form-control" value="{{ old('group', $setting->group ?? '') }}">
</div>

<button type="submit" class="btn btn-success">Save</button>
