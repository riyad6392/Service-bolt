<!DOCTYPE html>
<html>
<head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel='stylesheet' href="{{ asset('css/main.min.css')}}">


<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />

<link rel='stylesheet' href="{{ asset('css/slick-theme.css')}}">
<link rel='stylesheet' href="{{ asset('css/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/stylesuperadmin.css')}}">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dropify.css')}}">
  <title>
  Services Bolt
  </title>
</head>
<body style="background: #F7F9FA;">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- side bar start -->


<div class="sidebar pt-2">
<div class="sidebar-scroll">
    <div class="logo">
        <img src="{{ asset('images/logo.png')}}" style="width: 166px;">
    </div>
    <ul class="dashboard mt-3">
        
            <li class="link">
      <a href="{{ route('superadmin.home') }}"><svg width="18" height="18" viewBox="0 0 18 18"  xmlns="http://www.w3.org/2000/svg">
<path d="M6.9375 0H1.3125C0.58875 0 0 0.58875 0 1.3125V4.6875C0 5.41125 0.58875 6 1.3125 6H6.9375C7.66125 6 8.25 5.41125 8.25 4.6875V1.3125C8.25 0.58875 7.66125 0 6.9375 0Z" fill="currentColor"/>
<path d="M6.9375 7.5H1.3125C0.58875 7.5 0 8.08875 0 8.8125V16.6875C0 17.4113 0.58875 18 1.3125 18H6.9375C7.66125 18 8.25 17.4113 8.25 16.6875V8.8125C8.25 8.08875 7.66125 7.5 6.9375 7.5Z" fill="currentColor"/>
<path d="M16.6875 12H11.0625C10.3387 12 9.75 12.5888 9.75 13.3125V16.6875C9.75 17.4113 10.3387 18 11.0625 18H16.6875C17.4112 18 18 17.4113 18 16.6875V13.3125C18 12.5888 17.4112 12 16.6875 12Z" fill="currentColor"/>
<path d="M16.6875 0H11.0625C10.3387 0 9.75 0.58875 9.75 1.3125V9.1875C9.75 9.91125 10.3387 10.5 11.0625 10.5H16.6875C17.4112 10.5 18 9.91125 18 9.1875V1.3125C18 0.58875 17.4112 0 16.6875 0Z" fill="currentColor"/>
</svg> Dashboard</a></li>
        
        
            <li class="link"><a href="{{ route('superadmin.manageUser') }}"><svg width="18" height="16" viewBox="0 0 18 16" xmlns="http://www.w3.org/2000/svg">
<path d="M9 6.26434C7.31079 6.26434 5.94141 4.89496 5.94141 3.20575C5.94141 1.51653 7.31079 0.147156 9 0.147156C10.6892 0.147156 12.0586 1.51653 12.0586 3.20575C12.0586 4.89496 10.6892 6.26434 9 6.26434Z" fill="currentColor"/>
<path d="M2.8125 6.26432C1.74461 6.26432 0.878906 5.39862 0.878906 4.33072C0.878906 3.26283 1.74461 2.39713 2.8125 2.39713C3.88039 2.39713 4.74609 3.26283 4.74609 4.33072C4.74609 5.39862 3.88039 6.26432 2.8125 6.26432Z" fill="currentColor"/>
<path d="M15.1875 6.26432C14.1196 6.26432 13.2539 5.39862 13.2539 4.33072C13.2539 3.26283 14.1196 2.39713 15.1875 2.39713C16.2554 2.39713 17.1211 3.26283 17.1211 4.33072C17.1211 5.39862 16.2554 6.26432 15.1875 6.26432Z" fill="currentColor"/>
<path d="M13.2824 8.00069C14.0435 7.37709 14.7328 7.45964 15.6129 7.45964C16.9291 7.45964 18 8.52417 18 9.83233V13.6717C18 14.2399 17.5363 14.7018 16.9661 14.7018C14.5042 14.7018 14.8008 14.7464 14.8008 14.5956C14.8008 11.875 15.123 9.8799 13.2824 8.00069Z" fill="currentColor"/>
<path d="M8.16307 7.47367C9.70024 7.34546 11.0364 7.47515 12.1888 8.42641C14.1174 9.97117 13.7462 12.0511 13.7462 14.5956C13.7462 15.2688 13.1985 15.8268 12.5151 15.8268C5.09421 15.8268 4.79886 16.0662 4.35881 15.0917C4.2145 14.7621 4.25405 14.8668 4.25405 11.7142C4.25405 9.21018 6.42224 7.47367 8.16307 7.47367Z" fill="currentColor"/>
<path d="M2.38726 7.45965C3.27215 7.45965 3.95766 7.37794 4.71777 8.0007C2.89088 9.86594 3.19937 11.725 3.19937 14.5956C3.19937 14.7473 3.44557 14.7018 1.07101 14.7018C0.480389 14.7018 0.000154495 14.2233 0.000154495 13.6352V9.83233C0.000154495 8.52417 1.07101 7.45965 2.38726 7.45965Z" fill="currentColor"/>
</svg>Manage Users</a></li>
      
        
            <li class="link"><a href="{{ route('superadmin.managePayment') }}" ><svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
