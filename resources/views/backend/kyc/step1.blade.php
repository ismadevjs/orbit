@extends('layouts.backend')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .tempus-dominus-widget {
            font-family: 'Tajawal', 'Arial', sans-serif;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            direction: rtl;
        }
        .tempus-dominus-widget .date-container button {
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }
        .tempus-dominus-widget .date-container button:hover {
            background-color: #0056b3;
        }
        .tempus-dominus-widget .day.selected {
            background-color: #007bff !important;
            color: #fff !important;
        }
        .form-control.is-invalid + .input-group-text {
            border-color: #dc3545;
        }
        .input-group {
            direction: ltr; /* Ensure RTL for input group */
        }
        .force-english-digits {
    direction: ltr;
    unicode-bidi: plaintext;
}


    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary ">
                    <h4 class="mb-0">التحقق من الهوية - الخطوة الأولى</h4>
                    <small>معلومات شخصية</small>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('investor.kyc.step.one.submit') }}" dir="rtl">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <h6 for="name" class="form-label fw-bold">الاسم الكامل</h6>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name ?? '') }}"
                                   placeholder="أدخل اسمك الكامل (كما في الهوية الرسمية)"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth with Tempus Dominus -->
                        <div class="mb-3">
                            <h6 for="dob" class="form-label fw-bold">تاريخ الميلاد</h6>
                            <div class="input-group" id="datepicker" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                <span class="input-group-text" data-td-target="#datepicker" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" 
                                       class="form-control force-english-digits @error('date_of_birth') is-invalid @enderror"
                                       id="dob" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}"
                                       data-td-target="#datepicker"
                                       placeholder="اختر تاريخ الميلاد"
                                       required>
                            </div>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <h6 class="form-label fw-bold">الجنس</h6>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="gender" 
                                           id="male" 
                                           value="ذكر"
                                           @if(old('gender', $user->gender ?? '') === 'ذكر') checked @endif 
                                           required>
                                    <h6 class="form-check-label" for="male">ذكر</h6>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="gender" 
                                           id="female" 
                                           value="أنثى"
                                           @if(old('gender', $user->gender ?? '') === 'أنثى') checked @endif>
                                    <h6 class="form-check-label" for="female">أنثى</h6>
                                </div>
                            </div>
                            @error('gender')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <h6 for="country" class="form-label fw-bold">الجنسية</h6>
                            <select name="country" 
                                    id="country" 
                                    class="form-select @error('country') is-invalid @enderror" 
                                    required>
                                <option disabled selected>اختر الجنسية</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->nameAr }}"
                                            @if(old('country', $user->country ?? '') === $country->nameAr) selected @endif>
                                        {{ $country->nameAr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number (RTL) -->
                        <div class="mb-3">
                            <h6 for="phone" class="form-label fw-bold">رقم الهاتف</h6>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   placeholder="XXXXXXXXX966+ :مثال"
                                   pattern="\+?[0-9]{10,15}"
                                   dir="rtl"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <h6 for="email" class="form-label fw-bold">البريد الإلكتروني</h6>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email ?? '') }}"
                                   placeholder="example@domain.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <h6 for="address" class="form-label fw-bold">العنوان الكامل</h6>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="أدخل عنوان إقامتك الكامل (المدينة، الحي، الشارع)"
                                      required>{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div class="mb-3">
                            <h6 for="postal_code" class="form-label fw-bold">الرمز البريدي</h6>
                            <input type="text" 
                                   class="form-control @error('postal_code') is-invalid @enderror" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code', $user->postal_code ?? '') }}"
                                   placeholder="أدخل الرمز البريدي (مثال: 12345)"
                                   required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Occupation -->
                        <div class="mb-3">
                            <h6 for="occupation" class="form-label fw-bold">المهنة</h6>
                            <input type="text" 
                                   class="form-control @error('occupation') is-invalid @enderror" 
                                   id="occupation" 
                                   name="occupation" 
                                   value="{{ old('occupation', $user->occupation ?? '') }}"
                                   placeholder="أدخل مهنتك الحالية"
                                   required>
                            @error('occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Progress Indicator -->
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: 33%;" 
                                 aria-valuenow="33" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                الخطوة التالية
                                <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>
    <script>
        const picker = new tempusDominus.TempusDominus(document.getElementById('datepicker'), {
            display: {
                components: {
                    decades: true,
                    year: true,
                    month: true,
                    date: true,
                    hours: false,
                    minutes: false,
                    seconds: false
                },
                sideBySide: false,
                calendarWeeks: false,
                viewMode: 'years',
            },
            restrictions: {
                maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 18)), // 18 years ago
                minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 100)), // 100 years ago
            },
            localization: {
                locale: 'ar', // Arabic localization
                format: 'yyyy-MM-dd',
            },
            stepping: 1,
        });

        // Make input clickable to toggle picker
        document.getElementById('dob').addEventListener('click', () => {
            picker.toggle();
        });
    </script>
@endpush