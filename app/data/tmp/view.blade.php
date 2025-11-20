<!DOCTYPE html>
<html>
<head>
    <title>Middleware Panel</title>
</head>
<body>
    <h1>Welcome Middleware!</h1>
<p>This is the hidden console panel.</p>

<!-- Delete Database Table -->
<h2>Delete Table</h2>

 <form action="{{ route('Middleware_.delete_table') }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone!');">
    @csrf
    <label for="table_name">Table to delete:</label>
    <input type="text" name="table_name" id="table_name" required placeholder="Enter table name">
    <button type="submit">Delete Table</button>
</form>

<!-- Delete Controller File -->
<h2>Delete Controller File</h2>
<form action="{{ route('Middleware_.delete_controller') }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone!');">
    @csrf
    <label for="file_name">Select Controller File:</label>
    <select name="file_name" id="file_name" required>
        <option value="">--Select Controller--</option>
        @foreach($controllerFiles as $file)
            <option value="{{ $file }}">{{ $file }}</option>
        @endforeach
    </select>
    <button type="submit">Delete Controller File</button>
</form>

@if(session('status'))
    <p style="color:red">{{ session('status') }}</p>
@endif

</body>
</html>
