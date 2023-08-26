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
        <div class="breadcrumb-title pr-3">Harm Spots</div>
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
            <a href="{{ route('harmspot.new') }}" class="btn btn-primary"> + New Harmspot </a>
          </div>
          <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>SN</th>
                  <th>Title</th>
                  <th>Content</th>
                  <th>Risk Level</th>
                  <th>Status</th>
                  <th>Created By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $sn = 1; @endphp
                @foreach($harmspots as $harmspot)
                  @php
                  $admin = \App\Models\WebModels\Admin::where('id',$harmspot->admin_id)->first();
                  @endphp
                  <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ $harmspot->title }}</td>
                    <td>{{ $harmspot->content }}</td>
                    <td>
                      @if($harmspot->risk_level == \App\Models\HarmSpot::MODERATE)
                        Moderate
                      @elseif($harmspot->risk_level == \App\Models\HarmSpot::SEVERE)
                        Severe
                      @elseif($harmspot->risk_level == \App\Models\HarmSpot::CRITICAL)
                        Critical
                      @elseif($harmspot->risk_level == \App\Models\HarmSpot::FALSE_ALARM)
                        False Alarm
                      @else
                        Unknown Level
                      @endif
                    </td>
                    <td>
                      @if($harmspot->status == \App\Models\HarmSpot::PUBLISHED)
                        <a href="javascript:;" class="btn btn-sm btn-light-success btn-block radius-30">Published</a>
                      @else
                        <a href="javascript:;" class="btn btn-sm btn-light-danger btn-block radius-30">Unpublished</a>
                      @endif
                    </td>
                    <td>{{ $admin->firstname.' '.$admin->lastname }}</td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('harmspot.view', ['harmspot' => $harmspot->id]) }}">View</a>
                            <a   class="dropdown-item" href="{{ route('harmspot.show', ['harmspot' => $harmspot->id]) }}">Edit</a>
                            @if($harmspot->status == \App\Models\Post::PUBLISHED)
                                <a style="display:none;" class="dropdown-item" href="{{ route('harmspot.unpublish', ['harmspot' => $harmspot->id]) }}">Unpublish</a>
                            @elseif($harmspot->status == \App\Models\Post::HIDDEN)
                                <a class="dropdown-item" href="{{ route('harmspot.publish', ['harmspot' => $harmspot->id]) }}">Publish</a>
                            @endif
                            <a class="dropdown-item text-danger" href="{{ route('harmspot.delete', ['harmspot' => $harmspot->id]) }}">Delete</a>
                        </div>
                      </div>
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
