<x-app-layout>
  @section('more-styles')
      <!--Data Tables -->
      <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
  @endsection
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">Sex Offenders</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <!-- <li class="breadcrumb-item active" aria-current="page">DataTable</li> -->
            </ol>
          </nav>
        </div>
        <!-- <div class="ml-auto">
          <div class="btn-group">
            <button type="button" class="btn btn-primary">Actions</button>
            <button type="button" class="btn btn-primary bg-split-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">	<span class="sr-only"> Actions</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">	<a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">New Admin</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div> -->
      </div>
      <!--end breadcrumb-->
      <div class="card">
        <div class="card-body">

          @include('components.flash-message')

          <!-- <div class="card-title">
            <h4 class="mb-0">DataTable Example</h4>
          </div> -->
          <!-- <hr/> -->
          <div  class="mb-4">
            <a href="{{ route('offenders.new') }}" class="btn btn-primary"> + Add a New Sex Offender </a>
          </div>
          <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>SN</th>
                  <th>Offender Name</th>
                  <th>Title</th>
                  <th>Offence</th>
                  <th>Added By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $sn = 1; @endphp
                @foreach($offenders as $offender)
                  @php
                  $admin = \App\Models\WebModels\Admin::where('id',$offender->admin_id)->first();
                  @endphp
                  <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ $offender->offender_name }}</td>
                    <td>{{ $offender->title }}</td>
                    <td>{{ $offender->offence }}</td>
                    <td>{{ $admin->firstname.' '.$admin->lastname }}</td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('offenders.view', ['offender' => $offender->id]) }}">View</a>
                            <a class="dropdown-item" href="{{ route('offenders.edit', ['offender' => $offender->id]) }}">Edit</a>
                            <a class="dropdown-item text-danger" href="{{ route('offenders.delete', ['offender' => $offender->id]) }}">Delete</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @php $sn++; @endphp
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

  @section('more-scripts')
    <!--Data Tables js-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script>
      $(document).ready(function () {
        //Default data table
        $('#example').DataTable();
        var table = $('#example2').DataTable({
          lengthChange: false,
          buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });
        table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
      });
    </script>
  @endsection
</x-app-layout>
