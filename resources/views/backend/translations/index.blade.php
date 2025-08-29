@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">{{__('hero_heading')}}</h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-language me-2"></i>
                        Managing translations for <strong>{{ $language->name }}</strong>
                        <span class="badge bg-primary ms-2">{{ strtoupper($language->code) }}</span>
                    </p>
                </div>
                <div>
                    <span class="badge bg-info fs-6">{{ count($translations) }} translations</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Action Cards -->
    <div class="row mb-4">
        <!-- Add Translation Card -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Add New Translation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('translations.store', $language) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="key" class="form-label fw-semibold">Translation Key</label>
                                <input type="text"
                                       name="key"
                                       id="key"
                                       class="form-control form-control-lg"
                                       placeholder="e.g., welcome_message"
                                       required>
                                <div class="form-text">Use lowercase letters, numbers, and underscores only</div>
                            </div>
                            <div class="col-md-5">
                                <label for="value" class="form-label fw-semibold">Translation Value</label>
                                <input type="text"
                                       name="value"
                                       id="value"
                                       class="form-control form-control-lg"
                                       placeholder="Enter the translated text">
                                <div class="form-text">The actual text that will be displayed</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-save me-2"></i>Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sync Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sync-alt me-2"></i>Sync Translations
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="text-muted mb-3">Export all translations to the language file</p>
                    <form action="{{ route('translations.sync', $language) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-download me-2"></i>
                            Sync to /lang/{{ $language->code }}/app.php
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Translations Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Current Translations
                </h5>
                <div class="d-flex gap-2">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="searchInput"
                           placeholder="Search translations..."
                           style="width: 250px;">
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($translations) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="translationsTable">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th scope="col" style="width: 30%">
                                    <i class="fas fa-key me-2"></i>Translation Key
                                </th>
                                <th scope="col" style="width: 60%">
                                    <i class="fas fa-quote-left me-2"></i>Translation Value
                                </th>
                                <th scope="col" style="width: 10%" class="text-center">
                                    <i class="fas fa-cog me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($translations as $index => $t)
                                <tr class="translation-row" data-index="{{ $index }}">
                                    <td class="fw-medium text-primary">
                                        <code class="bg-light px-2 py-1 rounded">{{ $t->key }}</code>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $t->value ?: '—' }}</span>
                                        @if(empty($t->value))
                                            <small class="text-muted d-block">(No translation provided)</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Edit Translation">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Translation">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-language text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">No Translations Found</h5>
                    <p class="text-muted">Start by adding your first translation key above.</p>
                </div>
            @endif
        </div>
        @if(count($translations) > 0)
            <div class="card-footer bg-light text-muted">
                <small>
                    <i class="fas fa-info-circle me-2"></i>
                    Showing {{ count($translations) }} translation(s) for {{ $language->name }}
                </small>
            </div>
        @endif
    </div>
</div>

<!-- Search and Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const table = document.getElementById('translationsTable');

    if (searchInput && table) {
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const key = row.cells[0].textContent.toLowerCase();
                const value = row.cells[1].textContent.toLowerCase();

                if (key.includes(searchTerm) || value.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('input', performSearch);
        searchBtn.addEventListener('click', performSearch);

        // Clear search on escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                performSearch();
            }
        });
    }

    // Auto-focus on key input
    document.getElementById('key')?.focus();
});
</script>

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

code {
    font-size: 0.875em;
}

.translation-row td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.25rem;
}

.sticky-top {
    top: 0;
    z-index: 10;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }

    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .btn-group {
        flex-direction: column;
    }
}
</style>
@endsection
