<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

@section('htmlheader')
    @include('layouts.partials.htmlheader')
@show


@section('loadjs')
    @include('layouts.partials.loadjs')
@show
<html lang="en">
<body class="skin-black sidebar-mini sidebar-collapse">
<div id="app" v-cloak>
    <div class="wrapper">

    @include('layouts.partials.mainheader')

    @include('layouts.partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @include('layouts.partials.contentheader')

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('main-content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('layouts.partials.controlsidebar')

    @include('layouts.partials.footer')

</div><!-- ./wrapper -->
</div>
@section('scripts')
    @include('layouts.partials.scripts')
@show

</body>
</html>
