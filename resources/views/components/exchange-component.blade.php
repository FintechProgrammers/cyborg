<div class="mb-3">
    <label>Exchange</label>
    <select name="exchange" id="exchange" class="form-control">
        <option value="">--select--exchange--</option>
        @foreach ($exchange as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
    </select>
</div>
