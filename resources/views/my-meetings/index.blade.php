<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Saya</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 2rem;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #5a5a5a;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .meeting-list {
            list-style: none;
            padding: 0;
        }

        .meeting-item {
            background: #fafafa;
            border: 1px solid #ddd;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .meeting-details {
            flex-grow: 1;
        }

        .meeting-details h3 {
            margin: 0 0 0.5rem 0;
        }

        .meeting-details p {
            margin: 0.25rem 0;
            color: #666;
            font-size: 0.9em;
        }

        .join-button {
            background-color: #007bff;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            white-space: nowrap;
            margin-top: 1rem;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .join-button:hover {
            background-color: #0056b3;
        }

        .join-button.disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            pointer-events: none;
        }

        .no-meetings {
            text-align: center;
            padding: 2rem;
            color: #777;
        }

        .zoom-connect-alert {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            border-radius: .25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .zoom-connect-button {
            background-color: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .meeting-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .join-button {
                width: 100%;
                text-align: center;
            }

            .zoom-connect-alert {
                flex-direction: column;
                text-align: center;
            }

            .zoom-connect-button {
                margin-top: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Jadwal Meeting Saya</h1>

        @if (auth()->user()->hasRole('student') && !$isZoomConnected)
            <div class="zoom-connect-alert">
                <span>Untuk bergabung ke meeting, Anda harus menghubungkan akun Zoom Anda terlebih dahulu.</span>
                <a href="{{ route('zoom.redirect') }}" class="zoom-connect-button">Hubungkan Akun Zoom</a>
            </div>
        @endif

        @forelse($meetings as $meeting)
            <div class="meeting-item">
                <div class="meeting-details">
                    <h3>{{ $meeting->topic }}</h3>
                    <p><strong>Mata Pelajaran:</strong> {{ $meeting->topic->chapter->subject->name }}</p>
                    <p><strong>Guru:</strong> {{ $meeting->topic->chapter->subject->teacher->name }}</p>
                    <p><strong>Jadwal:</strong> {{ $meeting->start_time->isoFormat('dddd, D MMMM YYYY, HH:mm') }}
                        (Durasi: {{ $meeting->duration }} menit)
                    </p>
                </div>
                <a href="{{ route('meetings.join', $meeting) }}"
                    class="join-button {{ !$isZoomConnected && auth()->user()->hasRole('student') ? 'disabled' : '' }}"
                    @if (!$isZoomConnected && auth()->user()->hasRole('student')) onclick="event.preventDefault(); alert('Silakan hubungkan akun Zoom Anda terlebih dahulu.');" @endif>
                    Gabung Meeting
                </a>
            </div>
        @empty
            <div class="no-meetings">
                <p>Tidak ada jadwal meeting yang ditemukan.</p>
            </div>
        @endforelse
    </div>
</body>

</html>
