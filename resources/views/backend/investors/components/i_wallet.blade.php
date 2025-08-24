<form action="{{ route('investors.updateCapital') }}" method="POST" dir="rtl">
    @csrf
    <div class="row align-items-end">
        <div class="col-9">
            <div class="form-group">
                <label for="capital" class="form-label">رأس المال</label>
                <input type="number" class="form-control text-end" name="capital" required min="1"
                    value="{{ $investor->user->wallet->capital }}">
            </div>
        </div>
        <div class="col-3">
            <button class="btn btn-primary w-100" type="submit">حفظ</button>
        </div>
    </div>
    <input type="hidden" name="investor_id" value="{{ $investor->id }}">
</form>
