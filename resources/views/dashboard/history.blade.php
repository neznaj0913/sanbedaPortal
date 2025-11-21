<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor History | San Beda Portal</title>
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

    <div class="content-area" id="history-page">
        <header class="dashboard-header">
            <h2>Visitor History</h2>
            <span class="time-text">{{ now()->timezone('Asia/Manila')->format('M d, Y | h:i A') }}</span>
        </header>

        <form method="GET" action="{{ route('dashboard.history') }}" id="filterForm" class="filter-form">
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search visitor..." class="filter-input">

                <select name="status" class="filter-select">
                    <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="Inside" {{ ($status ?? '') === 'Inside' ? 'selected' : '' }}>Inside</option>
                    <option value="Timed Out" {{ ($status ?? '') === 'Timed Out' ? 'selected' : '' }}>Timed Out</option>
                </select>

                <input type="date" name="from_date" value="{{ $from_date ?? '' }}" class="filter-input">
                <input type="date" name="to_date" value="{{ $to_date ?? '' }}" class="filter-input">

                <button type="submit" class="action-btn email-btn">Apply Filters</button>
                <button type="button" onclick="clearFilters()" class="action-btn" style="background-color:#6c757d;">Clear</button>

                <button type="submit" formaction="{{ route('export.visitors') }}" class="action-btn timeout-btn">Export Excel</button>
                <button type="submit" formaction="{{ route('export.visitors') }}" name="today" value="1" class="action-btn timeout-btn" style="background-color:#ffc107; color:#333;">Export Today</button>
            </div>
        </form>

        <div class="table-container">
            <table class="visitor-table" id="visitorTable">
                <thead>
                    <tr>
                        <th>Pass No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Purpose</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->gatepass_no }}</td>
                            <td>{{ $visitor->first_name }} {{ $visitor->last_name }}</td>
                            <td>{{ $visitor->department }}</td>
                            <td>{{ $visitor->purpose }}</td>
                            <td>{{ $visitor->time_in ? \Carbon\Carbon::parse($visitor->time_in)->timezone('Asia/Manila')->format('m/d/Y, g:i A') : '—' }}</td>
                            <td>{{ $visitor->time_out ? \Carbon\Carbon::parse($visitor->time_out)->timezone('Asia/Manila')->format('m/d/Y, g:i A') : '—' }}</td>
                            <td>
                                @php
                                    $statusClass = strtolower(str_replace(' ', '-', $visitor->status));
                                @endphp
                                <span class="status {{ $statusClass }}">{{ $visitor->status ?? 'Pending' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="no-data">No visitors found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($visitors->hasPages())
            <div class="pagination-container">
                <nav>
                    @if ($visitors->onFirstPage())
                        <span class="disabled arrow">‹</span>
                    @else
                        <a href="{{ $visitors->previousPageUrl() }}" class="arrow">‹</a>
                    @endif

                    @foreach ($visitors->getUrlRange(1, $visitors->lastPage()) as $page => $url)
                        @if ($page == $visitors->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($visitors->hasMorePages())
                        <a href="{{ $visitors->nextPageUrl() }}" class="arrow">›</a>
                    @else
                        <span class="disabled arrow">›</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>

<script>
function clearFilters() {
    window.location.href = "{{ route('dashboard.history') }}";
}

document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    }

    const historyPage = document.getElementById('history-page');
    if (historyPage && document.getElementById('visitorTable')) {
        const script = document.createElement('script');
        script.src = "{{ asset('js/visitor-refresh.js') }}";
        document.body.appendChild(script);
    }
});
</script>

<script src="{{ asset('js/dashboard.js') }}"></script>


</body>
</html>
