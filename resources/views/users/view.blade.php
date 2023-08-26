<x-app-layout>
  @section('more-styles')
  @endsection

  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">User Profile</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.browse') }}">Users</a></li>
              <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
          </nav>
        </div>
        <!-- <div class="ml-auto">
          <div class="btn-group">
            <button type="button" class="btn btn-primary">Settings</button>
            <button type="button" class="btn btn-primary bg-split-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">	<span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">	<a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">Another action</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div> -->
      </div>
      <!--end breadcrumb-->
      <div class="user-profile-page">
        <div class="card radius-15">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-lg-7 border-right">
                <div class="d-md-flex align-items-center">
                  <div class="mb-md-0 mb-3">
                    @if($user->profile_pic == 'none')
                      <img src="{{ asset('assets/images/avatars/user.jpg') }}" class="rounded-circle shadow" width="130" height="130" alt="" />
                    @else
                      <img src="{{ asset('assets/images/avatars/user.jpg') }}" class="rounded-circle shadow" width="130" height="130" alt="" />
                    @endif
                  </div>
                  <div class="ml-md-4 flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                      <h6 class="mb-0">{{ $user->firstname . ' ' . $user->lastname }}</h6>
                      <!-- <p class="mb-0 ml-auto">$44/hr</p> -->
                    </div>
                    <p class="mb-0 text-muted">{{ $user->username }}</p>
                    <p class="text-primary"><i class='bx bx-envelope'></i> {{ $user->email }}</p>
                    <a class="btn btn-primary" href="{{ route('user-tracking',['user' => $user->id]) }}">View Tracking Information</a>
                    <!-- <button type="button" class="btn btn-outline-secondary ml-2">Resume</button> -->
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-5">
                <table class="table table-sm table-borderless mt-md-0 mt-3">
                  <tbody>
                    <tr>
                      <th>Phone:</th>
                      <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                      <th>Gender:</th>
                      <td>{{ $user->sex}}</td>
                    </tr>
                    <tr>
                      <th>Date Of Birth:</th>
                      <td>{{ $user->dob }}</td>
                    </tr>
                    <tr>
                      <th>Country:</th>
                      <td>{{ $user->country }}</td>
                    </tr>
                    <tr>
                      <th>Status:</th>
                      <td>{{ $user->status }}</td>
                    </tr>

                  </tbody>
                </table>
                <div class="mb-3 mb-lg-0">
                  <!-- <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-github'></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-twitter'></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-facebook'></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-linkedin'></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-dribbble'></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-link"><i class='bx bxl-stack-overflow'></i></a> -->
                </div>
              </div>
            </div>
            <!--end row-->
            <ul class="nav nav-pills mt-2">
              <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#UserGuardian"><span class="p-tab-name">User Guardian</span><i class='bx bx-donate-blood font-24 d-sm-none'></i></a>
              </li>
              <li class="nav-item"> <a class="nav-link" id="profile-tab" data-toggle="tab" href="#PendingGuardian"><span class="p-tab-name">Pending Guardian</span><i class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
              </li>
              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#DeclinedGuardians"><span class="p-tab-name">Declined Guardians</span><i class='bx bx-message-edit font-24 d-sm-none'></i></a>
              </li>
            </ul>
            <div class="tab-content mt-3">
              <div class="tab-pane fade show active" id="UserGuardian">
                <div class="card shadow-none border mb-0 radius-15">
                  <div class="card-body">

                    @if(count($confirmedGuardians) > 0)
                        <div class="card radius-15">
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Guardian Username</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @php $sn = 1; @endphp
                                  @foreach($confirmedGuardians as $cGuardian)
                                    <tr>
                                      <th scope="row">{{ $sn }}</th>
                                      <td>{{ $cGuardian->guardian_username }}</td>
                                    </tr>
                                    @php $sn++; @endphp
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    @else
                        <h6>No Guardian Found</h6>
                    @endif

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="PendingGuardian">
                <div class="card shadow-none border mb-0 radius-15">
                  <div class="card-body">
                        @if(count($pendingGuardians) > 0)
                            <div class="card radius-15">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Guardian Username</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @php $sn = 1; @endphp
                                      @foreach($pendingGuardians as $pGuardian)
                                        <tr>
                                          <th scope="row">{{ $sn }}</th>
                                          <td>{{ $pGuardian->guardian_username }}</td>
                                        </tr>
                                        @php $sn++; @endphp
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                        @else
                            <h6>No Guardian Found</h6>
                        @endif
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="DeclinedGuardians">
                <div class="card shadow-none border mb-0 radius-15">
                  <div class="card-body">
                      @if(count($declinedGuardians) > 0)
                          <div class="card radius-15">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Guardian Username</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @php $sn = 1; @endphp
                                    @foreach($declinedGuardians as $dGuardian)
                                      <tr>
                                        <th scope="row">{{ $sn }}</th>
                                        <td>{{ $dGuardian->guardian_username }}</td>
                                      </tr>
                                      @php $sn++; @endphp
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                      @else
                          <h6>No Guardian Found</h6>
                      @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('more-scripts')
  @endsection
</x-app-layout>
