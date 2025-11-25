<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visitor Stats | San Beda Portal</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="main-container">
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

  <div class="content-area">
    <header class="dashboard-header">
      <h2>Visitor Statistics</h2>
      <span class="time-text">{{ now()->format('M d, Y | h:i A') }}</span>
    </header>
<div class="stats-grid">

   
    <a href="{{ route('dashboard.history') }}" class="stat-card total stat-link">
    <h5>Total Visitors</h5>
    <h2>{{ $totalVisitors }}</h2>
</a>


    
    <a href="{{ url('/dashboard') }}" class="stat-card inside stat-link">
    <h5>Currently Inside</h5>
    <h2>{{ $currentlyInside }}</h2>
</a>


</div>

   <div class="charts-grid">
    
    <div class="chart-card">
        <h5>Visitors by Purpose</h5>

        @php
    $chartLabels = $visitorsByPurpose->pluck('purpose_category');
    $chartData = $visitorsByPurpose->pluck('count');
@endphp

<canvas
    id="visitorsByPurpose"
    data-labels='@json($chartLabels)'
    data-data='@json($chartData)'>
</canvas>

    </div>
</div>


      
      <div class="chart-card">
        <h5>Visitors by Hour</h5>
        <canvas
            id="visitorsByHour"
            data-labels='@json($visitorsByHour->pluck("hour_label"))'
            data-data='@json($visitorsByHour->pluck("count"))'>
        </canvas>
      </div>

    </div>
  </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/visitor-refresh.js') }}"></script>

</body>
</html>
