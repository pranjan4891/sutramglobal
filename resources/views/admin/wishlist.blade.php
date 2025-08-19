@extends('admin.layout.layout', ['pageTitle' => $title])

@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Image</th>
                                    <th>User Info</th>
                                    <th>Product Details</th>
                                    <th>Created At</th>
                                    <th>Notify</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Product Image</th>
                                    <th>User Info</th>
                                    <th>Product Details</th>
                                    <th>Created At</th>
                                    <th>Notify</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('sub-script')
    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize DataTable
            ssakDataTable('table-data', "{{ route('admin.wishlists.getWishlists') }}", true, false);

        });
        $(document).ready(function () {
            // Display Toastr success message
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            // Display Toastr error message
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
@endpush

