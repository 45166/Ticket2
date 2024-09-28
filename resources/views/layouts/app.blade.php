<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Tsu</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Google Fonts --><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-image: url('/images/'); /* URL ของภาพพื้นหลังแบบโปร่งใส */
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-color: #f4f4f9; /* สีพื้นหลังรองรับ */
}


        /* Custom Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, #1f2a44, #2c3e50);
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Add shadow to navbar */
            z-index: 1000; /* Ensure the navbar stays on top */
        }

        .navbar-custom .navbar-brand {
            color: #ffffff;
            font-size: 1.4rem;
            font-weight: 500;
        }

        .navbar-custom .navbar-brand:hover {
            color: #b0bec5;
        }

        .navbar-custom .nav-link {
            color: #ffffff;
            margin-left: 20px;
            font-size: 1rem;
            font-weight: 500;
        }

        .navbar-custom .nav-link:hover {
            color: #b0bec5;
        }

        /* Username style */
        .navbar-custom .navbar-text {
            color: #b0bec5;
            font-weight: 500;
            font-size: 1rem;
            margin-left: 15px;
        }

        .navbar-custom img {
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Sidebar */
        .sidebar {
            background: url('https://www.transparenttextures.com/patterns/square.png') repeat, #343a40;
            min-height: 100vh;
            padding-top: 20px;
            background-size: 20px 20px; /* Adjust the size of the pattern */
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15); /* Add shadow to sidebar */
            z-index: 900; /* Ensure the sidebar stays above the main content */
        }

        .sidebar .nav-link {
            color: #cfd8dc;
            font-size: 1rem;
            margin: 10px 0;
            padding: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #ffffff;
            border-radius: 5px;
        }

        .sidebar .logo img {
            max-width: 100px;
            height: auto;
            margin-bottom: 15px;
        }

        .sidebar .nav-item i {
            margin-right: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-custom .nav-link {
                font-size: 0.9rem;
                margin-left: 10px;
            }

            .navbar-custom .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-custom .navbar-text {
                font-size: 0.9rem;
            }

            .sidebar {
                padding-top: 10px;
                box-shadow: none; /* Remove shadow on small screens for better usability */
            }

            .sidebar .nav-link {
                font-size: 0.9rem;
            }
        }

        /* Main content padding */
        .main-content {
            padding: 20px;
        }

        /* Button styling inside the sidebar */
        .sidebar button.nav-link {
            text-align: left;
            padding: 10px;
            width: 100%;
            background: none;
            border: none;
            color: #cfd8dc;
        }

        .sidebar button.nav-link:hover {
            background-color: #495057;
            color: #ffffff;
            border-radius: 5px;
        }
        

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            Development of an E-Ticket System for Computer and Network Equipment Maintenance at Thaksin University
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if (Auth::check())
                    <li class="nav-item">
                        <span class="navbar-text">
                            @if (Auth::user()->role == 0)
                                <img src="{{ asset('images/admin_icon.png') }}" alt="Admin Icon" style="width: 30px; height: 30px;">
                            @elseif (Auth::user()->role == 1)
                                <img src="{{ asset('images/it_icon.png') }}" alt="IT Icon" style="width: 30px; height: 30px;">
                            @elseif (Auth::user()->role == 2)
                                <img src="{{ asset('images/user_icon.png') }}" alt="User Icon" style="width: 30px; height: 30px;">
                            @endif
                            {{ Auth::user()->name }}
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky">
                <!-- Logo -->
                <div class="logo text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>

                <ul class="nav flex-column mt-3">
                    @if(Auth::check())
                    @if(Auth::user()->role == 0)
                        <!-- Admin Sidebar -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin') }}">
                                <i class="bi bi-house"></i> Admin Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('repair_requests.evaluations') }}">
                                <i class="bi bi-boxes"></i> ดูผลแบบประเมิน
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL('fullcalender') }}" class="nav-link">
                                <i class="bi bi-calendar-check"></i> ตารางนัดหมาย
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('devices.index') }}" class="nav-link">
                                <i class="bi bi-plus-circle"></i> เพิ่มประเภทอุปกรณ์
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('buildings.create') }}" class="nav-link">
                                <i class="bi bi-building"></i> เพิ่มอาคาร
                            </a>
                        </li>
                        <!-- เพิ่มลิงก์สำหรับจัดการ Role -->
                        <li class="nav-item">
                            <a href="{{ route('role.form') }}" class="nav-link">
                                <i class="bi bi-people"></i> จัดการ Role
                            </a>
                        </li>
                        @elseif(Auth::user()->role == 1)
                            <!-- IT Sidebar -->
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('it') }}">
                                    <i class="bi bi-house"></i> IT Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('it') }}">
                                    <i class="bi bi-tools"></i> My Assigned Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL('fullcalender') }}" class="nav-link">
                                    <i class="bi bi-calendar-check"></i> ตารางนัดหมาย
                                </a>
                            </li>
                        @elseif(Auth::user()->role == 2)
                            <!-- User Sidebar -->
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house"></i> User Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('repair_requests.create') }}">
                                    <i class="bi bi-tools"></i> แจ้งซ่อม
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL('fullcalender') }}" class="nav-link">
                                    <i class="bi bi-calendar-check"></i> ตารางนัดหมาย
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('repair_requests.search') }}">
                                    <i class="bi bi-search"></i> ค้นหาประวัติการซ่อม
                                </a>
                            </li>
                        @endif
                        
                        <!-- Logout -->
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="nav-link bg-transparent border-0" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            @yield('content')
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>
</html>
