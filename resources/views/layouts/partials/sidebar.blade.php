<?php
$active_ext_tab = \Request::segment(2);
$active_tab     = \Request::segment(1);
?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <a href="{{ url('/home') }}" class="logo">

        </a>
        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
               
                <div class="pull-left info">
                   
                </div>
            </div>
        @endif

        

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class=" treeview menu-open"></li>

            <li class="@if($active_tab == 'user_home') active @endif"><a href="{{ route('user_home') }}"><i class='fa fa-user'></i><span>Users</span></a></li>

            <li class="@if($active_tab == 'vehicle_home') active @endif"><a href="{{ route('vehicle_home') }}"><i class='fa fa-car'></i><span>Vehicle</span></a></li>

            <li class="@if($active_tab == 'user_checker_home') active @endif"><a href="{{ route('user_checker_home') }}"><i class='fa fa-check-circle'></i><span>Assign Checker</span></a></li>

            <li class="@if($active_tab == 'toilet_home') active @endif"><a href="{{ route('toilet_home') }}"><i class='fa fa-recycle'></i><span>Toilet</span></a></li>

            <li class="@if($active_tab == 'assign_toilet_home') active @endif"><a href="{{ route('assign_toilet_home') }}"><i class='fa fa-window-restore'></i><span>Assign Toilet</span></a></li>

            <li class="@if($active_tab == 'report_home') active @endif"><a href="{{ route('report_home') }}"><i class='fa fa-file-archive-o'></i><span>Cleaning Report</span></a></li>

            <li class="@if($active_tab == 'expense_home') active @endif"><a href="{{ route('expense_home') }}"><i class='fa fa-file-archive-o'></i><span>Expense Report</span></a></li>

            <li class="@if($active_tab == 'logout') active @endif">

                <a href="{{ url('/logout') }}" id="logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class='fa fa-sign-out'></i><span>Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="submit" value="logout" style="display: none;">
                </form>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
