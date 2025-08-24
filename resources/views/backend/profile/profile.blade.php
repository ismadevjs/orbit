@extends('layouts.backend')

@section('content')
    <div class="bg-image bg-image-bottom"
         style="background-image: url('https://picsum.photos/916/311');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; ">
        <div class="bg-black-75 py-4">
            <div class="content content-full text-center">
                <!-- الصورة الشخصية -->
                <div class="mb-3">
                    <a class="img-link" href="#">
                        <img class="img-avatar img-avatar96 img-avatar-thumb"
                             src="{{ $user->avatar ? asset('/storage/' . $user->avatar) : asset('assets/media/avatars/avatar15.jpg') }}"
                             alt="">
                    </a>
                </div>
                <!-- نهاية الصورة الشخصية -->

                <!-- البيانات الشخصية -->
                <h1 class="h3 text-white fw-bold mb-2">{{ $user->name }}</h1>
                <h2 class="h5 text-white-75">
                    @if ($user->getRoleNames()->isNotEmpty())
                        <h5 class="text-white">{{ $user->getRoleNames()->first() }}</h5>
                    @else
                        <h5 class="text-white"></h5>
                    @endif
                </h2>
                <!-- نهاية البيانات الشخصية -->

                <!-- الإجراءات -->
                <a href="{{ route('backend.index') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left opacity-50 me-1"></i> العودة إلى لوحة التحكم
                </a>
                <!-- نهاية الإجراءات -->
            </div>
        </div>
    </div>
    <!-- نهاية معلومات المستخدم -->

    <!-- المحتوى الرئيسي -->
    <div class="content">
        <!-- ملف المستخدم -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-user-circle me-1 text-muted"></i> ملف المستخدم
                </h3>
            </div>
            <div class="block-content">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                معلومات حسابك الأساسية.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">

                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-name">الاسم</label>
                                <input type="text" class="form-control form-control-lg" id="profile-settings-name"
                                       name="name" placeholder="أدخل اسمك.."
                                       value="{{ old('profile-settings-name', $user->name) }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-email">البريد الإلكتروني</label>
                                <input type="email" class="form-control form-control-lg" id="profile-settings-email"
                                       name="email" placeholder="أدخل بريدك الإلكتروني.."
                                       value="{{ old('profile-settings-email', $user->email) }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-phone">الهاتف</label>
                                <input type="text" class="form-control form-control-lg" id="profile-settings-email"
                                       name="phone" placeholder="أدخل رقم هاتفك.."
                                       value="{{ old('profile-settings-phone', $user->phone) }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-phone">واتساب</label>
                                <input type="text" class="form-control form-control-lg" id="profile-settings-email"
                                       name="whatsapp" placeholder="أدخل رقم هاتفك.."
                                       value="{{ old('profile-settings-whatsapp', $user->whatsapp) }}">
                            </div>


                            <div class="row mb-4">
                                <div class="col-md-10 col-xl-6">
                                    <div class="push">
                                        <img class="img-avatar"
                                             src="{{ $user->avatar ? asset('/storage/' . $user->avatar) : asset('assets/media/avatars/avatar15.jpg') }}"
                                             alt="">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="profile-settings-avatar">اختر صورة جديدة</label>
                                        <input class="form-control" type="file" id="profile-settings-avatar"
                                               name="avatar">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="about" class="form-label">نبذة عن الشخص</label>
                                <textarea class="form-control" id="about" name="about"   rows="4">{{$user->about ?? ''}}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea class="form-control" id="address" name="address"   rows="4">{{$user->address ?? ''}}</textarea>
                            </div>



                            <div class="mb-3">
                                <label for="gender" class="form-label">الجنس</label>
                                <select name="gender" class="form-select" id="gender">
                                    <option value="" disabled selected>-</option>
                                    <option value="ذكر" @if($user->gender === 'ذكر') selected @endif>ذكر</option>
                                    <option value="أنثى" @if($user->gender === 'أنثى') selected @endif>أنثى</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="date_of_birth" value="{{$user->date_of_birth ?? ''}}" name="date_of_birth" >
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="country" class="form-label">الدولة</label>
                                    <select name="country" id="country" class="form-select">
                                        <option disabled selected>-</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->nameAr || $country->nameEn }}" @if($country->nameAr == $user->country || $country->nameEn == $user->country) selected @endif>{{ $country->nameAr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-primary">تحديث</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- نهاية ملف المستخدم -->

        <!-- تغيير كلمة المرور -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-asterisk me-1 text-muted"></i> تغيير كلمة المرور
                </h3>
            </div>
            <div class="block-content">
                <form action="{{ route('profile.changePassword') }}" method="POST">
                    @csrf
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                تغيير كلمة المرور الخاصة بك هي طريقة سهلة للحفاظ على أمان حسابك.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-password">كلمة المرور الحالية</label>
                                <input type="password" class="form-control form-control-lg"
                                       id="profile-settings-password"
                                       name="password">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-password-new">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control form-control-lg"
                                       id="profile-settings-password-new" name="password-new">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="profile-settings-password-new-confirm">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control form-control-lg"
                                       id="profile-settings-password-new-confirm" name="password-new_confirmation">
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-primary">تحديث</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- نهاية تغيير كلمة المرور -->
    </div>
@endsection
