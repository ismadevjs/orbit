@extends('layouts.backend')

@can('browse settings')
    @section('content')
        <div class="content">

            <div class="bg-image bg-image-bottom"
                 style="background-image: url('https://picsum.photos/916/311');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; ">
                <div class="bg-black-75 py-4">
                    <div class="content content-full text-center">


                        <!-- Personal -->
                        <h1 class="h2 text-white fw-bold mb-2">{{ getSettingValue('site_name') }}</h1>
                        <h4 class="text-white fw-bold mb-2">{{getSettingValue('site_description')}}</h4>
                        <!-- END Personal -->

                        <!-- Actions -->
                        <a href="{{ route('backend.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left opacity-50 me-1"></i> العودة إلى لوحة التحكم
                        </a>
                        <!-- END Actions -->
                    </div>
                </div>
            </div>
            <!-- END User Info -->


            <div class="row mt-6">
                <div class="col-lg-12">

                    <!-- Site Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الموقع</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update.site_info') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="site_name" class="form-label">اسم الموقع</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name"
                                           value="{{ $settings->site_name ?? '' }}">
                                </div>
                                <div class="mb-4">
                                    <label for="site_description" class="form-label">وصف الموقع</label>
                                    <textarea class="form-control" id="site_description" name="site_description"
                                              rows="3">{{ $settings->site_description ?? '' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="site_keywords" class="form-label">كلمات الموقع المفتاحية</label>
                                    <textarea class="form-control" id="site_keywords" name="site_keywords"
                                              rows="3">{{ $settings->site_keywords ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث معلومات الموقع</button>
                            </form>
                        </div>
                    </div>

                    <!-- Logo Management Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">إدارة الشعار</h5>
                        </div>
                        <div class="card-body">
                            <!-- Logo -->
                            <form action="{{ route('settings.update.logo') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="logo" class="form-label">الشعار</label>
                                    <input type="file" class="form-control" id="logo" name="logo">
                                    @if ($settings && $settings->logo)
                                        <div class="mt-3">
                                            <img src="{{ asset('/storage/' . $settings->logo) }}" class="img-thumbnail"
                                                 width="120">
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث الشعار</button>
                            </form>
                            <hr>
                            <!-- White Logo -->
                            <form action="{{ route('settings.update.logo_white') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="logo_white" class="form-label">الشعار الأبيض</label>
                                    <input type="file" class="form-control" id="logo_white" name="logo_white">
                                    @if ($settings && $settings->logo_white)
                                        <div class="mt-3">
                                            <img src="{{ asset('/storage/' . $settings->logo_white) }}"
                                                 class="img-thumbnail"
                                                 width="120">
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث الشعار الأبيض</button>
                            </form>
                            <hr>
                            <!-- Favicon -->
                            <form action="{{ route('settings.update.favicon') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="favicon" class="form-label">الأيقونة المفضلة</label>
                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                    @if ($settings && $settings->favicon)
                                        <div class="mt-3">
                                            <img src="{{ asset('/storage/' . $settings->favicon) }}" class="img-thumbnail"
                                                 width="32">
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث الأيقونة المفضلة</button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الاتصال</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update.contact_info') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="site_email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email"
                                           value="{{ $settings->site_email ?? '' }}">
                                </div>
                                <div class="mb-4">
                                    <label for="site_phone" class="form-label">الهاتف</label>
                                    <input type="text" class="form-control" id="site_phone" name="site_phone"
                                           value="{{ $settings->site_phone ?? '' }}">
                                </div>
                                <div class="mb-4">
                                    <label for="whatsapp" class="form-label">واتساب</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                                           value="{{ $settings->whatsapp ?? '' }}">
                                </div>
                                <div class="mb-4">
                                    <label for="whatsapp_message" class="form-label">رسالة واتساب</label>
                                    <textarea type="text" class="form-control" id="whatsapp_message" name="whatsapp_message"
                                    >{{ $settings->whatsapp_message ?? '' }}</textarea>
                                </div>


                                <div class="mb-4">
                                    <label for="site_address" class="form-label">العنوان</label>
                                    <textarea class="form-control" id="site_address" name="site_address"
                                              rows="2">{{ $settings->site_address ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث معلومات الاتصال</button>
                            </form>
                        </div>
                    </div>

                    <!-- map Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">الخريطة :</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update.map') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="long" class="form-label">خط الطول</label>
                                    <input class="form-control" id="long" name="long"
                                           value="{{ $settings->long ?? '' }}"/>
                                </div>
                                <div class="mb-4">
                                    <label for="lat" class="form-label">خط العرض</label>
                                    <input class="form-control" id="lat" name="lat"
                                           value="{{ $settings->lat ?? '' }}"/>
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث الخريطة</button>
                            </form>
                        </div>
                    </div>


                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">التطبيقات</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update.apps') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="play_store" class="form-label">متجر بلاي</label>
                                    <input type="text" class="form-control" id="play_store" name="play_store" value="{{ $settings->play_store ?? '' }}">

                                </div>
                                <div class="mb-4">
                                    <label for="app_store" class="form-label">متجر التطبيقات</label>
                                    <input type="text" class="form-control" id="app_store" name="app_store" value="{{ $settings->app_store ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary">تحديث التطبيقات</button>
                            </form>
                        </div>
                    </div>


                    <!-- Footer Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">الفوتر</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update.footer') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="footer_text" class="form-label">النص في الفوتر</label>
                                    <textarea type="text" class="form-control" id="footer_text" name="footer_text" rows="3">{{ $settings->footer_text ?? '' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="footer_links" class="form-label">روابط الفوتر</label>
                                    <textarea type="text" class="form-control" id="footer_links" name="footer_links" rows="3">{{ $settings->footer_links ?? '' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="footer_big" class="form-label">نص الفوتر الكبير</label>
                                    <textarea type="text" class="form-control" id="footer_big" name="footer_big" rows="3">{{ $settings->footer_big ?? '' }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">تحديث الفوتر</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endcan
