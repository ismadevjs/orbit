@extends('layouts.backend')

@can('browse smtp')

    @section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">تعديل إعدادات SMTP</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('smtp.update') }}">
                    @csrf
                    <div class="row">
                        <!-- MAIL_MAILER -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_mailer" class="form-label">MAIL_MAILER</label>
                            <input type="text" class="form-control" id="mail_mailer" name="mail_mailer"
                                   value="{{ env('MAIL_MAILER', 'smtp') }}" required>
                        </div>

                        <!-- MAIL_HOST -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_host" class="form-label">MAIL_HOST</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host"
                                   value="{{ env('MAIL_HOST', 'mail.ibtassimclub.com') }}" required>
                        </div>

                        <!-- MAIL_PORT -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_port" class="form-label">MAIL_PORT</label>
                            <input type="number" class="form-control" id="mail_port" name="mail_port"
                                   value="{{ env('MAIL_PORT', 465) }}" required>
                        </div>

                        <!-- MAIL_USERNAME -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_username" class="form-label">MAIL_USERNAME</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username"
                                   value="{{ env('MAIL_USERNAME', 'contact@ibtassimclub.com') }}" required>
                        </div>

                        <!-- MAIL_PASSWORD -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_password" class="form-label">MAIL_PASSWORD</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password"
                                   value="{{ env('MAIL_PASSWORD', 'Ismail2024') }}" required>
                        </div>

                        <!-- MAIL_ENCRYPTION -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_encryption" class="form-label">MAIL_ENCRYPTION</label>
                            <input type="text" class="form-control" id="mail_encryption" name="mail_encryption"
                                   value="{{ env('MAIL_ENCRYPTION', 'tls') }}" required>
                        </div>

                        <!-- MAIL_FROM_ADDRESS -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_from_address" class="form-label">MAIL_FROM_ADDRESS</label>
                            <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                   value="{{ env('MAIL_FROM_ADDRESS', 'contact@ibtassimclub.com') }}" required>
                        </div>

                        <!-- MAIL_FROM_NAME -->
                        <div class="col-md-6 mb-3">
                            <label for="mail_from_name" class="form-label">MAIL_FROM_NAME</label>
                            <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                   value="{{ env('MAIL_FROM_NAME', 'It Worked') }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        @canany([['create smtp', 'edit smtp']])
                            <button type="submit" class="btn btn-primary btn-lg w-100">حفظ الإعدادات</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
@endcan
