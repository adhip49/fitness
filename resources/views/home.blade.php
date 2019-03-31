@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Manage Workouts</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <button type="button" class="btn btn-primary float-right" id="add_workouts_modal">
                            Add Workouts
                        </button>

                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Workout Type</th>
                                <th>Workout Date</th>
                                <th>Workout Time</th>
                                <th>Workout Speed (mi/hr)</th>
                                <th>Workout Location</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($workouts as $workout)
                                <tr>
                                    <td>{{ $workout->id }}</td>
                                    <td>{{ ucfirst($workout->type) }}</td>
                                    <td>{{ $workout->date }}</td>
                                    <td>{{ $workout->time }}</td>
                                    <td>{{ $workout->speed }}</td>
                                    <td>{{ ucfirst($workout->location) }}</td>
                                    <td>
                                        <p data-id="{{ $workout->id  }}" class="edit_workouts_modal">Edit</p>
                                        <a href="{{ url('workouts/remove/'.$workout->id) }}"
                                           onclick="return confirm('Are you sure you want to delete this workout?');">
                                            Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="add_workouts">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add a new workout</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ url('add/workouts') }}" id="add_workout_form" class="was-validated">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="add_type">Workout Type:</label>
                            <select class="form-control" id="add_type" name="workout_type"
                                    required>
                                <option value="">Select Workout Type</option>
                                <option value="run">Run</option>
                                <option value="walk">Walk</option>
                                <option value="elliptical">Elliptical</option>
                            </select>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <div class="form-group">
                            <label for="add_date">Workout Date:</label>
                            <input type="text" class="form-control" id="add_date" placeholder="Enter Workout Date"
                                   name="workout_date"
                                   required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <div class="form-group">
                            <label for="add_time">Workout Time:</label>
                            <input type="number" class="form-control" id="add_time"
                                   placeholder="Enter Workout Time (in minutes)"
                                   name="workout_time"
                                   required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <div class="form-group">
                            <label for="add_speed">Workout Speed:</label>
                            <input type="number" class="form-control" id="add_speed" placeholder="Enter Workout Speed"
                                   name="workout_speed"
                                   required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <div class="form-group">
                            <label for="add_location">Workout Location:</label>
                            <input type="text" class="form-control" id="add_location"
                                   placeholder="Enter Workout Location" name="workout_location"
                                   required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Workout</button>
                    </form>
                    <div class="m-auto alert-success alert status_show"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>

        $(document).ready(function () {
            $("#add_workouts_modal").click(function () {
                $('#add_workouts').modal('show');
                $('#add_workout_form').show();
                $('.status_show').hide();
                $('#add_workout_form').trigger("reset");
            });

            $(".edit_workouts_modal").click(function () {
                $('#add_workouts').modal('show');
                $('#add_workout_form').show();
                $('.status_show').hide();
                var id = $(this).data('id');
                $('#add_workout_form').append('<input type="hidden" id="edit_id" name="id" value="' + id + '">');
                $.ajax({
                    type: "POST",
                    url: '{{ url('workouts/fetch/') }}',
                    data: {id: id},
                    success: function (data) {
                        $('#add_type').val(data.type);
                        $('#add_date').val(data.date);
                        $('#add_time').val(data.time);
                        $('#add_speed').val(data.speed);
                        $('#add_location').val(data.location);
                    }
                });
            });
        });

        $.noConflict();

        jQuery(document).ready(function ($) {

            $('#example').DataTable();

            $('#add_date').datepicker({
                uiLibrary: 'bootstrap4'
            });


            var request;
            $("#add_workout_form").submit(function (event) {

                event.preventDefault();

                if (request) {
                    request.abort();
                }

                var $form = $(this);
                var $inputs = $form.find("input, select, button, textarea");
                var serializedData = $form.serialize();

                $inputs.prop("disabled", true);

                url = '{{ url('workouts/add') }}';
                // Fire off the request to /form.php
                request = $.ajax({
                    url: url,
                    type: "post",
                    data: serializedData
                });

                // Callback handler that will be called on success
                request.done(function (response, textStatus, jqXHR) {
                    console.log("Added");
                    $('#add_workout_form').hide();
                    $('.status_show').show().html(response.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);

                });

                // Callback handler that will be called on failure
                request.fail(function (jqXHR, textStatus, errorThrown) {
                    // Log the error to the console
                    console.error(
                        "The following error occurred: " +
                        textStatus, errorThrown
                    );
                });

                request.always(function () {
                    // Reenable the inputs
                    $inputs.prop("disabled", false);
                });
            });
        });
    </script>
@endpush
