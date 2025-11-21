<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <title>Super Admin Dashboard</title>
</head>
<body>
    <div class="container">
        <div class="header" style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Super Admin Dashboard</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div> 

        <p>Welcome, Super Admin!</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

<form action="{{ route('admin.index') }}" method="GET" style="margin-bottom:20px;">
    <input type="text" name="email" placeholder="Search by email" value="{{ $searchEmail ?? '' }}">
    <button type="submit" class="btn btn-outline-primary">Search</button>
    @if($searchEmail)
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>


        <h2>Manage Users</h2>
        <table class="visitor-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td data-label="ID">{{ $user->id }}</td>
                    <td data-label="Firstname">{{ $user->firstname }}</td>
                    <td data-label="Lastname">{{ $user->lastname }}</td>
                    <td data-label="Name">{{ $user->name }}</td>
                    <td data-label="Username">{{ $user->username }}</td>
                    <td data-label="Email">{{ $user->email }}</td>
                    <td class="action-buttons" data-label="Actions">
                        <button class="btn btn-outline-primary edit-btn" 
                            data-id="{{ $user->id }}"
                            data-firstname="{{ $user->firstname }}"
                            data-lastname="{{ $user->lastname }}"
                            data-name="{{ $user->name }}"
                            data-username="{{ $user->username }}"
                            data-email="{{ $user->email }}">
                            Edit
                        </button>

                        <form action="{{ route('super.delete.user', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit User</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="firstname" placeholder="Firstname" required>
                <input type="text" name="lastname" placeholder="Lastname" required>
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password (leave blank to keep)">
                <button type="submit" class="btn btn-outline-primary">Update</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editModal');
        const closeBtn = modal.querySelector('.close');
        const editForm = document.getElementById('editForm');

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                editForm.action = `/admin/manage-users/${id}`; // must match your PUT route
                editForm.querySelector('[name="firstname"]').value = btn.dataset.firstname;
                editForm.querySelector('[name="lastname"]').value = btn.dataset.lastname;
                editForm.querySelector('[name="name"]').value = btn.dataset.name;
                editForm.querySelector('[name="username"]').value = btn.dataset.username;
                editForm.querySelector('[name="email"]').value = btn.dataset.email;

                modal.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', e => { if(e.target === modal) modal.style.display = 'none'; });
    </script>
</body>
</html>
