<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form class="form w-100" method="POST" novalidate="novalidate" action="{{ route('login') }}" id="kt_sign_in_form"
        data-kt-redirect-url="/">
        @csrf
        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">
                Sign In
            </h1>
            <div class="text-gray-500 fw-semibold fs-6">
                Your Social Campaigns
            </div>
        </div>
        <div class="row g-3 mb-9">
            <div class="col-md-6">
                <a href="#"
                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                    <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/google-icon.svg')}}"
                        class="h-15px me-3" />
                    Sign in with Google
                </a>
            </div>
            <div class="col-md-6">
                <a href="#"
                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                    <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/apple-black.svg')}}"
                        class="theme-light-show h-15px me-3" />
                    <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/apple-black-dark.svg')}}"
                        class="theme-dark-show h-15px me-3" />
                    Sign in with Apple
                </a>
            </div>
        </div>
        <div class="separator separator-content my-14">
            <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
        </div>
        <div class="fv-row mb-8">
            <input type="text" placeholder="Email" name="email" autocomplete="off" id="email"
                :value="old('email')" class="form-control bg-transparent" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="fv-row mb-3">
            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off"
                class="form-control bg-transparent" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div><label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="form-check-input"
                        name="remember">
                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">{{ __('Remember me') }}</span>
                </label></div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-primary">
                    Forgot Password ?
                </a>
            @endif
        </div>
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label">
                    Sign In</span>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Not a Member yet?
            <a href="{{ route('register') }}" class="link-primary">
                Sign up
            </a>
        </div>
    </form>
</x-guest-layout>