<path d="M6.44311 7.36692H9.01011H11.5771C11.8559 7.36692 12.0817 7.14019 12.0817 6.86021V6.30801C12.0817 4.85982 10.9317 3.6091 9.3647 3.6091C10.1922 3.44366 10.8162 2.71061 10.8162 1.83076C10.8162 0.829331 10.0074 0.0176086 9.01011 0.0176086C8.0128 0.0176086 7.20407 0.829331 7.20407 1.83076C7.20407 2.71061 7.82798 3.44366 8.65553 3.6091C7.09058 3.6091 5.93848 4.85789 5.93848 6.30801V6.86021C5.93851 7.14016 6.16429 7.36692 6.44311 7.36692Z" fill="currentColor"/>
<path d="M2.26824 8.78087C2.30016 8.7867 2.33184 8.78951 2.36316 8.78951C2.61277 8.78951 2.8345 8.61057 2.8808 8.35519C3.01049 7.63962 3.25602 6.94434 3.61054 6.28871C3.95268 5.656 4.38173 5.08781 4.88576 4.59994C5.09522 4.39723 5.10134 4.06237 4.89944 3.85203C4.69754 3.64172 4.36408 3.63557 4.15462 3.83828C3.57289 4.40138 3.07831 5.05599 2.6847 5.78394C2.27678 6.53832 1.99402 7.33967 1.84429 8.16577C1.79222 8.45317 1.98203 8.72855 2.26824 8.78087Z" fill="currentColor"/>
<path d="M14.4098 6.28869C14.7643 6.94436 15.0099 7.63961 15.1396 8.35518C15.1859 8.61059 15.4076 8.7895 15.6572 8.7895C15.6885 8.7895 15.7202 8.78669 15.7521 8.78085C16.0383 8.72854 16.2281 8.45316 16.176 8.16576C16.0263 7.33965 15.7435 6.53827 15.3356 5.78392C14.942 5.05597 14.4474 4.40133 13.8657 3.83827C13.6562 3.63552 13.3228 3.64171 13.1209 3.85201C12.919 4.06235 12.9251 4.39718 13.1346 4.59993C13.6386 5.08779 14.0677 5.65599 14.4098 6.28869Z" fill="currentColor"/>
<path d="M11.1383 12.8208C10.4588 13.0451 9.7431 13.16 9.01024 13.1625C9.00074 13.1628 8.99093 13.1628 8.98144 13.1628C8.26471 13.1628 7.5624 13.0553 6.89481 12.8434C6.6174 12.7552 6.3217 12.9097 6.23391 13.1882C6.14613 13.4668 6.29993 13.7637 6.57735 13.8518C7.3478 14.0966 8.15654 14.2207 8.98144 14.2207H9.01024C9.85515 14.2175 10.6814 14.0849 11.467 13.8257C11.7434 13.7347 11.894 13.4357 11.803 13.1582C11.7125 12.8808 11.4146 12.7295 11.1383 12.8208Z" fill="currentColor"/>
<path d="M3.42615 14.2246C4.25373 14.0591 4.87768 13.3258 4.87768 12.4462C4.87768 11.4448 4.06895 10.6327 3.07164 10.6327C2.07432 10.6327 1.26559 11.4448 1.26559 12.4462C1.26559 13.3258 1.88954 14.0591 2.71712 14.2246C1.15351 14.2246 0 15.4721 0 16.9235V17.4757C0 17.7553 0.225809 17.9824 0.504633 17.9824H3.07164H5.63864C5.91746 17.9824 6.14327 17.7553 6.14327 17.4757V16.9235C6.14327 15.4782 4.99634 14.2246 3.42615 14.2246Z" fill="currentColor"/>
<path d="M15.2829 14.2246C16.1105 14.0591 16.7344 13.3258 16.7344 12.4462C16.7344 11.4448 15.9257 10.6327 14.9284 10.6327C13.9307 10.6327 13.1223 11.4448 13.1223 12.4462C13.1223 13.3258 13.746 14.0591 14.5737 14.2246C13.0065 14.2246 11.8567 15.475 11.8567 16.9235V17.4757C11.8567 17.7553 12.0825 17.9824 12.3613 17.9824H14.9283H17.4953C17.7738 17.9824 18 17.7553 18 17.4757V16.9235C18 15.4752 16.8499 14.2246 15.2829 14.2246Z" fill="currentColor"/>
</svg>Manage payments</a></li>
      
       
            <li class="link">
       <a href="{{ route('superadmin.manageTenture') }}" >
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M9.75001 12.534C9.75001 12.1666 10.0035 11.8667 10.3125 11.8667H13.125V14.5359C13.125 15.027 13.461 15.4256 13.875 15.4256C14.289 15.4256 14.625 15.027 14.625 14.5359V11.8667H17.4375C17.7473 11.8667 18 12.1666 18 12.534V20.9864C18 21.3539 17.7473 21.6537 17.4375 21.6537H10.3125C10.0035 21.6537 9.75001 21.3539 9.75001 20.9864V12.534Z" fill="currentColor"/>
<path d="M-7.72476e-05 12.534C-7.72476e-05 12.1666 0.253422 11.8667 0.562422 11.8667H3.37492V14.5359C3.37492 15.027 3.71092 15.4256 4.12492 15.4256C4.53891 15.4256 4.87491 15.027 4.87491 14.5359V11.8667H7.68741C7.99716 11.8667 8.24991 12.1666 8.24991 12.534V20.9864C8.24991 21.3539 7.99716 21.6537 7.68741 21.6537H0.562422C0.253422 21.6537 -7.72476e-05 21.3539 -7.72476e-05 20.9864V12.534Z" fill="currentColor"/>
<path d="M4.87511 0.967616C4.87511 0.60105 5.12861 0.300323 5.4376 0.300323H8.2501V2.96949C8.2501 3.46062 8.5861 3.85922 9.0001 3.85922C9.4141 3.85922 9.7501 3.46062 9.7501 2.96949V0.300323H12.5626C12.8723 0.300323 13.1251 0.60105 13.1251 0.967616V9.41999C13.1251 9.78744 12.8723 10.0873 12.5626 10.0873H5.4376C5.12861 10.0873 4.87511 9.78744 4.87511 9.41999V0.967616Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="matrix(-1 0 0 1 18 0.300323)"/>
</clipPath>
</defs>
</svg>Manage Tenure</a></li>

