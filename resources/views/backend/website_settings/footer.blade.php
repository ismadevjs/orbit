@extends('layouts.backend')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Footer Management</h1>

        <div class="row">
            <!-- Footer Info Widget -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Footer Info</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.info.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="footer_title">Title</label>
                                <input type="text" id="footer_title" name="footer_title" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="footer_description">Description</label>
                                <textarea id="footer_description" name="footer_description"
                                          class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Footer Info</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- About Widget -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">About Widget</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.about.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="footer_logo">Footer Logo</label>
                                <input type="file" id="footer_logo" name="footer_logo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="about_description">About Description</label>
                                <textarea id="about_description" name="about_description"
                                          class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="play_store_link">Play Store Link</label>
                                <input type="url" id="play_store_link" name="play_store_link" class="form-control"
                                       value="">
                            </div>
                            <div class="form-group">
                                <label for="app_store_link">App Store Link</label>
                                <input type="url" id="app_store_link" name="app_store_link" class="form-control"
                                       value="">
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update About Widget</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info Widget -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Contact Info</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.contact.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="contact_address">Address</label>
                                <input type="text" id="contact_address" name="contact_address" class="form-control"
                                       value="">
                            </div>
                            <div class="form-group">
                                <label for="contact_phone">Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" class="form-control"
                                       value="">
                            </div>
                            <div class="form-group">
                                <label for="contact_email">Email</label>
                                <input type="email" id="contact_email" name="contact_email" class="form-control"
                                       value="">
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Contact Info</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Link Widget -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Link Widget</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.links.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="link_widget_title">Title</label>
                                <input type="text" id="link_widget_title" name="link_widget_title" class="form-control"
                                       value="">
                            </div>
                            <div id="links-container">
                                <div class="form-group link-item">
                                    <input type="url" name="links[]" class="form-control my-2" placeholder="Link URL"
                                           value="">
                                    <button type="button" class="btn btn-danger mt-2 remove-link">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="add-link">Add Link</button>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Links</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Footer Bottom</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.copyright.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="copyright_text">Copyright Text</label>
                                <input type="text" id="copyright_text" name="copyright_text" class="form-control"
                                       value="">
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Copyright</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Social Links Widget -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Social Links</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.social.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="social_link">Social Link</label>
                                <input type="url" id="social_link" name="social_link" class="form-control"
                                       placeholder="Enter social link">
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Social Links</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Widget -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Payment Methods</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('footer.payment.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="payment_image">Upload Payment Methods Image</label>
                                <input type="file" id="payment_image" name="payment_image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success mt-2 btn-block">Update Payment Methods</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('add-link').addEventListener('click', function () {
            const container = document.getElementById('links-container');
            const linkItem = document.createElement('div');
            linkItem.className = 'form-group link-item';
            linkItem.innerHTML = `
                <input type="url" name="links[]" class="form-control my-2" placeholder="Link URL">
                <button type="button" class="btn btn-danger my-2 remove-link">Remove</button>
            `;
            container.appendChild(linkItem);
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-link')) {
                event.target.closest('.link-item').remove();
            }
        });
    </script>
@endpush
