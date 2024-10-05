<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring Pakan Ayam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Tambahkan link Font Awesome -->
    <style>
        body {
            background-image: url('/assets/images/ayam.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .blur-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            filter: blur(5px);
            z-index: 1;
        }

        .dark-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .transparent-bg {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .clock-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .schedule {
            font-size: 1.2rem;
            color: white;
        }

        .clock {
            font-size: 2rem;
            font-weight: bold;
            background-color: rgba(248, 249, 250, 0.8);
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // Fungsi untuk mengubah status relay menggunakan AJAX
        function toggleRelay(status) {
            let relayStatus = status ? 'on' : 'off';
            fetch('/toggle-relay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ relayStatus: relayStatus })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('relay-status-text').innerText = data.message;
                document.getElementById('relay-status-display').classList.toggle('text-success', data.relayStatus === 'on');
                document.getElementById('relay-status-display').classList.toggle('text-danger', data.relayStatus === 'off');
            })
            .catch(error => console.error('Error:', error));
        }

        // Event listener untuk switch toggle
        function handleToggle(event) {
            toggleRelay(event.target.checked);
        }

        // Fungsi untuk memperbarui jam
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('clock').textContent = timeString;
        }

        // Memperbarui jam setiap detik
        setInterval(updateClock, 1000);
        window.onload = updateClock;
    </script>
</head>
<body>
    <div class="blur-bg"></div>
    <div class="dark-overlay"></div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Pakan Ayam</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">Profil</a>
                        </li>
                    </ul>
                    <form action="{{ route('logout') }}" method="POST" class="d-flex">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="fas fa-sign-out-alt"></i> Logout <!-- Ganti dengan ikon logout -->
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <h2 class="text-center text-white">
                <img src="assets/images/ayamlogo.png" alt="Logo" style="height: 150px; margin-right: 10px;"> <!-- Sesuaikan dengan path dan ukuran logo -->
                Dashboard Sistem Monitoring Pakan Ayam
            </h2>

            <div class="card mt-4 transparent-bg">
                <div class="card-header">
                    Status Relay
                </div>
                <div class="card-body">
                    <h5 class="card-title">Status: 
                        <span id="relay-status-display" class="{{ $isRelayActive ? 'text-success' : 'text-danger' }}">
                            {{ $isRelayActive ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </h5>
                    <p id="relay-status-text">{{ $isRelayActive ? 'Relay sedang aktif memberikan pakan.' : 'Relay tidak aktif saat ini.' }}</p>

                    <label class="switch">
                        <input type="checkbox" id="relay-toggle" {{ $isRelayActive ? 'checked' : '' }} onchange="handleToggle(event)">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="clock-container">
                <div class="schedule">
                    <h4>Jadwal Pemberian Makan:</h4>
                    <ul>
                        <li>Pukul 08:00 pagi</li>
                        <li>Pukul 16:00 sore</li>
                    </ul>
                </div>
                <div class="clock" id="clock">00:00:00</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
