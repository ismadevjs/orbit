<div class="px-4 py-2 mb-4">
    <a class="link-fx fw-bold" href="{{ url('/') }}">
        <i class="fa fa-fire"></i>
        <span class="fs-4 text-body-color">{{ $data['name'] }}</span>
    </a>
    <h1 class="h3 fw-bold mt-4 mb-2">{{ $data['message'] }}</h1>
    <h2 class="h5 fw-medium text-muted mb-0">{{ $data['call'] }}</h2>
</div>
