<x-app-layout>
  @section('more-styles')
  @endsection

  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">View Sex Offender</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('offenders.browse') }}">Sex Offenders</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
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
                    @if($offender->photo == NULL)
                      <img src="{{ asset('assets/images/avatars/user.jpg') }}" class="rounded-circle shadow" width="130" height="130" alt="" />
                    @else
                      <img src="{{ asset('offenders-photo/'.$offender->photo.'') }}" class="rounded-circle shadow" width="130" height="130" alt="" />
                    @endif
                  </div>
                  <div class="ml-md-4 flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                      <h6 class="mb-0">{{ $offender->offender_name }}</h6>
                      <!-- <p class="mb-0 ml-auto">$44/hr</p> -->
                    </div>
                    <div class="d-flex align-items-center mb-1">
                      <h6 class="mb-0">{{ $offender->title }}</h6>
                      <!-- <p class="mb-0 ml-auto">$44/hr</p> -->
                    </div>
                    <!-- <p class="mb-0 text-muted">username</p> -->
                    <!-- <p class="text-primary"><i class='bx bx-envelope'></i> email</p> -->
                    <!-- <a class="btn btn-primary" href="{{ route('user-tracking',['user' => $offender->id]) }}">View Tracking Information</a> -->
                    <!-- <button type="button" class="btn btn-outline-secondary ml-2">Resume</button> -->
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-5">
                <table class="table table-sm table-borderless mt-md-0 mt-3">
                  <tbody>
                      <tr>
                          <td>Title</td>
                          <td>{{ $offender->title }}</td>
                      </tr>
                      <tr>
                          <td>Offence</td>
                          <td>{{ $offender->offence }}</td>
                      </tr>
                      <tr>
                          <td>Source URL</td>
                          <td>{{ $offender->source_url }}</td>
                      </td>
                      @php
                      $admin = \App\Models\WebModels\Admin::where('id',$offender->admin_id)->first();
                      @endphp
                      <tr>
                          <td>Added By</td>
                          <td>{{ $admin->firstname .' '.$admin->lastname }}</td>
                      </td>



                  </tbody>
                </table>
                <div class="mb-3 mb-lg-0">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card radius-15">
          <div class="card-body">
              <p>{{ strip_tags($offender->content) }}</p>
          </div>
      </div>

      <div class="card radius-15">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-4">
                      <h5 class="text-success">True Votes ({{ count($trueVotes) }})</h5>
                      @if(count($trueVotes) > 0)
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>SN</th>
                                <th>Name of User</th>
                                <th>Date of Vote</th>
                            </tr>
                            @php $sn=1; @endphp
                        @foreach($trueVotes as $trueVote)
                            <tr>
                                <td>{{ $sn }}</td>
                                @php
                                    $user = \App\Models\User::where('id',$trueVote->user_id)->first();
                                @endphp
                                <td>{{ $user->firstname ?? NULL }} {{ $user->lastname ?? NULL}}</td>
                                <td>{{ date('jS M. Y g:iA', strtotime($trueVote->created_at)) ?? '' }}</td>
                            </tr>
                            @php $sn++; @endphp
                        @endforeach
                        </table>
                        </div>
                      @endif
                  </div>
                  <div class="col-md-4">
                      <h5 class="text-danger">False Votes ({{ count($falseVotes) }})</h5>
                      @if(count($falseVotes) > 0)
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>SN</th>
                                <th>Name of User</th>
                                <th>Date of Vote</th>
                            </tr>
                            @php $sn=1; @endphp
                        @foreach($falseVotes as $falseVote)
                            <tr>
                                <td>{{ $sn }}</td>
                                @php
                                    $user = \App\Models\User::where('id',$falseVote->user_id)->first();
                                @endphp
                                <td>{{ $user->firstname ?? NULL }} {{ $user->lastname ?? NULL}}</td>
                                <td>{{ date('jS M. Y g:iA', strtotime($falseVote->created_at)) ?? '' }}</td>
                            </tr>
                            @php $sn++; @endphp
                        @endforeach
                        </table>
                        </div>
                      @endif
                  </div>
                  <div class="col-md-4">
                      <h5 class="text-warning">Not Sure Votes ({{ count($notSureVotes) }})</h5>
                      @if(count($notSureVotes) > 0)
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>SN</th>
                                <th>Name of User</th>
                                <th>Date of Vote</th>
                            </tr>
                            @php $sn=1; @endphp
                        @foreach($notSureVotes as $notSureVote)
                            <tr>
                                <td>{{ $sn }}</td>
                                @php
                                    $user = \App\Models\User::where('id',$notSureVote->user_id)->first();
                                @endphp
                                <td>{{ $user->firstname ?? NULL }} {{ $user->lastname ?? NULL}}</td>
                                <td>{{ date('jS M. Y g:iA', strtotime($notSureVote->created_at)) ?? '' }}</td>

                            </tr>
                            @php $sn++; @endphp
                        @endforeach
                        </table>
                        </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>


    </div>
  </div>

  @section('more-scripts')
  @endsection
</x-app-layout>
