<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-primary); }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
        .sidebar-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2.5rem; }
        .sidebar-header .icon { font-size: 2rem; color: var(--accent-color); }
        .sidebar-header h2 { font-size: 1.25rem; font-weight: 700; }
        .sidebar-nav { flex-grow: 1; }
        .sidebar-nav ul { list-style: none; }
        .sidebar-nav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-weight: 500; transition: background-color 0.2s, color 0.2s; }
        .sidebar-nav a:hover { background-color: #334155; color: var(--text-primary); }
        .sidebar-nav a.active { background-color: var(--accent-color); color: var(--bg-dark); font-weight: 600; }
        .sidebar-nav a i { font-size: 1.25rem; }
        .logout-form button { width: 100%; background: none; border: none; cursor: pointer; text-align: left; }
        .logout-link { color: var(--text-secondary) !important; }
        .logout-link:hover { color: var(--text-primary) !important; }
        .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .main-header h1 { font-size: 1.75rem; }
        .add-user-btn { background-color: var(--accent-color); color: var(--bg-dark); border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
        .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: #334155; font-weight: 600; }
        td { color: var(--text-secondary); }
        tr:last-child td { border-bottom: none; }
        .action-btn { background: none; border: none; cursor: pointer; padding: 0.5rem; border-radius: 6px; transition: background-color 0.2s; }
        .action-btn:hover { background-color: #475569; }
        .action-btn i { font-size: 1.25rem; }
        .pagination-links { margin-top: 1.5rem; color: var(--text-secondary); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background-color: var(--card-bg); padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px; transform: scale(0.95); transition: transform 0.3s; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .modal-header h2 { font-size: 1.25rem; }
        .close-modal-btn { background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-primary); }
        .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
        .btn-secondary { background-color: #475569; color: var(--text-primary); }
        .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
        .btn-danger { background-color: var(--red); color: var(--text-primary); }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
        .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
        .alert-danger { background-color: #991b1b; border-color: var(--red); color: #fee2e2; }
        .alert-danger ul { list-style-position: inside; }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="ph-bold ph-shield-check icon"></i>
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}"><i class="ph ph-gauge"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}" class="active"><i class="ph ph-users"></i> User Management</a></li>
                    <li><a href="{{ route('admin.activity_logs.index') }}"><i class="ph ph-list-dashes"></i> Activity Log</a></li>
                    <!-- Add other links here -->
                </ul>
            </nav>
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit"><a class="logout-link"><i class="ph ph-sign-out"></i> Logout</a></button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>User Management</h1>
                <button class="add-user-btn" id="addUserBtn"><i class="ph ph-plus"></i> Add New User</button>
            </header>

            <!-- Session Messages and Validation Errors -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Username</th><th>Email</th><th>Balance</th><th>Level</th><th>Joined</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>${{ number_format($user->balance, 2) }}</td>
                                <td>{{ $user->level->name ?? 'N/A' }}</td>
                                <td>{{ $user->created_at->format('d M, Y') }}</td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit User" data-user='@json($user)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                                    <button class="action-btn delete-btn" title="Delete User" data-id="{{ $user->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align: center; padding: 2rem;">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-links">{{ $users->links() }}</div>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New User</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group"><label for="username">Username</label><input type="text" id="username" name="username" required></div>
                <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                <div class="form-group"><label for="password">Password (leave blank to keep unchanged)</label><input type="password" id="password" name="password"></div>
                <div class="form-group"><label for="balance">Balance</label><input type="number" step="0.01" id="balance" name="balance" required></div>
                <div class="form-group">
                    <label for="level_id">Level</label>
                    <select id="level_id" name="level_id" required>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userModal = document.getElementById('userModal');
            const deleteModal = document.getElementById('deleteModal');
            const addUserBtn = document.getElementById('addUserBtn');
            const closeButtons = document.querySelectorAll('.close-modal-btn');
            const userForm = document.getElementById('userForm');
            const deleteForm = document.getElementById('deleteForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            // Open Add User Modal
            addUserBtn.addEventListener('click', () => {
                userForm.reset();
                userForm.action = "{{ route('admin.users.store') }}";
                methodField.innerHTML = ''; // No method spoofing for POST
                modalTitle.textContent = 'Add New User';
                userModal.classList.add('active');
            });

            // Open Edit User Modal
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const user = JSON.parse(button.dataset.user);
                    userForm.reset();
                    userForm.action = `/admin/users/${user.id}`;
                    methodField.innerHTML = '@method("PUT")'; // Method spoofing for PUT
                    modalTitle.textContent = 'Edit User';

                    document.getElementById('username').value = user.username;
                    document.getElementById('email').value = user.email;
                    document.getElementById('balance').value = user.balance;
                    document.getElementById('level_id').value = user.level_id;

                    userModal.classList.add('active');
                });
            });

            // Open Delete Confirmation Modal
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const userId = button.dataset.id;
                    deleteForm.action = `/admin/users/${userId}`;
                    deleteModal.classList.add('active');
                });
            });

            // Close Modals
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    userModal.classList.remove('active');
                    deleteModal.classList.remove('active');
                });
            });
        });
    </script>
</body>
</html>
