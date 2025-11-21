<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>San Beda Portal | Dashboard</title>

  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body> 
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

  <!-- Main Content -->
  <main class="content-area">
    <header class="dashboard-header">
      <h2>Welcome, {{ $user->firstname ?? 'User' }}!</h2>
      <span class="time-text">{{ now()->format('M d, Y | h:i A') }}</span>
    </header>

    <section class="dashboard-container">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <h4 class="section-title">Visitor Log</h4>

      <div class="table-wrapper">
        <table class="visitor-table" id="visitorTable">
          <thead>
            <tr>
              <th>Company</th>
              <th>Visitor</th>
              <th>Purpose</th>
              <th>Contact Person</th>
              <th>Time In</th>
              <th>Time Out</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="visitorTableBody">
            <tr>
              <td colspan="8" class="no-data">Loading visitors...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script>
    const FETCH_VISITORS_URL = "{{ route('fetch.visitors') }}";
    const TIMEOUT_ROUTE = "{{ url('/visitor/timeOut') }}";
    const REJECT_ROUTE = "{{ url('/visitor/reject') }}";
    const EMAIL_ROUTE = "{{ url('/visitor/sendEmail') }}";
  </script>

  <script src="{{ asset('js/dashboard.js') }}"></script>

</body>
</html>
