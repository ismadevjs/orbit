<form action="{{ route('investors.update.duration') }}" method="POST" dir="rtl">
    @csrf

    <div class="d-flex align-items-center gap-3 mb-3 w-100">
        <input type="hidden" name="id" value="{{ $investor->id }}">
        <label for="duration" class="form-label mb-0 fw-bold">المدة:</label>
        <select name="duration" id="duration" class="form-select">
            <option value="start" {{ $investor->duration == 'start' ? 'selected' : '' }}>بداية الشهر</option>
            <option value="middle" {{ $investor->duration == 'middle' ? 'selected' : '' }}>منتصف الشهر</option>
        </select>
        <button type="submit" class="btn btn-success">حفظ</button>
    </div>
</form>