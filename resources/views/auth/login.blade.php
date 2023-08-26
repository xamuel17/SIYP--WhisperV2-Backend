<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <!-- <x-jet-authentication-card-logo /> -->
        </x-slot>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <div class="section-authentication-login d-flex align-items-center justify-content-center">
    			<div class="row">
    				<div class="col-12 col-lg-10 mx-auto">
    					<div class="card radius-15">
    						<div class="row no-gutters">
    							<div class="col-lg-6">
    								<div class="card-body p-md-5">
    									<div class="text-center">
    										<img src="{{ asset('assets/images/whisper-logo.png') }}" alt="">
    										<h3 class="mt-4 font-weight-bold">Welcome Back</h3>
    									</div>
                      <x-jet-validation-errors class="mb-4" style="color: red;" />
                      @include('components.flash-message')
                      <form method="POST" action="{{ route('login') }}">
                          @csrf
        									<div class="form-group mt-4">
        										<label>Email Address</label>
        										<input type="email" class="form-control" placeholder="Enter your email address" name="email" value="{{ @old('email') }}" required autofocus />
        									</div>
        									<div class="form-group">
        										<label>Password</label>
        										<input type="password" class="form-control" placeholder="Enter your password" name="password" required />
        									</div>
        									<div class="form-row">
        										<div class="form-group col">
        											<div class="custom-control custom-switch">
        												<input type="checkbox" class="custom-control-input" id="customSwitch1" checked name="remember">
        												<label class="custom-control-label" for="customSwitch1">Remember Me</label>
        											</div>
        										</div>
        										<div class="form-group col text-right"> <a href="{{ route('password.request') }}"><i class='bx bxs-key mr-2'></i>Forget Password?</a>
        										</div>
        									</div>
        									<div class="btn-group mt-3 w-100">
        										<button type="submit" class="btn btn-primary btn-block">Log In</button>
        										<button type="button" class="btn btn-primary"><i class="lni lni-arrow-right"></i>
        										</button>
        									</div>
                      </form>
    									<!-- <hr> -->
    									<!-- <div class="text-center">
    										<p class="mb-0">Don't have an account? <a href="authentication-register.html">Sign up</a>
    										</p>
    									</div> -->
    								</div>
    							</div>
    							<div class="col-lg-6">
    								<img src="{{ asset('assets/images/login-images/whisper-banner.jpg') }}" class="card-img login-img h-100" alt="...">
    							</div>
    						</div>
    						<!--end row-->
    					</div>
    				</div>
    			</div>
    		</div>

    </x-jet-authentication-card>
</x-guest-layout>
