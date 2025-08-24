@extends('layouts.backend')

@section('content')
    <div class="container">
        <h1 class="m-4">Add new Project</h1>

        <div class="row">
            <form action="{{ route('projects.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{$project->id}}"  name="id">
                <div class="col-12">

                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-title">General Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="name" name="project_name"
                                        value="{{ $project->name }}" placeholder="Enter name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="{{ $project->price }}"placeholder="Enter price" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"
                                        required>{{ $project->description }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="images" class="form-label">Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple
                                        >
                                </div>



                                <div class="col-md-6">
                                    <label for="areas" class="form-label">Area</label>
                                    <select class="form-select" id="areas" name="area" required>
                                        <option value="" disabled selected>Select area</option>
                                        @foreach ($areas as $area)
                                            <option @if ($project->area == $area->id) selected @endif
                                                value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    @foreach ($project->images as $key => $image)
                                        <div class="col-md-2 my-4 d-flex justify-content-center">
                                            <img src="{{ asset('storage') . '/' . $project->images[$key] }}" height="120" width="120" alt="{{ $project->images[$key] }}">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <!-- Location Input and Button -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="location" class="form-label">Location / Project position</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="location"
                                            value="Lat: {{ $project->location['lat'] }}, Lng: {{ $project->location['lng'] }}"
                                            name="location" placeholder="Select location from the map" readonly required>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#mapModal">Select from Map</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Inputs to Store Latitude, Longitude, and Address -->
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <input type="hidden" id="address" name="address">

                            <!-- Map Modal -->
                            <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="mapModalLabel">Select Location on Map</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="map" style="height: 500px; width: 100%;"></div>
                                            <!-- Adjust map container -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="confirm-location">Confirm
                                                Location</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Facilities Section -->
                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-title">Facilities and Distances</h5>
                            <div id="facility-fields" class="row">
                              @foreach ($project->facilities as $key => $item)
                              <div class="col-md-6 mb-3">
                                <label for="facility" class="form-label">Select Facility</label>
                                <select class="form-select" id="facility" name="facilities[]" required>
                                    <option value="" disabled selected>Select a facility</option>
                                    @foreach ($facilities as $facility)
                                        <option @if ($project->facilities[$key] == $facility->id) selected @endif value="{{ htmlspecialchars($facility['id']) }}">
                                            {{ htmlspecialchars($facility['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="distance" class="form-label">Distance (km)</label>
                                <input type="number" class="form-control" id="distance"
                                     name="distances[]" value="{{$project->distances[$key]}}" placeholder="Enter distance"
                                    required>
                            </div>
                              @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="add-facility-field">Add
                                    Facility</button>
                                <button type="button" class="btn btn-danger" id="delete-facility-field">Delete Last
                                    Facility</button>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Fields Section -->
                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-title">Custom Fields</h5>
                            <div id="custom-fields" class="row">
                                @foreach ($project->custom_field_names as $key => $field)
                                    <div class="col-md-6 mb-3">
                                        <label for="customName1" class="form-label">Field Name</label>
                                        <input type="text" class="form-control" id="customName1"
                                            name="custom_field_names[]" value="{{ $project->custom_field_names[$key] }}"
                                            placeholder="Enter name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customValue1" class="form-label">Field Value</label>
                                        <input type="text" class="form-control" id="customValue1"
                                            name="custom_field_values[]"
                                            value="{{ $project->custom_field_values[$key] }}" placeholder="Enter value"
                                            required>
                                    </div>
                                @endforeach

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="add-custom-field">Add
                                    Field</button>
                                <button type="button" class="btn btn-danger" id="delete-custom-field">Delete Last
                                    Field</button>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Video Links -->
                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-title">Video Links</h5>
                            <div id="video-links" class="row">
                                @foreach ($project->video_links as $key => $video)
                                    <div class="col-md-12 mb-3">
                                        <label for="videoLink1" class="form-label">Video Link</label>
                                        <input type="url" class="form-control" id="videoLink1"
                                            value="{{ $project->video_links[$key] }}" name="video_links[]"
                                            placeholder="Enter video link" required>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="add-video-link">Add Video
                                    Link</button>
                                <button type="button" class="btn btn-danger" id="delete-video-link">Delete Last Video
                                    Link</button>
                            </div>
                        </div>
                    </div>

                    <!-- Speciality and Detailed Video -->
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialityVideo" class="form-label">Speciality video</label>
                                    <input type="text" class="form-control" id="specialityVideo"
                                        name="speciality_video" value="{{ $project->speciality_video }}"
                                        placeholder="Enter speciality video" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="detailedVideo" class="form-label">Detailed video</label>
                                    <input type="text" class="form-control" id="detailedVideo"
                                        value="{{ $project->detailed_video }}" name="detailed_video"
                                        placeholder="Enter detailed video" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Project Details -->
                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-title">Project Details</h5>
                            <div id="project-details" class="row">
                                @foreach ($project->project_types as $key => $type)
                                    <div class="col-md-4 mb-3">
                                        <label for="projectType1" class="form-label">Project Type</label>
                                        <input type="text" class="form-control" id="projectType1"
                                            value="{{ $project->project_types[$key] }}" name="project_types[]"
                                            placeholder="Enter project type" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="surface1" class="form-label">Surface (sqm)</label>
                                        <input type="number" class="form-control" id="surface1"
                                            value="{{ $project->surfaces[$key] }}" name="surfaces[]"
                                            placeholder="Enter surface" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="price1" class="form-label">Price ($)</label>
                                        <input type="number" class="form-control" id="price1"
                                            value="{{ $project->prices[$key] }}" name="prices[]"
                                            placeholder="Enter price" required>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="add-project-detail">Add Project
                                    Detail</button>
                                <button type="button" class="btn btn-danger" id="delete-project-detail">Delete Last
                                    Project Detail</button>
                            </div>
                        </div>
                    </div>


                </div>


                <button class="btn btn-primary w-100">Update</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add and Delete for Facilities
        document.getElementById('add-facility-field').addEventListener('click', function() {
            const facilityFields = document.getElementById('facility-fields');
            const fieldCount = facilityFields.children.length / 2 + 1;
            const newFacilityHtml = `
            <div class="col-md-6 mb-3">
                <label for="facility${fieldCount}" class="form-label">Select Facility</label>
                <select class="form-select" id="facility${fieldCount}" required>
                    <option value="" disabled selected>Select a facility</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ htmlspecialchars($facility['id']) }}">{{ htmlspecialchars($facility['name']) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="distance${fieldCount}" class="form-label">Distance (km)</label>
                <input type="number" class="form-control" id="distance${fieldCount}" placeholder="Enter distance" required>
            </div>
        `;
            facilityFields.insertAdjacentHTML('beforeend', newFacilityHtml);
        });

        document.getElementById('delete-facility-field').addEventListener('click', function() {
            const facilityFields = document.getElementById('facility-fields');
            if (facilityFields.children.length > 0) {
                facilityFields.removeChild(facilityFields.lastElementChild);
                facilityFields.removeChild(facilityFields.lastElementChild); // Remove in pairs
            }
        });

        // Add and Delete for Custom Fields
        document.getElementById('add-custom-field').addEventListener('click', function() {
            const customFields = document.getElementById('custom-fields');
            const fieldCount = customFields.children.length / 2 + 1;
            const newFieldHtml = `
            <div class="col-md-6 mb-3">
                <label for="customName${fieldCount}" class="form-label">Field Name</label>
                <input type="text" class="form-control" name="custom_field_names[]" id="customName${fieldCount}" placeholder="Enter name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="customValue${fieldCount}" class="form-label">Field Value</label>
                <input type="text" class="form-control" name="custom_field_values[]" id="customValue${fieldCount}" placeholder="Enter value" required>
            </div>
        `;
            customFields.insertAdjacentHTML('beforeend', newFieldHtml);
        });

        document.getElementById('delete-custom-field').addEventListener('click', function() {
            const customFields = document.getElementById('custom-fields');
            if (customFields.children.length > 0) {
                customFields.removeChild(customFields.lastElementChild);
                customFields.removeChild(customFields.lastElementChild); // Remove in pairs
            }
        });

        // Add and Delete for Video Links
        document.getElementById('add-video-link').addEventListener('click', function() {
            const videoLinks = document.getElementById('video-links');
            const videoCount = videoLinks.children.length + 1;
            const newVideoHtml = `
            <div class="col-md-12 mb-3">
                <label for="videoLink${videoCount}" class="form-label">Video Link</label>
                <input type="url" class="form-control" name="video_links[]" id="videoLink${videoCount}" placeholder="Enter video link" required>
            </div>
        `;
            videoLinks.insertAdjacentHTML('beforeend', newVideoHtml);
        });

        document.getElementById('delete-video-link').addEventListener('click', function() {
            const videoLinks = document.getElementById('video-links');
            if (videoLinks.children.length > 0) {
                videoLinks.removeChild(videoLinks.lastElementChild);
            }
        });

        // Add and Delete for Project Details
        document.getElementById('add-project-detail').addEventListener('click', function() {
            const projectDetails = document.getElementById('project-details');
            const detailCount = projectDetails.children.length / 3 + 1;
            const newDetailHtml = `
            <div class="col-md-4 mb-3">
                <label for="projectType${detailCount}" class="form-label">Project Type</label>
                <input type="text" class="form-control" id="projectType${detailCount}" name='project_types[]' placeholder="Enter project type" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="surface${detailCount}" class="form-label">Surface (sqm)</label>
                <input type="number" class="form-control" id="surface${detailCount}" name='surfaces[]' placeholder="Enter surface" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="price${detailCount}" class="form-label">Price ($)</label>
                <input type="number" class="form-control" id="price${detailCount}" name='prices[]'  placeholder="Enter price" required>
            </div>
        `;
            projectDetails.insertAdjacentHTML('beforeend', newDetailHtml);
        });

        document.getElementById('delete-project-detail').addEventListener('click', function() {
            const projectDetails = document.getElementById('project-details');
            if (projectDetails.children.length > 0) {
                projectDetails.removeChild(projectDetails.lastElementChild);
                projectDetails.removeChild(projectDetails.lastElementChild);
                projectDetails.removeChild(projectDetails.lastElementChild);
            }
        });
    </script>



    <!-- Leaflet (or Google Maps) Script -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <script>
        let map;
        let marker;

        // Initialize the map with a focus on the United Arab Emirates
        function initializeMap() {
            // Center on UAE
            let uaeLatLng = [23.4241, 53.8478];
            map = L.map('map').setView(uaeLatLng, 7); // Zoom level 7 to cover UAE

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Handle map click to select location
            map.on('click', function(e) {
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);

                // Set latitude and longitude
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;


            });
        }

        // Trigger map initialization when modal is shown
        $('#mapModal').on('shown.bs.modal', function() {
            if (!map) {
                initializeMap();
            }
            setTimeout(function() {
                map.invalidateSize(); // Fix map size issues inside the modal
            }, 200); // Small delay to ensure map resizes correctly
        });

        // Handle Confirm Location button click
        document.getElementById('confirm-location').addEventListener('click', function() {
            let lat = document.getElementById('latitude').value;
            let lng = document.getElementById('longitude').value;

            // Set location display (simple format with lat/lng, or reverse geocoding can be added here)
            let selectedAddress = `Lat: ${lat}, Lng: ${lng}`;
            document.getElementById('location').value = selectedAddress; // Display in location input
            document.getElementById('address').value = selectedAddress; // Hidden field for backend

            // Close the modal using Bootstrap's modal methods
            let mapModal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
            mapModal.hide();
        });

        // Ensure modal closes when 'Close' button or X icon is clicked
        document.querySelectorAll('#mapModal .btn-close, #mapModal .btn-secondary').forEach(button => {
            button.addEventListener('click', function() {
                let mapModal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
                if (mapModal) {
                    mapModal.hide();
                }
            });
        });
    </script>
@endpush
