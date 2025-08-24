<form action="{{ route('investors.update_comission') }}" method="POST" dir="rtl">
    @csrf
    <div class="row align-items-end">
        <div class="col-9">
            <div class="form-group">
                <label for="update_comission" class="form-label">نسبة الارباح</label>
                <input type="number" class="form-control text-end" name="percentage" required min="1" step="0.01" value="{{ $investor->percentage }}">
           </div>
        </div>
        <div class="col-3">
            <button class="btn btn-primary w-100" type="submit">حفظ</button>
        </div>
    </div>
    <input type="hidden" name="investor_id" value="{{ $investor->id }}">
</form>
