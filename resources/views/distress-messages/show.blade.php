<x-app-layout>
  @section('more-styles')
  @endsection

  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">Distress Message</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('distress-message.browse') }}">Distress Messages</a></li>
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">Update Message Status</button>

                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-hidden="true">
      								<div class="modal-dialog">
      									<div class="modal-content">
      										<div class="modal-header">
      											<h5 class="modal-title">Update Distress Message Status</h5>
      											<button type="button" class="close" data-dismiss="modal" aria-label="Close">	<span aria-hidden="true">&times;</span>
      											</button>
      										</div>
      										<div class="modal-body">
                              <form method="post" action="{{ route('distress-message.update-priority', ['message' => $distressMessage->id]) }}">
                                  @csrf
                                  <div class="form-row">
                                    <div class="form-group col-md-12">
                                      <label>Status</label>
                                      <div class="input-group">
                                        <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                                        </div>
                                        <select class="form-control border-left-0" name="status" required>
                                            <option value="urgent"
                                              @if($distressMessage->priority=='urgent')
                                                {{ 'selected' }}
                                              @endif
                                            >Urgent</option>
                                            <option value="false-alarm"
                                            @if($distressMessage->priority=='false-alarm')
                                              {{ 'selected' }}
                                            @endif
                                            >False Alarm</option>
                                            <option value="resolved"
                                            @if($distressMessage->priority=='resolved')
                                              {{ 'selected' }}
                                            @endif
                                            >Resolved</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>

                          </div>
      										<div class="modal-footer">
      											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      											<button type="submit" class="btn btn-primary">Save changes</button>
      										</div>
                          </form>
      									</div>
      								</div>
      							</div>

                    <a href="{{ route('user.send-message', ['user' => $user->id]) }}" class="btn btn-outline-secondary ml-2">Message User</a>
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


            <div class="card-body">
              <div class="d-sm-flex align-items-center mb-3">
                <h4 class="mb-0">Distress Message</h4>
                <!-- <p class="mb-0 ml-sm-3 text-muted">3 Job History</p> -->
                <!-- <a href="javascript:;" class="btn btn-primary ml-auto radius-10"><i class='bx bx-plus'></i> Add More</a> -->
              </div>
              <div class="media"> <i class='bx bxl-dribbble media-icons bg-dribbble'></i>
                <div class="media-body ml-3">
                  <div class="row align-items-center">
                    <div class="col-lg-4">
                      <h5 class="text-muted mb-0"><i class='bx bx-star'></i> {{ $distressMessage->priority }}</h5>
                    </div>
                    <div class="col-lg-4">
                      <h5 class="text-muted mb-0"><i class='bx bx-time'></i> {{ date('j-M-Y h:iA', strtotime($distressMessage->time_of_message)) }}</h5>
                    </div>
                    <!-- <div class="col-lg-4">
                      <h5 class="text-muted mb-0"><i class='bx bxs-map'></i> long:{{ $distressMessage->longitude }} lat: {{ $distressMessage->latitude }}</h5>
                    </div> -->
                  </div>
                  <p class="mt-2">{{ $distressMessage->content }}</p>
                  <!-- <h6>Media Files(2)</h6> -->
                  <!-- <div class="row">
                    <div class="col-12 col-lg-3">
                      <img src="{{ asset('assets/images/gallery/35.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="col-12 col-lg-3">
                      <img src="{{ asset('assets/images/gallery/36.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="col-12 col-lg-3">
                      <img src="{{ asset('assets/images/gallery/37.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="col-12 col-lg-3">
                      <img src="{{ asset('assets/images/gallery/38.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                  </div> -->
                  <hr/>
                </div>
              </div>
              <div class="media">
                <div class="media-body ml-3">
                  <p class="mt-2">
                    @if($distressMessage->photo)
                      <img src="{{ asset('distress-photos/'.$distressMessage->photo.'') }}">
                    @elseif($distressMessage->audio)
                      <audio controls>
                        <source src="{{ asset('distress-audio/'.$distressMessage->audio.'') }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                      </audio>
                    @elseif($distressMessage->video)
                      <video width="320" height="240" controls>
                        <source src="{{ asset('distress-videos/'.$distressMessage->video.'') }}" type="video/mp4">
                        Your browser does not support the video element.
                      </video>
                    @endif
                  </p>
                </div>
              </div>
            </div>

            <!--end row-->
            <!-- <ul class="nav nav-pills mt-2">
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

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="PendingGuardian">
                <div class="card shadow-none border mb-0 radius-15">
                  <div class="card-body">

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="DeclinedGuardians">
                <div class="card shadow-none border mb-0 radius-15">
                  <div class="card-body">

                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('more-scripts')
  @endsection
</x-app-layout>
