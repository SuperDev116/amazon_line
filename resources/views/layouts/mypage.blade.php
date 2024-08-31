<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Amazon Track">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AMAZON出品</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/charts-c3/plugin.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}" />
    <!-- Custom Css -->

    <link rel="stylesheet" href="{{ asset('assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" />

    <style>
        .l-slategray_1 {
            background: linear-gradient(0deg, #777, #28a745) !important;
            color: #fff !important;
        }
        
        i {
            cursor: pointer !important;
        }
        
    </style>
</head>

<body class="theme-blush">

    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30">
                <img class="zmdi-hc-spin" src="assets/images/loader.svg" width="48" height="48" alt="Amazon">
            </div>
            <p>少々お待ちください。</p>
        </div>
    </div>

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    <!-- Main Search -->
    <!-- <div id="search">
		<button id="close" type="button" class="close btn btn-primary btn-icon btn-icon-mini btn-round">x</button>
		<form>
			<input type="search" value="" placeholder="検索ワード入力..." id="skeyword" name="keyword" value=""/>
			<button type="button" class="btn btn-primary" onclick="search()">商品検索</button>
		</form>
	</div> -->
    
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <div class="navbar-brand">
            <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
            <!-- <a href="{{ route('list_product') }}"><img src="assets/images/logo.svg" width="25" alt="Amazon"><span -->
            <a href="{{ route('list_product') }}"><img src="assets/images/a.png" width="25" alt="Amazon"><span
                    class="m-l-10">AMAZON</span></a>
        </div>

        <div class="menu">
            <ul class="list">
                <li>
                    <div class="user-info">
                        <!-- <a class="image" href="#"><img src="assets/images/profile_av.jpg" alt="User"></a> -->
                        <div class="detail mt-3 ml-4">
                            <h4>{{ Auth::user()->family_name }}</h4>
                            <p>{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </li>

                <li <?php if(strpos(url()->current(), "register_product")) echo 'class="active open"';?>>
                    <a href="./register_product"><i class="zmdi zmdi-playlist-plus"></i><span>商品登録</span></a>
                </li>

                <li <?php if(strpos(url()->current(), "list_product")) echo 'class="active open"';?>>
                    <a href="./list_product"><i class="zmdi zmdi-shopping-cart"></i><span>商品一覧</span></a>
                </li>

                <li <?php if(strpos(url()->current(), "change_pwd")) echo 'class="active open"';?>>
                    <a href="./change_pwd"><i class="zmdi zmdi-key"></i><span>パスワード変更</span></a>
                </li>

                <li <?php if(strpos(url()->current(), "change_info")) echo 'class="active open"';?>>
                    <a href="./change_info"><i class="zmdi zmdi-account"></i><span>ユーザー情報入力</span></a>
                </li>

                @if ( Auth::user()->role == 'admin')
                    <li <?php if(strpos(url()->current(), "admin_page")) echo 'class="active open"';?>>
                        <a href="{{ route('admin_page') }}"><i class="zmdi zmdi-settings"></i><span>管理者ページ</span></a>
                    </li>

                    <li <?php if(strpos(url()->current(), "notify_page")) echo 'class="active open"';?>>
                        <a href="{{ route('notify_page') }}"><i class="zmdi zmdi-receipt"></i><span>LINEログページ</span></a>
                    </li>
                @endif

                <li class="">
                    <a href="./logout"><i class="zmdi zmdi-power"></i><span>ログアウト</span></a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Right Sidebar -->
    <!-- <div class="navbar-right">
		<ul class="navbar-nav">
			<li><a href="#search" class="main_search" title="商品検索..."><i class="zmdi zmdi-search"></i></a></li> 
			<li><a href="sign-in.html" class="mega-menu" title="商品追加"><i class="zmdi zmdi-assignment"></i></a></li>
			<li><a href="sign-in.html" class="mega-menu" title="CSVダウンロード"><i class="zmdi zmdi-swap-alt"></i></a></li>
			<li><a href="sign-in.html" class="mega-menu" title="ログアウト"><i class="zmdi zmdi-power"></i></a></li> 
		</ul>
	</div> -->

    <div id="toast-container" class="toast-top-right"></div>

    <!-- Main Content -->

    @yield('content')
    <!-- Jquery Core Js -->

    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <!-- slimscroll, waves Scripts Plugin Js -->

    <script src="{{ asset('assets/bundles/sparkline.bundle.js') }}"></script>
    <!-- Sparkline Plugin Js -->
    <script src="{{ asset('assets/bundles/c3.bundle.js') }}"></script>
 
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/index.js') }}"></script>

    <script src="{{ asset('assets/bundles/footable.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/tables/footable.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

    @stack('scripts')
</body>

</html>