<li class="link">
       <a href="{{ route('superadmin.manageFeature') }}" >
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M9.75001 12.534C9.75001 12.1666 10.0035 11.8667 10.3125 11.8667H13.125V14.5359C13.125 15.027 13.461 15.4256 13.875 15.4256C14.289 15.4256 14.625 15.027 14.625 14.5359V11.8667H17.4375C17.7473 11.8667 18 12.1666 18 12.534V20.9864C18 21.3539 17.7473 21.6537 17.4375 21.6537H10.3125C10.0035 21.6537 9.75001 21.3539 9.75001 20.9864V12.534Z" fill="currentColor"/>
<path d="M-7.72476e-05 12.534C-7.72476e-05 12.1666 0.253422 11.8667 0.562422 11.8667H3.37492V14.5359C3.37492 15.027 3.71092 15.4256 4.12492 15.4256C4.53891 15.4256 4.87491 15.027 4.87491 14.5359V11.8667H7.68741C7.99716 11.8667 8.24991 12.1666 8.24991 12.534V20.9864C8.24991 21.3539 7.99716 21.6537 7.68741 21.6537H0.562422C0.253422 21.6537 -7.72476e-05 21.3539 -7.72476e-05 20.9864V12.534Z" fill="currentColor"/>
<path d="M4.87511 0.967616C4.87511 0.60105 5.12861 0.300323 5.4376 0.300323H8.2501V2.96949C8.2501 3.46062 8.5861 3.85922 9.0001 3.85922C9.4141 3.85922 9.7501 3.46062 9.7501 2.96949V0.300323H12.5626C12.8723 0.300323 13.1251 0.60105 13.1251 0.967616V9.41999C13.1251 9.78744 12.8723 10.0873 12.5626 10.0873H5.4376C5.12861 10.0873 4.87511 9.78744 4.87511 9.41999V0.967616Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="matrix(-1 0 0 1 18 0.300323)"/>
</clipPath>
</defs>
</svg>Manage Feature</a></li>

