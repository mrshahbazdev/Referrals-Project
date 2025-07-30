<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
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
        .dashboard-layout { display: flex; min-height: 100vh; position: relative; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); transition: transform 0.3s ease-in-out; }
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
        .mobile-nav-toggle { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.5rem; cursor: pointer; }
        .add-btn { background-color: var(--accent-color); color: var(--bg-dark); border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
        .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
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
        .form-group input { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: #334155; color: var(--text-primary); }
        .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
        .btn-secondary { background-color: #475569; color: var(--text-primary); }
        .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
        .btn-danger { background-color: var(--red); color: var(--text-primary); }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
        .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
        .alert-danger { background-color: #991b1b; border-color: var(--red); color: #fee2e2; }
        .alert-danger ul { list-style-position: inside; }
        .mobile-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99; }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 100; }
            .sidebar.active { transform: translateX(0); }
            .mobile-nav-toggle { display: block; }
            .mobile-overlay.active { display: block; }
            .table-container { border: none; background-color: transparent; }
            table thead { display: none; }
            table tr { display: block; margin-bottom: 1rem; border-radius: 12px; border: 1px solid var(--border-color); background-color: var(--card-bg); }
            table td { display: flex; justify-content: space-between; align-items: center; text-align: right; padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-color); }
            table td:last-child { border-bottom: none; }
            table td::before { content: attr(data-label); font-weight: 600; text-align: left; margin-right: 1rem; color: var(--text-primary); }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Add your full sidebar here -->
        <aside class="sidebar"> ... </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Task Management</h1>
                <button class="add-btn" id="addTaskBtn"><i class="ph ph-plus"></i> Add New Task</button>
            </header>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Title</th><th>Reward</th><th>URL</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr>
                                <td data-label="ID">{{ $task->id }}</td>
                                <td data-label="Title">{{ $task->title }}</td>
                                <td data-label="Reward">${{ number_format($task->reward_amount, 2) }}</td>
                                <td data-label="URL"><a href="{{ $task->youtube_url }}" target="_blank" style="color: var(--accent-color);">View Link</a></td>
                                <td data-label="Actions">
                                    <button class="action-btn edit-btn" data-task='@json($task)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                                    <button class="action-btn delete-btn" data-id="{{ $task->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align: center; padding: 2rem;">No tasks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-links">{{ $tasks->links() }}</div>
        </main>
    </div>

    <!-- Add/Edit Task Modal -->
    <div class="modal-overlay" id="taskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Task</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="taskForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group"><label for="title">Task Title</label><input type="text" id="title" name="title" required></div>
                <div class="form-group"><label for="reward_amount">Reward Amount</label><input type="number" step="0.01" id="reward_amount" name="reward_amount" required></div>
                <div class="form-group"><label for="youtube_url">YouTube URL</label><input type="url" id="youtube_url" name="youtube_url" required></div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this task?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const taskModal = document.getElementById('taskModal');
            const deleteModal = document.getElementById('deleteModal');
            const addTaskBtn = document.getElementById('addTaskBtn');
            const closeButtons = document.querySelectorAll('.close-modal-btn');
            const taskForm = document.getElementById('taskForm');
            const deleteForm = document.getElementById('deleteForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            addTaskBtn.addEventListener('click', () => {
                taskForm.reset();
                taskForm.action = "{{ route('admin.tasks.store') }}";
                methodField.innerHTML = '';
                modalTitle.textContent = 'Add New Task';
                taskModal.classList.add('active');
            });

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const task = JSON.parse(button.dataset.task);
                    taskForm.reset();
                    taskForm.action = `/admin/tasks/${task.id}`;
                    methodField.innerHTML = '@method("PUT")';
                    modalTitle.textContent = 'Edit Task';
                    document.getElementById('title').value = task.title;
                    document.getElementById('reward_amount').value = task.reward_amount;
                    document.getElementById('youtube_url').value = task.youtube_url;
                    taskModal.classList.add('active');
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const taskId = button.dataset.id;
                    deleteForm.action = `/admin/tasks/${taskId}`;
                    deleteModal.classList.add('active');
                });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    taskModal.classList.remove('active');
                    deleteModal.classList.remove('active');
                });
            });
        });
    </script>
</body>
</html>
