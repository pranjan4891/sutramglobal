<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | {{ $pageTitle }}</title>
    <link rel="icon" type="image/png" href="{{asset('public/img/FaviconIcon.jpg')}}">


    @include('admin.layout.path_css')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
    <style>
        .brand-link .brand-image {
            max-height: 50px;
            width: 90%;
        }
        .layout-navbar-fixed.layout-fixed .wrapper .sidebar {
            margin-top: calc(3.5rem + 15px);
        }
    </style>
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('public/img/FaviconIcon.jpg') }}" alt="AdminLTELogo"
                height="40" width="40">
        </div>

        <!-- Navbar -->
        @include('admin.layout.top_nav')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.layout.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $title }}</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">

                @yield('contant')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="#">Admin</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3 control-sidebar-content" style="">
                <h5>Admin</h5>
                <hr class="mb-2">
                <div class="nav nav-sidebar flex-column mb-4">
                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>My Profile</p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </div>
            </div>
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    @include('admin.layout.path_js')


    <script type="text/javascript">
		$(document).ready(function() {

            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            @if (Session::has('warning'))
                toastr.warning('{{ Session::get('warning') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif

			$('form input, form textarea').on('input', function() {
				if ($(this).hasClass('is-invalid')) {
					$(this).removeClass('is-invalid');
					$(this).siblings('.invalid-feedback').hide();
				}
			});

		});

		function clearError(form) {
			form.find('.is-invalid').removeClass('is-invalid');
			form.find('.invalid-feedback').remove();
		}
	</script>
    <script type="text/javascript">
        function ssakDataTable(table_id,url=null,custom_filter=false,export_button=false)
        {
            $('#'+table_id).DataTable().destroy();
            if(url!=null)
            {
                var filter={};
                filter['_token']='{{csrf_token()}}';

                if(custom_filter)
                {
                    $('.data-filter').each(function(index) {
                        if($(this).val().trim().length>0)
                        {
                            var key=$(this).attr('name');
                            var val=$(this).val();
                            filter[key]=val;
                        }
                    });
                }
                console.log(filter);
                var table = $('#'+table_id).DataTable({
                    lengthMenu: [10, 25, 50, 100, 200, 500],
                    processing: true,
                    serverSide: true,
                    ajax:{url:url,
                        type:'POST',
                        data:filter,
                      },
                    fnRowCallback: function( nRow, aData, iDisplayIndex ) {
                      // $('td:eq(1)', nRow).css('width','10%');

                    }
                });
            }else{
                var table = $('#'+table_id).DataTable();
            }

            if(export_button)
            {
                var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [
                        { extend: 'copy' },
                        { extend: 'excel' },
                        { extend: 'csv' },
                        { extend: 'pdf' },
                        { extend: 'print' }
                        ]
                }).container().appendTo($('#dataTableButtons'));
           }
        }

        function get_subcategory(e) {
            var category_id = $(e).val();
            $.ajax({
                url: '{{ route('admin.get_subcategory') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    'category_id': category_id
                },
                success: function(data) {
                    console.log(data);
                    $('#subcategory_id').html(data);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }
    </script>
@stack('sub-script')
</body>

</html>