<li class="link">
       <a href="{{ route('superadmin.productfeature') }}" >
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M9.75001 12.534C9.75001 12.1666 10.0035 11.8667 10.3125 11.8667H13.125V14.5359C13.125 15.027 13.461 15.4256 13.875 15.4256C14.289 15.4256 14.625 15.027 14.625 14.5359V11.8667H17.4375C17.7473 11.8667 18 12.1666 18 12.534V20.9864C18 21.3539 17.7473 21.6537 17.4375 21.6537H10.3125C10.0035 21.6537 9.75001 21.3539 9.75001 20.9864V12.534Z" fill="currentColor"/>
<path d="M-7.72476e-05 12.534C-7.72476e-05 12.1666 0.253422 11.8667 0.562422 11.8667H3.37492V14.5359C3.37492 15.027 3.71092 15.4256 4.12492 15.4256C4.53891 15.4256 4.87491 15.027 4.87491 14.5359V11.8667H7.68741C7.99716 11.8667 8.24991 12.1666 8.24991 12.534V20.9864C8.24991 21.3539 7.99716 21.6537 7.68741 21.6537H0.562422C0.253422 21.6537 -7.72476e-05 21.3539 -7.72476e-05 20.9864V12.534Z" fill="currentColor"/>
<path d="M4.87511 0.967616C4.87511 0.60105 5.12861 0.300323 5.4376 0.300323H8.2501V2.96949C8.2501 3.46062 8.5861 3.85922 9.0001 3.85922C9.4141 3.85922 9.7501 3.46062 9.7501 2.96949V0.300323H12.5626C12.8723 0.300323 13.1251 0.60105 13.1251 0.967616V9.41999C13.1251 9.78744 12.8723 10.0873 12.5626 10.0873H5.4376C5.12861 10.0873 4.87511 9.78744 4.87511 9.41999V0.967616Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="matrix(-1 0 0 1 18 0.300323)"/>
</clipPath>
</defs>
</svg>Manage Product Feature Content</a></li>

<li class="link">
       <a href="{{ route('superadmin.manageProductimg') }}">
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M9.75001 12.534C9.75001 12.1666 10.0035 11.8667 10.3125 11.8667H13.125V14.5359C13.125 15.027 13.461 15.4256 13.875 15.4256C14.289 15.4256 14.625 15.027 14.625 14.5359V11.8667H17.4375C17.7473 11.8667 18 12.1666 18 12.534V20.9864C18 21.3539 17.7473 21.6537 17.4375 21.6537H10.3125C10.0035 21.6537 9.75001 21.3539 9.75001 20.9864V12.534Z" fill="currentColor"/>
<path d="M-7.72476e-05 12.534C-7.72476e-05 12.1666 0.253422 11.8667 0.562422 11.8667H3.37492V14.5359C3.37492 15.027 3.71092 15.4256 4.12492 15.4256C4.53891 15.4256 4.87491 15.027 4.87491 14.5359V11.8667H7.68741C7.99716 11.8667 8.24991 12.1666 8.24991 12.534V20.9864C8.24991 21.3539 7.99716 21.6537 7.68741 21.6537H0.562422C0.253422 21.6537 -7.72476e-05 21.3539 -7.72476e-05 20.9864V12.534Z" fill="currentColor"/>
<path d="M4.87511 0.967616C4.87511 0.60105 5.12861 0.300323 5.4376 0.300323H8.2501V2.96949C8.2501 3.46062 8.5861 3.85922 9.0001 3.85922C9.4141 3.85922 9.7501 3.46062 9.7501 2.96949V0.300323H12.5626C12.8723 0.300323 13.1251 0.60105 13.1251 0.967616V9.41999C13.1251 9.78744 12.8723 10.0873 12.5626 10.0873H5.4376C5.12861 10.0873 4.87511 9.78744 4.87511 9.41999V0.967616Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="matrix(-1 0 0 1 18 0.300323)"/>
</clipPath>
</defs>
</svg>Manage Product Feature Image</a></li>

