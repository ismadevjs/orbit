@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">Language Management</h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-globe me-2"></i>
                        Manage supported languages for your application
                    </p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">{{ count($languages) }} languages</span>
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

    <!-- Add Language Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Add New Language
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('languages.store') }}" method="POST" id="addLanguageForm">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="code" class="form-label fw-semibold">Language Code</label>
                                <input type="text"
                                       name="code"
                                       id="code"
                                       class="form-control form-control-lg"
                                       placeholder="e.g., en, fr, es"
                                       maxlength="5"
                                       pattern="[a-z]{2,5}"
                                       required>
                                <div class="form-text">2-5 lowercase letters (ISO 639-1)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Language Name</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-lg"
                                       placeholder="e.g., English, Français, Español"
                                       required>
                                <div class="form-text">Native language name preferred</div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-plus me-2"></i>Add Language
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages Grid -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Supported Languages
                </h5>
                <div class="d-flex gap-2">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="searchInput"
                           placeholder="Search languages..."
                           style="width: 200px;">
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($languages) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="languagesTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" style="width: 15%">
                                    <i class="fas fa-code me-2"></i>Code
                                </th>
                                <th scope="col" style="width: 35%">
                                    <i class="fas fa-language me-2"></i>Language Name
                                </th>
                                <th scope="col" style="width: 25%">
                                    <i class="fas fa-info-circle me-2"></i>Status
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($languages as $lang)
                                <tr class="language-row">
                                    <td>
                                        <span class="badge bg-secondary fs-6 font-monospace">
                                            {{ strtoupper($lang->code) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flag-icon me-3">
                                                <i class="fas fa-flag text-muted"></i>
                                            </div>
                                            <div>
                                                <strong class="text-dark">{{ $lang->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $lang->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Active
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('translations.index', $lang) }}"
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="Manage Translations">
                                                <i class="fas fa-language me-1"></i>
                                                Translations
                                            </a>

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
                        <i class="fas fa-globe text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">No Languages Found</h5>
                    <p class="text-muted">Start by adding your first language above.</p>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Tip:</strong> Common language codes include: en (English), fr (Français), es (Español), de (Deutsch)
                        </small>
                    </div>
                </div>
            @endif
        </div>
        @if(count($languages) > 0)
            <div class="card-footer bg-light text-muted">
                <div class="d-flex justify-content-between align-items-center">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        {{ count($languages) }} language(s) configured
                    </small>
                    <small class="text-end">
                        <i class="fas fa-lightbulb me-1"></i>
                        Click on "Translations" to manage language-specific content
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center">
                    Are you sure you want to delete the language <strong id="languageToDelete"></strong>?
                </p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This will also delete all associated translations and cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Language
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Language Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Language
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editCode" class="form-label fw-semibold">Language Code</label>
                        <input type="text"
                               class="form-control"
                               id="editCode"
                               name="code"
                               pattern="[a-z]{2,5}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="editName" class="form-label fw-semibold">Language Name</label>
                        <input type="text"
                               class="form-control"
                               id="editName"
                               name="name"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('languagesTable');

    if (searchInput && table) {
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const code = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();

                if (code.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('input', performSearch);
        document.getElementById('searchBtn').addEventListener('click', performSearch);

        // Clear search on escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                performSearch();
            }
        });
    }

    // Auto-focus and format code input
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.focus();
        codeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toLowerCase().replace(/[^a-z]/g, '');
        });
    }
});

// Delete confirmation
function confirmDelete(languageId, languageName) {
    document.getElementById('languageToDelete').textContent = languageName;
    document.getElementById('deleteForm').action = '/languages/' + languageId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Edit language
function editLanguage(languageId, code, name) {
    document.getElementById('editCode').value = code;
    document.getElementById('editName').value = name;
    document.getElementById('editForm').action = '/languages/' + languageId;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
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

.language-row td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
}

.flag-icon {
    width: 24px;
    text-align: center;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }

    .btn-group {
        flex-direction: column;
        gap: 5px;
    }

    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }

    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection
