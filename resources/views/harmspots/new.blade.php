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
        <div class="breadcrumb-title pr-3">New Harmspot</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('harmspot.index')}}">Harmspots</a></li>
              <li class="breadcrumb-item active" aria-current="page">New Harmspot</li>
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
          <form method="post" action="{{ route('harmspot.create') }}">
            @csrf
            <div class="row">
              <div class="col-md-12">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="country">Country</label>
                  <div class="input-group">
                    <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                    </div>
                    <select class="form-control" name="country" required id="country" onchange="getCountryName(event)">
                        <option value="">-- select country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <input type="hidden" id="countryName" />
                </div>
              </div>

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Location</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="location" required value="{{ @old('location') }}" onblur="findLocation(event)" id="location">
                    </div>
                  </div>
                </div>

                <div class="form-row" style="display:none;" id="search-result">

                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Latitude</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-map'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="latitude" required value="{{ @old('latitude') }}" id="latitude" readonly>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Longitude</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-map'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="longitude" required value="{{ @old('longitude') }}" id="longitude" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Title</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <input type="text" class="form-control border-left-0" placeholder="" name="title" required value="{{ @old('title') }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Content</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <textarea class="form-control border-left-0" placeholder="" name="content" required>{{ @old('content') }}</textarea>
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
                          <option value={{ \App\Models\HarmSpot::MODERATE }}>Moderate</option>
                          <option value={{ \App\Models\HarmSpot::SEVERE }}>Severe</option>
                          <option value={{ \App\Models\HarmSpot::CRITICAL }}>Critical</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Status</label>
                    <div class="input-group">
                      <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                      </div>
                      <select class="form-control border-left-0" name="status" required>
                          <option value={{ \App\Models\HarmSpot::PUBLISHED }}>Published</option>
                       <!--   <option value={{ \App\Models\HarmSpot::UNPUBLISHED }}>Unpublished</option>-->
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary px-3 mt-4">Create Harmspot</button>
                  </div>
                </div>
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

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script src="https://apis.google.com/js/api.js" type="text/javascript"></script>
    <script type="text/javascript">
      gapi.load('auth2', function() {
        // Library loaded.
      });
    </script>

    <script>

        function getCountryName(e){
            let countryId = e.target.value;
            let countryNameElem = document.getElementById('countryName');
            axios.get('{{$baseUrl}}/get-country-name/'+countryId, {
            }).then((response) => {
                // console.log(response.data);
                countryName.value = response.data;
            }).catch((error) => {
                console.log('Error occurred:: ' + error);
            })
        }

        let latitude;
        let longitude;
        let address;

        function findLocation(e) {
            let searchTerm;
            let countryName = document.getElementById('countryName').value;

            if(e.target.value.length > 2)
                searchTerm = e.target.value + countryName;
            else
                searchTerm = e.target.value;

            let searchResult = document.getElementById('search-result');
            searchResult.style.display = 'block';
            searchResult.innerHTML = `<p>Searching for location. . .</p>
            <img src="{{ asset('assets/images/loading.gif') }}" width="200px" height="120px">`;

            axios.get('https://maps.googleapis.com/maps/api/geocode/json?address='+searchTerm+'&key=AIzaSyBOn78CtqMoTBNn774iM0d2A8jCA3nMCCA', {
            }).then((response) => {
                console.log(response);
                address = response.data.results[0].formatted_address;
                latitude = response.data.results[0].geometry.location.lat;
                longitude = response.data.results[0].geometry.location.lng;

                searchResult.innerHTML = `
                    <h6>Google Suggestions</h6>
                    <p>${address}
                        <button class="btn btn-primary btn-sm" onclick="selectLocation()">Select</button>
                    </p>
                `;

            }).catch((error) => {
                console.log('Error occurred:: ' + error);
                searchResult.innerHTML = `<p>Could not find a location. Try again<p>
                <p>Try making your location more descriptive by including name of street, state and country</p>`;
            })

            // let selectBtn = document.getElementById('selectLocation');
            // if(selectBtn) {
            //     selectBtn.addEventListener("click", function(){
            //         alert("Hello World!");
            //     });
            // }

            // document.getElementById("selectLocation").addEventListener("click", function(){
            //     alert('hey people')
            // });


        }

        function selectLocation() {
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            document.getElementById('location').value = address;
            alert('location selected');
            document.getElementById('search-result').style.display = 'none';
        }
    </script>
  @endsection
</x-app-layout>