<li class="link">
       <a href="{{ route('superadmin.managehomepagecontent') }}" >
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M9.75001 12.534C9.75001 12.1666 10.0035 11.8667 10.3125 11.8667H13.125V14.5359C13.125 15.027 13.461 15.4256 13.875 15.4256C14.289 15.4256 14.625 15.027 14.625 14.5359V11.8667H17.4375C17.7473 11.8667 18 12.1666 18 12.534V20.9864C18 21.3539 17.7473 21.6537 17.4375 21.6537H10.3125C10.0035 21.6537 9.75001 21.3539 9.75001 20.9864V12.534Z" fill="currentColor"/>
<path d="M-7.72476e-05 12.534C-7.72476e-05 12.1666 0.253422 11.8667 0.562422 11.8667H3.37492V14.5359C3.37492 15.027 3.71092 15.4256 4.12492 15.4256C4.53891 15.4256 4.87491 15.027 4.87491 14.5359V11.8667H7.68741C7.99716 11.8667 8.24991 12.1666 8.24991 12.534V20.9864C8.24991 21.3539 7.99716 21.6537 7.68741 21.6537H0.562422C0.253422 21.6537 -7.72476e-05 21.3539 -7.72476e-05 20.9864V12.534Z" fill="currentColor"/>
<path d="M4.87511 0.967616C4.87511 0.60105 5.12861 0.300323 5.4376 0.300323H8.2501V2.96949C8.2501 3.46062 8.5861 3.85922 9.0001 3.85922C9.4141 3.85922 9.7501 3.46062 9.7501 2.96949V0.300323H12.5626C12.8723 0.300323 13.1251 0.60105 13.1251 0.967616V9.41999C13.1251 9.78744 12.8723 10.0873 12.5626 10.0873H5.4376C5.12861 10.0873 4.87511 9.78744 4.87511 9.41999V0.967616Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="matrix(-1 0 0 1 18 0.300323)"/>
</clipPath>
</defs>
</svg>Manage Home Page Content</a></li>
       
        
            <li class="link">
      <a href="{{ route('superadmin.manageCmspages') }}" >
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<g clip-path="url(#clip1)">
<path d="M4.46414 14.6728V13.1033C2.60431 13.866 1.26492 15.964 1.26492 18.4263V20.9287C1.26492 21.2742 1.50103 21.5543 1.79227 21.5543H6.57352V17.7378C5.37145 17.4472 4.46414 16.1834 4.46414 14.6728Z" fill="currentColor"/>
<path d="M9.73758 13.1304V14.6728C9.73758 16.1834 8.83027 17.4472 7.6282 17.7378V21.5543H12.3391C12.6304 21.5543 12.8665 21.2742 12.8665 20.9287V18.4263C12.8665 15.9551 11.5335 13.9006 9.73758 13.1304Z" fill="currentColor"/>
<path d="M8.82352 0.242615H5.30789C3.0786 0.242615 1.26492 2.39417 1.26492 5.03878V9.66812C1.26492 10.0136 1.50103 10.2937 1.79227 10.2937H2.59109C2.47198 9.89521 2.38968 9.47623 2.34907 9.04253H2.31961C2.31961 8.56225 2.31961 6.01127 2.31961 5.03878C2.31961 3.08407 3.66015 1.49379 5.30789 1.49379H8.82352C10.4713 1.49379 11.8118 3.08407 11.8118 5.03878V9.04253H11.7823C11.7417 9.47623 11.6594 9.89521 11.5403 10.2937H12.3391C12.6304 10.2937 12.8665 10.0136 12.8665 9.66812V5.03878C12.8665 2.39417 11.0528 0.242615 8.82352 0.242615Z" fill="currentColor"/>
<path d="M17.4186 9.45921C17.3014 9.38017 17.1556 9.48077 17.1556 9.64071V11.4198C17.1556 11.6501 16.9982 11.8368 16.8041 11.8368H15.3978C15.2037 11.8368 15.0463 11.6501 15.0463 11.4198V9.64209C15.0463 9.48256 14.9006 9.38051 14.7841 9.46029C13.9951 10.0008 13.4642 11.0121 13.4642 12.1705C13.4642 13.453 14.1152 14.5546 15.0463 15.0374V21.1789C15.0463 21.4093 15.2037 21.596 15.3978 21.596H16.8041C16.9982 21.596 17.1556 21.4093 17.1556 21.1789V15.0374C19.1712 13.9923 19.2566 10.6983 17.4186 9.45921Z" fill="currentColor"/>
<path d="M10.7571 5.08048C10.7571 3.81567 9.8897 2.78667 8.82352 2.78667H5.30789C4.24171 2.78667 3.3743 3.81567 3.3743 5.08048V7.79136H3.93856C4.81089 7.79136 5.52059 6.94945 5.52059 5.9146C5.52059 5.56911 5.7567 5.28901 6.04793 5.28901H10.7571V5.08048Z" fill="currentColor"/>
<path d="M8.68289 14.6728V13.7104C7.6661 14.1489 6.54328 14.16 5.51883 13.7399V14.6728C5.51883 15.7077 6.22853 16.5496 7.10086 16.5496C7.97319 16.5496 8.68289 15.7077 8.68289 14.6728Z" fill="currentColor"/>
<path d="M7.10085 9.04254H10.7193C10.744 8.83814 10.7571 8.62936 10.7571 8.41695V6.54019H6.52228C6.27728 7.96615 5.21187 9.04254 3.93855 9.04254H3.41212C3.66879 11.1619 5.2093 12.7961 7.0657 12.7961C8.5353 12.7961 9.80693 11.7719 10.4004 10.2937H7.10085C6.80962 10.2937 6.57351 10.0136 6.57351 9.66812C6.57351 9.32263 6.80962 9.04254 7.10085 9.04254Z" fill="currentColor"/>
</g>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="translate(0 0.242615)"/>
</clipPath>
<clipPath id="clip1">
<rect width="18" height="21.3534" fill="white" transform="translate(1 0.242615)"/>
</clipPath>
</defs>
</svg>Manage CMS pages</a></li>

 <li class="link">
      <a href="{{ route('superadmin.manageChecklist') }}">
      <svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<g clip-path="url(#clip1)">
<path d="M4.46414 14.6728V13.1033C2.60431 13.866 1.26492 15.964 1.26492 18.4263V20.9287C1.26492 21.2742 1.50103 21.5543 1.79227 21.5543H6.57352V17.7378C5.37145 17.4472 4.46414 16.1834 4.46414 14.6728Z" fill="currentColor"/>
<path d="M9.73758 13.1304V14.6728C9.73758 16.1834 8.83027 17.4472 7.6282 17.7378V21.5543H12.3391C12.6304 21.5543 12.8665 21.2742 12.8665 20.9287V18.4263C12.8665 15.9551 11.5335 13.9006 9.73758 13.1304Z" fill="currentColor"/>
<path d="M8.82352 0.242615H5.30789C3.0786 0.242615 1.26492 2.39417 1.26492 5.03878V9.66812C1.26492 10.0136 1.50103 10.2937 1.79227 10.2937H2.59109C2.47198 9.89521 2.38968 9.47623 2.34907 9.04253H2.31961C2.31961 8.56225 2.31961 6.01127 2.31961 5.03878C2.31961 3.08407 3.66015 1.49379 5.30789 1.49379H8.82352C10.4713 1.49379 11.8118 3.08407 11.8118 5.03878V9.04253H11.7823C11.7417 9.47623 11.6594 9.89521 11.5403 10.2937H12.3391C12.6304 10.2937 12.8665 10.0136 12.8665 9.66812V5.03878C12.8665 2.39417 11.0528 0.242615 8.82352 0.242615Z" fill="currentColor"/>
<path d="M17.4186 9.45921C17.3014 9.38017 17.1556 9.48077 17.1556 9.64071V11.4198C17.1556 11.6501 16.9982 11.8368 16.8041 11.8368H15.3978C15.2037 11.8368 15.0463 11.6501 15.0463 11.4198V9.64209C15.0463 9.48256 14.9006 9.38051 14.7841 9.46029C13.9951 10.0008 13.4642 11.0121 13.4642 12.1705C13.4642 13.453 14.1152 14.5546 15.0463 15.0374V21.1789C15.0463 21.4093 15.2037 21.596 15.3978 21.596H16.8041C16.9982 21.596 17.1556 21.4093 17.1556 21.1789V15.0374C19.1712 13.9923 19.2566 10.6983 17.4186 9.45921Z" fill="currentColor"/>
<path d="M10.7571 5.08048C10.7571 3.81567 9.8897 2.78667 8.82352 2.78667H5.30789C4.24171 2.78667 3.3743 3.81567 3.3743 5.08048V7.79136H3.93856C4.81089 7.79136 5.52059 6.94945 5.52059 5.9146C5.52059 5.56911 5.7567 5.28901 6.04793 5.28901H10.7571V5.08048Z" fill="currentColor"/>
<path d="M8.68289 14.6728V13.7104C7.6661 14.1489 6.54328 14.16 5.51883 13.7399V14.6728C5.51883 15.7077 6.22853 16.5496 7.10086 16.5496C7.97319 16.5496 8.68289 15.7077 8.68289 14.6728Z" fill="currentColor"/>
<path d="M7.10085 9.04254H10.7193C10.744 8.83814 10.7571 8.62936 10.7571 8.41695V6.54019H6.52228C6.27728 7.96615 5.21187 9.04254 3.93855 9.04254H3.41212C3.66879 11.1619 5.2093 12.7961 7.0657 12.7961C8.5353 12.7961 9.80693 11.7719 10.4004 10.2937H7.10085C6.80962 10.2937 6.57351 10.0136 6.57351 9.66812C6.57351 9.32263 6.80962 9.04254 7.10085 9.04254Z" fill="currentColor"/>
</g>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="translate(0 0.242615)"/>
</clipPath>
<clipPath id="clip1">
<rect width="18" height="21.3534" fill="white" transform="translate(1 0.242615)"/>
</clipPath>
</defs>
</svg>Manage Checklists</a></li>
        
        <li class="link"><a href="{{ route('superadmin.manageSetting') }}"><svg width="18" height="22" viewBox="0 0 18 22" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M16.2 2.32021H15.3V1.25254C15.3 0.611943 14.94 0.184875 14.4 0.184875C13.86 0.184875 13.5 0.611943 13.5 1.25254V2.32021H4.5V1.25254C4.5 0.611943 4.14 0.184875 3.6 0.184875C3.06 0.184875 2.7 0.611943 2.7 1.25254V2.32021H0.9C0.45 2.32021 0 2.74728 0 3.38788V18.3352C0 18.9758 0.45 19.4029 0.9 19.4029H6.39C5.76 18.1217 5.4 16.627 5.4 15.1322C5.4 10.4345 8.64 6.59088 12.6 6.59088C14.31 6.59088 15.84 7.33825 17.1 8.51268V3.38788C17.1 2.85404 16.65 2.32021 16.2 2.32021Z" fill="currentColor"/>
