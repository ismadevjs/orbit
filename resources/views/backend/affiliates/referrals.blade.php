@extends('layouts.backend')

@section('content')
    <div class="container py-4">

        <!-- قسم العنوان -->
        <div class="mb-4 text-center">
            <h1 class="display-6">إحالاتي</h1>
            <p class="text-muted">هنا تجد المستخدمين الذين قمت بإحالتهم.</p>
        </div>

        <!-- الإحالات كبطاقات -->
        <div class="row">
            @forelse ($referrals as $referral)
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="block block-rounded block-transparent text-center
                    @if($referral->active) bg-success @else bg-gd-sun @endif shadow-lg transform hover:scale-105 transition-all duration-300">

                        <!-- رابط تفاصيل الإحالة -->
                        <a href="{{ route('investors.details.id', ['id' => $referral->id]) }}">
                            <div class="block-content block-content-full py-3">
                                <!-- صورة الملف الشخصي -->
                                <img class="img-avatar img-avatar-thumb rounded-circle border-4 border-light"
                                     src="{{ asset($referral->avatar ? 'storage/' . $referral->avatar : 'assets/img/team/user.png') }}"
                                     alt="{{ $referral->name }}"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            </div>

                            <!-- الاسم والبريد الإلكتروني -->
                            <div class="block-content block-content-full block-content-sm bg-black-50 rounded-bottom">
                                <div class="fw-semibold text-white mb-1" style="font-size: 1.1rem;">{{ $referral->name }}</div>
                                <div class="fs-sm text-white-75">{{ $referral->email }}</div>
                            </div>
                        </a>


                    </div>
                </div>
            @empty
                <!-- حالة عدم وجود إحالات -->
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <p>لا توجد إحالات حتى الآن.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
@endsection
