<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clinic Appointment Scheduling System">
    <title>@yield('title', 'Clinic Appointment System')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('extra_css')
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <i class="fas fa-hospital" style="color: var(--primary-color);"></i>
                <span style="letter-spacing: -0.5px;">Clinic Appointment System</span>
            </div>
            <ul class="nav-menu">
                <li><a href="{{ url('index.php') }}" class="{{ isCurrentPage('index.php') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a></li>
                <li><a href="{{ url('patients/index.php') }}" class="{{ isCurrentPage('patients/') ? 'active' : '' }}">
                    <i class="fas fa-user-injured"></i> Patients
                </a></li>
                <li><a href="{{ url('doctors/index.php') }}" class="{{ isCurrentPage('doctors/') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i> Doctors
                </a></li>
                <li><a href="{{ url('appointments/index.php') }}" class="{{ isCurrentPage('appointments/') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Appointments
                </a></li>
            </ul>
            <div class="user-menu">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ getCurrentUser()['full_name'] ?? 'User' }}</span>
                    <span class="user-role">{{ strtoupper(getCurrentUser()['role'] ?? '') }}</span>
                </div>
                <a href="{{ url('auth/logout.php') }}" class="btn-logout" title="Logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    @if(isset($flash))
    <div class="alert alert-{{ $flash['type'] }}">
        <div class="container">
            <i class="fas fa-{{ $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-circle' : 'info-circle') }}"></i>
            {{ $flash['message'] }}
        </div>
    </div>
    @endif

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Clinic Appointment System. All rights reserved.</p>
        </div>
    </footer>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger-color); border-bottom: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Confirm Deletion</h2>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/form-validator.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        function openDeleteModal(url, message) {
            document.getElementById('deleteModalMessage').textContent = message;
            document.getElementById('confirmDeleteBtn').href = url;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
    @yield('extra_js')
</body>
</html>