<path d="M12.6 8.72622C9.63 8.72622 7.2 11.6089 7.2 15.1322C7.2 18.6555 9.63 21.5382 12.6 21.5382C15.57 21.5382 18 18.6555 18 15.1322C18 11.6089 15.57 8.72622 12.6 8.72622ZM14.4 16.1999H12.6C12.06 16.1999 11.7 15.7728 11.7 15.1322V11.9292C11.7 11.2886 12.06 10.8616 12.6 10.8616C13.14 10.8616 13.5 11.2886 13.5 11.9292V14.0646H14.4C14.94 14.0646 15.3 14.4916 15.3 15.1322C15.3 15.7728 14.94 16.1999 14.4 16.1999Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="translate(0 0.184875)"/>
</clipPath>
</defs>
</svg>Admin Setting</a></li>
        <li class="link"><a href="{{route('superadminlogout')}}">
<svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M0.327487 8.89696L1.14336 9.75505C1.30747 9.70168 1.47995 9.67265 1.65816 9.67265H14.8723L9.3126 3.82537L8.13383 5.40269L7.34109 4.56893L8.51986 2.99162L6.40387 0.766187C6.2191 0.571836 5.98867 0.476273 5.75922 0.476273C5.49039 0.476273 5.22284 0.607321 5.03086 0.864138L3.80632 2.5027L3.95991 2.66425C4.31261 3.03519 4.52245 3.5469 4.55074 4.10511C4.57903 4.66332 4.4224 5.20117 4.10972 5.61958C3.79699 6.03803 3.36565 6.28693 2.8951 6.32049C2.8589 6.32308 2.82273 6.32434 2.78682 6.32434C2.35533 6.32434 1.94407 6.13971 1.61846 5.79721L1.46491 5.63571L0.244953 7.26815C0.0725762 7.49879 -0.0137359 7.7952 0.00183842 8.10283C0.0174127 8.41047 0.133072 8.69251 0.327487 8.89696ZM6.59811 5.56306L7.39085 6.39686L6.64773 7.3912L5.85499 6.5574L6.59811 5.56306ZM5.10948 7.55497L5.90221 8.38873L5.15909 9.38307L4.36636 8.54931L5.10948 7.55497Z" fill="currentColor"/>
<path d="M17.1637 10.9295H5.36134V12.8875H4.30187V10.9295H1.65805C1.16715 10.9295 0.767731 11.4032 0.767731 11.9856V14.0172H1.10496C2.04073 14.0172 2.80207 14.9203 2.80207 16.0304C2.80207 17.1405 2.04073 18.0437 1.10496 18.0437H0.767731V20.0752C0.767731 20.6576 1.16715 21.1314 1.65805 21.1314H4.30183V19.1734H5.36131V21.1314H17.1636C17.6248 21.1314 17.9999 20.6863 17.9999 20.1394V11.9215C18 11.3745 17.6248 10.9295 17.1637 10.9295ZM5.36134 17.9156H4.30187V16.6595H5.36134V17.9156ZM5.36134 15.3993H4.30187V14.1433H5.36134V15.3993ZM14.9425 16.3336H13.0682L12.0386 18.6019L11.1058 18.0061L11.8649 16.3336H10.3281L9.644 17.4698L8.78187 16.7392L9.36363 15.7731L8.66985 14.95L9.419 14.0613L10.275 15.0767H11.8649L11.1058 13.4042L12.0386 12.8084L13.0682 15.0767H14.9425V16.3336H14.9425Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="translate(0 0.127167)"/>
</clipPath>
</defs>
</svg>Log Out</a></li>
 
        
    </ul>
</div>
</div>
<!-- sidebar end -->

  @yield('content')

<!-- Top bar start -->
  <div class="top-bar">
<div class="left-sidemenu">
<a class="menubar">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path fill-rule="evenodd" d="M1 2.75A.75.75 0 011.75 2h12.5a.75.75 0 110 1.5H1.75A.75.75 0 011 2.75zm0 5A.75.75 0 011.75 7h12.5a.75.75 0 110 1.5H1.75A.75.75 0 011 7.75zM1.75 12a.75.75 0 100 1.5h12.5a.75.75 0 100-1.5H1.75z"></path></svg>
</a>
       <div class="search-1">
        <i class="fa fa-search" aria-hidden="true"></i>
         <input type="search" name="" placeholder="search" class="form-control srch">
       </div>
     </div>
       <div class="flex-new">
         <span class="ned-hlp">Need Help?</span>
