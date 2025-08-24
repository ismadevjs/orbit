@extends('layouts.backend')

@can('browse media_files')
    @section('content')
        <div class="container">
            <h1>Media Library</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Back Button -->
            @if($currentDirectory)
                <a href="{{ route('uploaded_files.index') }}" class="btn btn-secondary mb-3">Back to Media Library</a>
            @endif

            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search files..."
                           value="{{ old('search', $search) }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <h2>Directories</h2>
            <div class="row mb-4">
                @foreach($directories as $directory)
                    <div class="col-md-3 m-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ basename($directory) }}</h5>
                                <a href="{{ route('uploaded_files.index', $directory) }}"
                                   class="btn btn-primary">Open</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h2>Files</h2>
            <div class="row">
                @forelse($files as $file)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ Storage::url($file) }}" class="card-img-top" alt="{{ basename($file) }}"
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ basename($file) }}</h5>
                                <form
                                    action="{{ route('uploaded_files.destroy', ['directory' => $currentDirectory, 'file' => basename($file)]) }}"
                                    method="POST">
                                    @csrf
                                    @can('delete media_files')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    @endcan
                                </form>


                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>No files found.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $files->links() }} <!-- Pagination links -->
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.2/dist/umd/popper.min.js"></script>
    @endpush
@endcan
