<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <title>Generate Access Codes | San Beda Portal</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="main-container">
    <!-- ✅ Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
        
            <img src="{{ asset('src/Sanbeda-logo.png') }}" alt="SBCA Logo" class="sidebar-logo">
            <h4>SBCA GATEPASS</h4>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('dashboard.stats') }}" class="{{ request()->is('dashboard/stats') ? 'active' : '' }}">📊 Stats</a></li>
                <li><a href="{{ route('dashboard.history') }}" class="{{ request()->is('dashboard/history') ? 'active' : '' }}">📜 History</a></li>
                <li><a href="{{ route('accesscode.index') }}" class="{{ request()->is('dashboard/generate-code') ? 'active' : '' }}">🔐 Access</a></li>
            </ul>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-container">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
        @include('layouts.footer')
    </aside>

    <!-- ✅ Main Content -->
    <div class="content-area" id="codes-page">
        
        <header class="dashboard-header">
            <h2>Generate Access Codes</h2>
            <span class="time-text">{{ now()->timezone('Asia/Manila')->format('M d, Y | h:i A') }}</span>
        </header>

        <!-- ✅ Alert message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- ✅ Generate new code button -->
        <form action="{{ route('accesscode.generate') }}" method="POST" style="margin-bottom: 20px;">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Generate New Code</button>
        </form>

   <div class="table-container">
    <table class="visitor-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($codes as $c)
                <tr>
                    <td>{{ $c->code }}</td>

                    <td>
                        @if($c->is_used)
                            <span class="status timed-out">Used</span>
                        @else
                            <span class="status inside">Active</span>
                        @endif
                    </td>

                    <td>{{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}</td>

                    <td style="text-align:center;">
                        <form action="{{ route('accesscode.delete', $c->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-outline-danger btn-delete-small"
                                    onclick="return confirm('Delete this code?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="4" class="no-data">No codes yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



<script src="{{ asset('js/dashboard.js') }}"></script>


</body>
</html>
