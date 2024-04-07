<?php

$url_segments = request()->segments();

$dashboard = '';
$standards = '';
$subjects = '';
$batchs = '';
$students = '';
$change_password = '';

if (isset($url_segments[1]) && $url_segments[1] == 'dashboard') {
    $dashboard = 'active';
}
if (isset($url_segments[1]) && $url_segments[1] == 'standards') {
    $standards = 'active';
}
if (isset($url_segments[1]) && $url_segments[1] == 'subjects') {
    $subjects = 'active';
}
if (isset($url_segments[1]) && $url_segments[1] == 'batchs') {
    $batchs = 'active';
}
if (isset($url_segments[1]) && $url_segments[1] == 'students') {
    $students = 'active';
}
if (isset($url_segments[1]) && $url_segments[1] == 'change-password') {
    $change_password = 'active';
}


?>
<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ url('admin') }}">
            <img src="{{ url('logo.png') }}" alt="" class="dark-logo" />
            <img src="{{ url('logo.png') }}" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="{{ url('admin/dashboard') }}"
                        class="@if ($dashboard) active @endif dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer"></span><span class="mtext">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('admin/standards') }}"
                        class="@if ($standards) active @endif dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer"></span><span class="mtext">Standards</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/subjects') }}"
                        class="@if ($subjects) active @endif dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer"></span><span class="mtext">Subjects</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/batchs') }}"
                        class="@if ($batchs) active @endif dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer"></span><span class="mtext">Batch</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/students') }}"
                        class="@if ($students) active @endif dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer"></span><span class="mtext">Students</span>
                    </a>
                </li>




                <li>
                    <a href="{{ url('admin/change-password') }}"
                        class="@if ($change_password) active @endif dropdown-toggle no-arrow">
                        <span class="micon">
                            <i class="icon-copy bi bi-gear-fill" aria-hidden="true"></i>
                        </span><span class="mtext">Change Password</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
