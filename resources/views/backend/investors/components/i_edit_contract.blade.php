<form action="{{ route('investors.contract.uploadSignedContract', ['investor' => $investor->id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="signed_contract" class="form-label">اختر ملف العقد (PDF)</label>
        <input class="form-control @error('signed_contract') is-invalid @enderror" type="file" id="signed_contract"
            name="signed_contract" accept="application/pdf" required>
        @error('signed_contract')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-upload"></i> رفع عقد جديد
    </button>
</form>