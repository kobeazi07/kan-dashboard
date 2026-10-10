<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Project SLF PT. KAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            margin-bottom: 32px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
        }

        .division {
            margin-bottom: 32px;
        }

        .division h2 {
            margin-bottom: 16px;
        }

        .documents {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .card {
            padding: 20px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .document-name {
            min-height: 44px;
            font-weight: 600;
            line-height: 1.5;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 20px 0 10px;
            font-size: 14px;
        }

        .progress-track {
            height: 8px;
            overflow: hidden;
            background: #e5e7eb;
            border-radius: 20px;
        }

        .progress-bar {
            height: 100%;
            background: #16a34a;
            border-radius: 20px;
        }

        .view-document {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 14px;
            color: white;
            background: #166534;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }

        .view-document:hover {
            background: #14532d;
        }

        .unavailable {
            color: #9ca3af;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>Dashboard Project SLF PT. KAN</h1>
            <p>Monitoring progres dokumen proyek</p>
        </header>

        @foreach ($data as $division => $documents)
            <section class="division">
                <h2>{{ $division }}</h2>

                <div class="documents">
                    @foreach ($documents as $document)
                        <article class="card" data-division="{{ $division }}"
                            data-document-name="{{ $document['name'] }}">
                            <div class="document-name">
                                {{ $document['name'] }}
                            </div>

                            <div class="progress-info"> <span>Progres</span> <strong
                                    class="progress-value {{ $document['progress'] === null ? 'unavailable' : '' }}"
                                    data-progress-value>
                                    @if ($document['progress'] !== null)
                                        {{ rtrim(rtrim(number_format($document['progress'], 2, '.', ''), '0'), '.') }}%
                                    @else
                                        Belum tersedia
                                    @endif
                                </strong>
                            </div>

                            <div class="progress-track">
                                <div class="progress-bar" data-progress-bar
                                    style="width: {{ max(0, min(100, $document['progress'] ?? 0)) }}%">
                                </div>
                            </div>

                            @if (!empty($document['url']))
                                <a class="view-document" href="{{ $document['url'] }}" target="_blank"
                                    rel="noopener noreferrer">
                                    View Document ↗
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</body>

</html>
