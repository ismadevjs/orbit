@extends('layouts.backend')

@section('content')

    <div class="container mt-4">
        <div class="text-left mb-4">
            <h1 class="h3">Website Header</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Header Setting</h6>
            </div>

            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Website Name -->
                    <div class="mb-4">
                        <label class="form-label">Website Name</label>
                        <input type="text" class="form-control" placeholder="Website Name" name="website_name" value="">
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" placeholder="Description" name="description"></textarea>
                    </div>
                    <!-- Logo -->
                    <div class="mb-4">
                        <label class="form-label">Logo</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="logo" accept="image/*">

                        </div>
                        <small class="form-text text-muted">Recommended dimensions: 244px width X 40px height.</small>
                    </div>

                    <!-- Logo (White) -->
                    <div class="mb-4">
                        <label class="form-label">Header Logo (White)</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="header_logo" accept="image/*">

                        </div>
                        <small class="form-text text-muted">Minimum dimensions required: 244px width X 40px
                            height.</small>
                    </div>

                    <!-- Favicon -->
                    <div class="mb-4">
                        <label class="form-label">Favicon</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="favicon" accept="image/*">

                        </div>
                        <small class="form-text text-muted">Recommended dimensions: 16px X 16px or 32px X 32px.</small>
                    </div>


                    <!-- Help Line Number -->
                    <div class="mb-4">
                        <label class="form-label">Help Line Number</label>
                        <input type="text" class="form-control" placeholder="Help Line Number" name="helpline_number"
                               value="">
                    </div>

                    <!-- Contact Email -->
                    <div class="mb-4">
                        <label class="form-label">Contact Email</label>
                        <input type="email" class="form-control" placeholder="Contact Email" name="contact_email"
                               value="">
                    </div>

                    <!-- Update Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
