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
        <div class="breadcrumb-title pr-3">Edit Harmspot</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('harmspot.index')}}">Harmspots</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Harmspot</li>
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
            <h4 class="mb-0">Form text editor</h4>
          </div>
          <hr/> -->
          <form method="post" action="{{ route('harmspot.update', ['harmspot' => $harmspot->id]) }}">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Title</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="title" required value="{{ @old('title') ? @old('title') : $harmspot->title }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Latitude</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-map'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="latitude" required value="{{ @old('latitude') ? @old('latitude') : $harmspot->latitude }}">
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Longitude</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-map'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="longitude" required value="{{ @old('longitude') ? @old('longitude') : $harmspot->longitude }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Content</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <textarea class="form-control border-left-0" placeholder="" name="content" required>{{ @old('content') ? @old('content') : $harmspot->content }}</textarea>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Risk Level</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <select class="form-control border-left-0" name="risk_level" required>
                          <option value={{ \App\Models\HarmSpot::MODERATE }}
                            @if($harmspot->risk_level == \App\Models\HarmSpot::MODERATE)
                              {{ 'selected' }}
                            @endif
                          >Moderate</option>
                          <option value={{ \App\Models\HarmSpot::SEVERE }}
                            @if($harmspot->risk_level == \App\Models\HarmSpot::SEVERE)
                              {{ 'selected' }}
                            @endif
                          >Severe</option>
                          <option value={{ \App\Models\HarmSpot::CRITICAL }}
                            @if($harmspot->risk_level == \App\Models\HarmSpot::CRITICAL)
                              {{ 'selected' }}
                            @endif
                          >Critical</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Status</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <select class="form-control border-left-0" name="status" required>
                          <option value={{ \App\Models\HarmSpot::PUBLISHED }}
                          @if($harmspot->status == \App\Models\HarmSpot::PUBLISHED)
                            {{ 'selected' }}
                          @endif
                          >Published</option>
                          <option style="display:none;" value={{ \App\Models\HarmSpot::UNPUBLISHED }}
                          @if($harmspot->status == \App\Models\HarmSpot::UNPUBLISHED)
                            {{ 'selected' }}
                          @endif
                          >Unpublished</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary px-3 mt-4">Update Harmspot</button>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <!-- <iframe
                  width="600"
                  height="450"
                  frameborder="0" style="border:0"
                  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyB4fp1ty4igm7ftTi6mk-ET6S_WNW_xiHU
                    &q=Space+Needle,Seattle+WA" allowfullscreen>
                </iframe> -->
                <iframe src="https://www.google.com/maps/embed" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
              </div>
            </div>


          </form>
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
    <script src="{{ asset('assets/js/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
  		tinymce.init({
  		  selector: '#mytextarea'
  		});
  	</script>
  @endsection
</x-app-layout>
