<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="ar" dir="rtl">
	<!--begin::Head-->
	<head>
		<title>تسجيل الدخول - نظام إدارة القروض</title>
		<meta name="description" content="نظام إدارة القروض - بنك الرياض" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14.png">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="#" class="mb-12">
						<img alt="Logo" src="{{asset('assets/media/logos/logo-1.svg')}}" class="h-40px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<form class="form w-100" method="POST" action="{{ route('login') }}" id="kt_sign_in_form">
							@csrf
							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">تسجيل الدخول</h1>
								<!--end::Title-->
								<!--begin::Subtitle-->
								<div class="text-gray-400 fw-bold fs-4">مرحباً بك في نظام إدارة القروض</div>
								<!--end::Subtitle-->
							</div>
							<!--end::Heading-->

							<!--begin::Alert-->
							@if ($errors->any())
								<div class="alert alert-danger d-flex align-items-center p-5 mb-10">
									<span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
											<rect x="11" y="14" width="2" height="2" rx="1" fill="black"/>
											<path d="M10.5 7L11.5 12L12.5 7H10.5Z" fill="black"/>
										</svg>
									</span>
									<div class="d-flex flex-column">
										<h4 class="mb-1 text-danger">خطأ في البيانات</h4>
										@foreach ($errors->all() as $error)
											<span class="fs-7">{{ $error }}</span>
										@endforeach
									</div>
								</div>
							@endif

							@if (session('success'))
								<div class="alert alert-success d-flex align-items-center p-5 mb-10">
									<span class="svg-icon svg-icon-2hx svg-icon-success me-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
											<path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"/>
										</svg>
									</span>
									<div class="d-flex flex-column">
										<h4 class="mb-1 text-success">نجح</h4>
										<span class="fs-7">{{ session('success') }}</span>
									</div>
								</div>
							@endif
							<!--end::Alert-->

							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<label class="form-label fs-6 fw-bolder text-dark">البريد الإلكتروني</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
									   type="email"
									   name="email"
									   value="{{ old('email') }}"
									   autocomplete="email"
									   placeholder="أدخل البريد الإلكتروني" />
								<!--end::Input-->
								@error('email')
									<div class="fv-plugins-message-container">
										<div class="fv-help-block">
											<span role="alert">{{ $message }}</span>
										</div>
									</div>
								@enderror
							</div>
							<!--end::Input group-->

							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Wrapper-->
								<div class="d-flex flex-stack mb-2">
									<!--begin::Label-->
									<label class="form-label fw-bolder text-dark fs-6 mb-0">كلمة المرور</label>
									<!--end::Label-->
									<!--begin::Link-->
									<!--end::Link-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
									   type="password"
									   name="password"
									   autocomplete="current-password"
									   placeholder="أدخل كلمة المرور" />
								<!--end::Input-->
								@error('password')
									<div class="fv-plugins-message-container">
										<div class="fv-help-block">
											<span role="alert">{{ $message }}</span>
										</div>
									</div>
								@enderror
							</div>
							<!--end::Input group-->

							<!--begin::Remember me-->
							<div class="fv-row mb-10">
							
							</div>
							<!--end::Remember me-->

							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
									<span class="indicator-label">تسجيل الدخول</span>
									<span class="indicator-progress">جاري التحميل...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
								<!--end::Submit button-->
							</div>
							<!--end::Actions-->
						</form>
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="d-flex flex-center flex-column-auto p-10">
					<!--begin::Links-->
					<div class="d-flex align-items-center fw-bold fs-6">
						<a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>
						<a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
						<a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
					</div>
					<!--end::Links-->
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->
		<script>var hostUrl = "{{asset('assets/')}}/";</script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const form = document.getElementById('kt_sign_in_form');
				const submitButton = document.getElementById('kt_sign_in_submit');

				if (form && submitButton) {
					form.addEventListener('submit', function() {
						// Show loading state
						submitButton.setAttribute('data-kt-indicator', 'on');
						submitButton.disabled = true;
					});
				}
			});
		</script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
