@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-lg-10 col-xxl-8 mx-auto">
        <div class="card my-2">
            <div class="card-header">
                <h3 class="h6 mb-0">معلومات الخادم</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped aiz-table">
                    <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الإصدار الحالي</th>
                        <th>الإصدار المطلوب</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>إصدار PHP</td>
                        <td>{{ phpversion() }}</td>
                        <td>8.1</td>
                        <td>
                            @if (floatval(phpversion()) >= 8.1)
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>MySQL</td>
                        <td>
                            @php
                                $results = \DB::select("SELECT version() AS version");
                                $mysql_version = $results[0]->version;
                                $version_explode = explode("-", $mysql_version);
                                $mysql_required_version = '8.0';
                                if (isset($version_explode[1]) && $version_explode[1] == 'MariaDB') {
                                    $mysql_required_version = '10.3';
                                }
                            @endphp
                            {{ $mysql_version }}
                        </td>
                        <td>{{ $mysql_required_version }}+</td>
                        <td>
                            @if (floatval($version_explode[0]) >= floatval($mysql_required_version))
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card my-2">
            <div class="card-header">
                <h3 class="h6 mb-0">إعدادات php.ini</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped aiz-table">
                    <thead>
                    <tr>
                        <th>اسم الإعداد</th>
                        <th>القيمة الحالية</th>
                        <th>القيمة الموصى بها</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>file_uploads</td>
                        <td>{{ ini_get('file_uploads') == 1 ? 'تشغيل' : 'إيقاف' }}</td>
                        <td>تشغيل</td>
                        <td>
                            @if (ini_get('file_uploads') == 1)
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>max_file_uploads</td>
                        <td>{{ ini_get('max_file_uploads') }}</td>
                        <td>20+</td>
                        <td>
                            @if (ini_get('max_file_uploads') >= 20)
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <!-- Add remaining settings here with translation -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card my-2">
            <div class="card-header">
                <h3 class="h6 mb-0">معلومات الإضافات</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>اسم الإضافة</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $loaded_extensions = get_loaded_extensions();
                        $required_extensions = ['bcmath', 'ctype', 'json', 'mbstring', 'zip', 'zlib', 'openssl', 'tokenizer', 'xml', 'dom',  'curl', 'fileinfo', 'gd', 'pdo_mysql'];
                    @endphp
                    @foreach ($required_extensions as $extension)
                        <tr>
                            <td>{{ $extension }}</td>
                            <td>
                                @if(in_array($extension, $loaded_extensions))
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
