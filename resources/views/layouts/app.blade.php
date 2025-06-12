<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Mohammed PhoneTech Brand Colors */
            --primary-color: #2c3e50;      /* Dark Navy */
            --secondary-color: #34495e;     /* Lighter Navy */
            --accent-gold: #f1c40f;         /* Gold */
            --accent-silver: #bdc3c7;      /* Silver */
            --success-color: #27ae60;      /* Green */
            --warning-color: #f39c12;      /* Orange */
            --danger-color: #e74c3c;       /* Red */
            --info-color: #3498db;         /* Blue */
            --dark-color: #1a252f;         /* Very Dark Navy */
            --light-color: #ecf0f1;        /* Light Gray */
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .main-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(241, 196, 15, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(45deg, var(--accent-gold), var(--accent-silver));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(241, 196, 15, 0.1);
            color: var(--accent-gold);
            transform: translateY(-2px);
        }

        /* Notification Bell */
        .notification-container {
            position: relative;
        }

        .notification-bell {
            position: relative;
            background: linear-gradient(45deg, var(--accent-gold), var(--warning-color));
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(241, 196, 15, 0.3);
        }

        .notification-bell:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 6px 20px rgba(241, 196, 15, 0.4);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
            border: 2px solid white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            width: 380px;
            max-height: 500px;
            overflow: hidden;
            z-index: 1000;
            display: none;
            border: 1px solid rgba(241, 196, 15, 0.2);
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes slideDown {
            from { 
                opacity: 0; 
                transform: translateY(-20px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        .notification-header {
            padding: 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .notification-item:hover {
            background: #f8fafc;
            transform: translateX(5px);
        }

        .notification-item.unread {
            background: linear-gradient(90deg, rgba(241, 196, 15, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
            border-right: 4px solid var(--accent-gold);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 4px;
            font-size: 0.9rem;
        }

        .notification-message {
            color: #64748b;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-time {
            color: #94a3b8;
            font-size: 0.7rem;
        }

        .notification-footer {
            padding: 15px 20px;
            background: #f8fafc;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        /* Sidebar Styles */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            position: fixed;
            top: var(--navbar-height);
            right: 0;
            z-index: 1020;
            border-left: 1px solid rgba(241, 196, 15, 0.2);
            box-shadow: -8px 0 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(241, 196, 15, 0.3);
            border-radius: 3px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-section-title {
            padding: 0 20px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-link {
            color: var(--primary-color);
            padding: 12px 20px;
            margin: 2px 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--accent-gold), var(--warning-color));
            transition: all 0.3s ease;
            z-index: -1;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            right: 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 8px 25px rgba(241, 196, 15, 0.3);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-left: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-top: var(--navbar-height);
            margin-right: var(--sidebar-width);
            padding: 30px;
            min-height: calc(100vh - var(--navbar-height));
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px 0 0 0;
            position: relative;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 0 15px 35px rgba(44, 62, 80, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 100%;
            height: 100%;
            background: rgba(241, 196, 15, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(44, 62, 80, 0.4);
        }

        .stats-card:hover::before {
            top: -30%;
            left: -30%;
        }

        .stats-card.gold {
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--warning-color) 100%);
            box-shadow: 0 15px 35px rgba(241, 196, 15, 0.3);
        }

        .stats-card.silver {
            background: linear-gradient(135deg, var(--accent-silver) 0%, #95a5a6 100%);
            box-shadow: 0 15px 35px rgba(189, 195, 199, 0.3);
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #2ecc71 100%);
            box-shadow: 0 15px 35px rgba(39, 174, 96, 0.3);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .stats-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2.5rem;
            opacity: 0.3;
        }

        /* Buttons */
        .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: all 0.3s ease;
            transform: translate(50%, -50%);
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(44, 62, 80, 0.4);
        }

        .btn-gold {
            background: linear-gradient(45deg, var(--accent-gold), var(--warning-color));
            color: white;
            box-shadow: 0 8px 25px rgba(241, 196, 15, 0.3);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(241, 196, 15, 0.4);
            color: white;
        }

        /* Tables */
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(241, 196, 15, 0.05);
            transform: scale(1.01);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Chart Containers */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .notification-dropdown {
                width: 320px;
                left: -50px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, var(--accent-gold), var(--warning-color));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-gold);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-cube ms-2"></i>
                محمد فون تك - نظام إدارة المنصات
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home ms-1"></i>
                            الرئيسية
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Notifications -->
                    <li class="nav-item notification-container">
                        <button class="notification-bell" id="notificationBell">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h6>الإشعارات</h6>
                                <button class="btn btn-sm btn-light" id="markAllRead">
                                    <i class="fas fa-check-double ms-1"></i>
                                    تحديد الكل
                                </button>
                            </div>
                            <div class="notification-list" id="notificationList">
                                <!-- سيتم تحميل الإشعارات هنا -->
                            </div>
                            <div class="notification-footer">
                                <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-list ms-1"></i>
                                    عرض جميع الإشعارات
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle ms-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user ms-2"></i>الملف الشخصي
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt ms-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <!-- Dashboard Section -->
            <div class="nav-section">
                <div class="nav-section-title">لوحة التحكم</div>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    الرئيسية
                </a>
            </div>

            @can('access meym')
            <!-- Meym Platform -->
            <div class="nav-section">
                <div class="nav-section-title">منصة ميم</div>
                <a class="nav-link {{ request()->routeIs('meym.*') ? 'active' : '' }}" href="{{ route('meym.campaigns.index') }}">
                    <i class="fas fa-bullhorn"></i>
                    إدارة الحملات
                </a>
            </div>
            @endcan

            @can('access phonetech')
            <!-- PhoneTech Platform -->
            <div class="nav-section">
                <div class="nav-section-title">محمد فون تك</div>
                <a class="nav-link {{ request()->routeIs('phonetech.products.*') ? 'active' : '' }}" href="{{ route('phonetech.products.index') }}">
                    <i class="fas fa-mobile-alt"></i>
                    إدارة المنتجات
                </a>
                <a class="nav-link {{ request()->routeIs('phonetech.sales.*') ? 'active' : '' }}" href="{{ route('phonetech.sales.index') }}">
                    <i class="fas fa-receipt"></i>
                    نظام المبيعات
                </a>
                <a class="nav-link {{ request()->routeIs('phonetech.installments.*') ? 'active' : '' }}" href="{{ route('phonetech.installments.index') }}">
                    <i class="fas fa-credit-card"></i>
                    نظام التقسيط
                </a>
            </div>
            @endcan

            @can('access carimport')
            <!-- Car Import Platform -->
            <div class="nav-section">
                <div class="nav-section-title"> شركة الميل</div>
                <a class="nav-link {{ request()->routeIs('carimport.*') ? 'active' : '' }}" href="{{ route('carimport.imports.index') }}">
                    <i class="fas fa-car"></i>
                    إدارة الاستيراد
                </a>
            </div>
            @endcan

            @can('access admin')
            <!-- Admin Section -->
            <div class="nav-section">
                <div class="nav-section-title">الإدارة العامة</div>
                <a class="nav-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}" href="{{ route('admin.accounts.index') }}">
                    <i class="fas fa-calculator"></i>
                    نظام الحسابات
                </a>
                <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    إدارة المواعيد
                </a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    إدارة المستخدمين
                </a>
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-cog"></i>
                    إدارة الأدوار
                </a>
            </div>
            @endcan

            @can('view reports')
            <!-- Reports Section -->
            <div class="nav-section">
                <div class="nav-section-title">التقارير والبيانات</div>
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    التقارير والإحصائيات
                </a>
                <a class="nav-link {{ request()->routeIs('exports.*') ? 'active' : '' }}" href="{{ route('exports.index') }}">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </a>
            </div>
            @endcan

            @can('access admin')
            <!-- System Section -->
            <div class="nav-section">
                <div class="nav-section-title">النظام والأمان</div>
                <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    الإشعارات
                </a>
                <a class="nav-link {{ request()->routeIs('security.*') ? 'active' : '' }}" href="{{ route('security.index') }}">
                    <i class="fas fa-shield-alt"></i>
                    نظام الأمان
                </a>
            </div>
            @endcan
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                <i class="fas fa-check-circle ms-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                <i class="fas fa-exclamation-circle ms-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notification System -->
    <script>
        class NotificationSystem {
            constructor() {
                this.bell = document.getElementById('notificationBell');
                this.badge = document.getElementById('notificationBadge');
                this.dropdown = document.getElementById('notificationDropdown');
                this.list = document.getElementById('notificationList');
                this.markAllBtn = document.getElementById('markAllRead');
                
                this.sounds = {
                    notification: '/sounds/notification.mp3',
                    reminder: '/sounds/reminder.mp3',
                    update: '/sounds/update.mp3',
                    delete: '/sounds/delete.mp3'
                };
                
                this.init();
            }
            
            init() {
                this.loadNotifications();
                setInterval(() => this.loadNotifications(), 30000);
                
                this.bell.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown();
                });
                
                this.markAllBtn.addEventListener('click', () => this.markAllAsRead());
                
                document.addEventListener('click', () => this.closeDropdown());
                this.dropdown.addEventListener('click', (e) => e.stopPropagation());
            }
            
            async loadNotifications() {
                try {
                    const response = await fetch('/notifications/unread', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.updateBadge(data.count);
                        this.renderNotifications(data.notifications);
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                }
            }
            
            updateBadge(count) {
                if (count > 0) {
                    this.badge.textContent = count > 99 ? '99+' : count;
                    this.badge.style.display = 'flex';
                } else {
                    this.badge.style.display = 'none';
                }
            }
            
            renderNotifications(notifications) {
                if (notifications.length === 0) {
                    this.list.innerHTML = `
                        <div class="notification-item text-center text-muted py-4">
                            <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                            <p class="mb-0">لا توجد إشعارات جديدة</p>
                        </div>
                    `;
                    return;
                }
                
                this.list.innerHTML = notifications.map(notification => `
                    <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                         data-id="${notification.id}" onclick="notificationSystem.markAsRead(${notification.id})">
                        <div class="notification-icon bg-${this.getNotificationColor(notification.type)}">
                            <i class="${this.getNotificationIcon(notification.type)} text-white"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${this.formatTime(notification.created_at)}</div>
                        </div>
                    </div>
                `).join('');
            }
            
            getNotificationColor(type) {
                const colors = {
                    'appointment': 'primary',
                    'sale': 'success',
                    'campaign': 'warning',
                    'car_import': 'info',
                    'security': 'danger',
                    'system': 'secondary'
                };
                return colors[type] || 'primary';
            }
            
            getNotificationIcon(type) {
                const icons = {
                    'appointment': 'fas fa-calendar',
                    'sale': 'fas fa-shopping-cart',
                    'campaign': 'fas fa-bullhorn',
                    'car_import': 'fas fa-car',
                    'security': 'fas fa-shield-alt',
                    'system': 'fas fa-cog'
                };
                return icons[type] || 'fas fa-bell';
            }
            
            toggleDropdown() {
                this.dropdown.classList.toggle('show');
            }
            
            closeDropdown() {
                this.dropdown.classList.remove('show');
            }
            
            async markAsRead(id) {
                try {
                    const response = await fetch(`/notifications/mark-as-read/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }
            
            async markAllAsRead() {
                try {
                    const response = await fetch('/notifications/mark-all-as-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            }
            
            playSound(soundName) {
                try {
                    const audio = new Audio(this.sounds[soundName] || this.sounds.notification);
                    audio.volume = 0.5;
                    audio.play().catch(e => console.log('Could not play notification sound:', e));
                } catch (error) {
                    console.log('Sound not available:', error);
                }
            }
            
            formatTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = now - date;
                
                if (diff < 60000) return 'الآن';
                if (diff < 3600000) return `${Math.floor(diff / 60000)} دقيقة`;
                if (diff < 86400000) return `${Math.floor(diff / 3600000)} ساعة`;
                return date.toLocaleDateString('ar-SA');
            }
        }
        
        // Initialize notification system
        document.addEventListener('DOMContentLoaded', function() {
            window.notificationSystem = new NotificationSystem();
        });

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