@php
  $auth_id = auth()->user()->id;
  $userData =  App\Models\User::select('firstname')->where('id',$auth_id)->first();
@endphp
<div class="dropdown">
  <img src="{{ asset('images/Ellipse-1.png')}}" style="width: 40px;  position: relative;right: 20px;">
  <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="color: #81878F;">
    {{$userData->firstname}}
  </a>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <li><a class="dropdown-item" href="{{ route('superadmin.manageSetting') }}">Setting</a></li>
    <li><a class="dropdown-item" href="{{ route('superadmin.changepassword') }}">Change Password</a></li>
    <li><a class="dropdown-item" href="{{route('superadminlogout')}}">Logout</a></li>
   
  </ul>
</div>
       </div>
     </div>
<!-- Top bar end -->

<!-- Footer start -->
  


</body>
 <script src="{{ asset('js/jquery.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
   <!-- <script src="{{ asset('js/chart.js')}}"></script> -->
   <script src="{{ asset('js/drop-zone.js')}}"></script>
   <script src="{{ asset('js/jquery-ui.js')}}"></script>
   <script src="{{ asset('js/dropify.js')}}"></script>
  
<script src="{{ asset('js/slick.min.js')}}"></script>
<script src="{{ asset('js/main.min.js')}}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="{{ asset('js/add-field.js')}}"></script>
   

   
   
<script>
  $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
          includeSelectAllOption: true,
        });
    });
  $(document).ready(function() {
  var buttonAdd = $("#add-button");
  var buttonRemove = $("#remove-button");
  
  var className = ".dynamic-field";
  var count = 0;
  var field = "";
  var maxFields =50;

  function totalFields() {
    return $(className).length;
  }

  function addNewField() {
    count = totalFields() + 1;
    field = $("#dynamic-field-1").clone();
    field.attr("id", "dynamic-field-" + count);
    field.children("label").text("Field " + count);
    field.find("input").val("");
    $(className + ":last").after($(field));
  }

  function removeLastField() {
    if (totalFields() > 1) {
      $(className + ":last").remove();
    }
  }

  function enableButtonRemove() {
    if (totalFields() === 2) {
      buttonRemove.removeAttr("disabled");
      buttonRemove.addClass("shadow-sm");
    }
  }

  function disableButtonRemove() {
    if (totalFields() === 1) {
      buttonRemove.attr("disabled", "disabled");
      buttonRemove.removeClass("shadow-sm");
    }
  }

  function disableButtonAdd() {
    if (totalFields() === maxFields) {
      buttonAdd.attr("disabled", "disabled");
      buttonAdd.removeClass("shadow-sm");
    }
  }

  function enableButtonAdd() {
    if (totalFields() === (maxFields - 1)) {
      buttonAdd.removeAttr("disabled");
      buttonAdd.addClass("shadow-sm");
    }
  }

  buttonAdd.click(function() {
    addNewField();
    enableButtonRemove();
    disableButtonAdd();
  });

  buttonRemove.click(function() {
    removeLastField();
    disableButtonRemove();
    enableButtonAdd();
  });
});
$(".confirm").click(function() {
  swal("Successfully address", "Well done, you pressed a button", "success")
});
$(".add-ticket-alert").click(function() {
  swal("Success full", "The ticket has been created", "success")
});

$(document).ready(function () {
        var url = window.location;
    
        $('.sidebar ul li a[href="' + url + '"]').parent().addClass('active');

    // Will also work for relative and absolute hrefs
        $('.sidebar ul li a').filter(function () {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');
    
    
    $(".menubar").click(function(){
    $(".sidebar").toggle();
  $("body").toggleClass('leftside-none');
  
  });
    
    });
  
</script>

<script type="">
  $(".circle_percent").each(function() {
    var $this = $(this),
    $dataV = $this.data("percent"),
    $dataDeg = $dataV * 3.6,
    $round = $this.find(".round_per");
  $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)"); 
  $this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
  $this.prop('Counter', 0).animate({Counter: $dataV},
  {
    duration: 2000, 
    easing: 'swing', 
    step: function (now) {
            $this.find(".percent_text").text(Math.ceil(now)+"%");
        }
    });
  if($dataV >= 51){
    $round.css("transform", "rotate(" + 360 + "deg)");
    setTimeout(function(){
      $this.addClass("percent_more");
    },1000);
    setTimeout(function(){
      $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
    },1000);
  } 
});
    </script>

</html>
<!-- Footer end  -->