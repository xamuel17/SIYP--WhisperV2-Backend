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

        <div class="authentication-forgot d-flex align-items-center justify-content-center">
    			<div class="card shadow-lg forgot-box">
    				<div class="card-body p-md-5">
    					<div class="text-center">
    						<img src="{{ asset('assets/images/whisper-logo.png') }}" width="140" alt="" />
    					</div>
    					<h5 class="mt-5 font-weight-bold" style="text-align:center;">Hello {{ $name }}, Setup Your Password</h5>
    					<!-- <p class="text-muted">Enter your registered email ID to reset the password</p> -->
              <div class="mb-4 text-sm text-gray-600 mt-4">
                  <!-- {{ __('Forgot Password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }} -->
                  @include('components.flash-message')
              </div>

                <form method="POST" action="{{ route('admin.create-password') }}">
                  @csrf
                  <input type="hidden" name="email" value="{{ $email }}" />
        					<div class="form-group mt-5">
                    <x-jet-validation-errors class="mb-4" style="color:red;" />
        						<label>Password</label>
        						<input type="password" class="form-control form-control-lg radius-30" name="password" required autofocus />
        					</div>
                  <div class="form-group mt-5">
        						<label>Confirm Password</label>
        						<input type="password" class="form-control form-control-lg radius-30" name="confirm_password" required />
        					</div>
        					<button type="submit" class="btn btn-primary btn-lg btn-block radius-30">Submit</button>
                  <!-- <a href="{{ route('login') }}" class="btn btn-link btn-block"><i class='bx bx-arrow-back mr-1'></i>Back to Login</a> -->
                </form>
            </div>
    			</div>
    		</div>


    </x-jet-authentication-card>
</x-guest-layout>
