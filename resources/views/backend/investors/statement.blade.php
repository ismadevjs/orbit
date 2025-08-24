@extends('layouts.backend')

@push('styles')
<style>
    .grok-pdf-container {
        max-width: 1200px;
        margin: 60px auto; /* Added top margin to push content down */
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .grok-pdf-viewer {
        width: 100%;
        height: 850px;
        border: none;
        border-radius: 10px;
        background: #fff;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .grok-pdf-viewer:hover {
        box-shadow: inset 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .grok-pdf-title {
        font-family: 'Cairo', sans-serif;
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 30px;
        position: relative;
    }

    .grok-pdf-title::after {
        content: '';
        width: 80px;
        height: 4px;
        background: #3498db;
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .grok-pdf-btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        margin: 10px 5px;
        background: #3498db;
        color: #fff;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .grok-pdf-btn:hover {
        background: #fff;
        color: #3498db;
        border-color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .grok-pdf-error {
        color: #e74c3c;
        text-align: center;
        font-size: 1.2rem;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        margin: 20px 0;
    }

    .grok-pdf-btn-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="grok-pdf-container">
    <h1 class="grok-pdf-title">كشف الحساب الشهري</h1>
    
    @if(isset($fileName))
        <iframe 
            src="{{ Storage::url($fileName) }}" 
            class="grok-pdf-viewer"
            frameborder="0">
        </iframe>
    @else
        <p class="grok-pdf-error">حدث خطأ أثناء تحميل الكشف</p>
    @endif

    <div class="d-flex justify-content-center mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-primary w-50 mx-1">رجوع</a>
        @if(isset($fileName))
            <a href="{{ Storage::url($fileName) }}" download class="btn btn-success w-50 mx-1">تحميل PDF</a>
        @endif
    </div>
</div>
@endsection