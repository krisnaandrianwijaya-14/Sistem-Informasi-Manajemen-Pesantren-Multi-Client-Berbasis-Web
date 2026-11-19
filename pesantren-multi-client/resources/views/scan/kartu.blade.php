<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu {{ $student->full_name }}</title>
    <style>
        body{font-family:system-ui,sans-serif;display:flex;justify-content:center;padding:24px}
        .kartu{border:2px solid #111;border-radius:16px;padding:20px;text-align:center;width:320px}
        img{width:260px;height:260px}
    </style>
</head>
<body>
    <div class="kartu">
        <h3>{{ $student->full_name }}</h3>
        <p>NIS: {{ $student->nis }}</p>
        <img src="{{ $qr }}" alt="QR">
    </div>
</body>
</html>