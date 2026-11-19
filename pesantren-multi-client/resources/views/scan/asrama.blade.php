<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wali Asrama - Scanner</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:480px;margin:0 auto;padding:16px}
        #reader{width:100%}
        #panel{margin-top:12px;padding:16px;border-radius:12px;display:none;font-size:20px;font-weight:600}
        .ok{background:#d1fae5}.err{background:#fee2e2}
        input,select,button,textarea{width:100%;padding:12px;font-size:16px;margin-top:8px;box-sizing:border-box}
        .manual{margin-top:16px;border-top:1px solid #ddd;padding-top:8px}
    </style>
</head>
<body>
    <h2>Wali Asrama</h2>
    <select id="mode">
        <option value="absensi">Absensi asrama</option>
        <option value="izin">Buat izin (sakit / pulang / keluar)</option>
    </select>

    <div id="izinFields" style="display:none">
        <select id="type">
            <option value="sakit">Sakit</option>
            <option value="pulang">Pulang</option>
            <option value="keluar">Keluar</option>
            <option value="lainnya">Lainnya</option>
        </select>
        <input id="reason" placeholder="Alasan">
        <input id="deadline" type="datetime-local" title="Batas kembali (wajib selain sakit)">
    </div>

    <div id="reader" style="margin-top:12px"></div>
    <div class="manual">
        <input id="manual" placeholder="Atau tempel kode kartu di sini">
        <button id="cari">Proses</button>
    </div>
    <div id="panel"></div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    const panel = document.getElementById('panel');
    const mode = document.getElementById('mode');
    let locked = false;
    mode.onchange = () => document.getElementById('izinFields').style.display = mode.value === 'izin' ? 'block' : 'none';
    function show(cls, text){ panel.className = cls; panel.textContent = text; panel.style.display = 'block'; }
    function beep(ok){ try{ const c=new AudioContext(),o=c.createOscillator(); o.frequency.value=ok?880:220; o.connect(c.destination); o.start(); setTimeout(()=>o.stop(),150);}catch(e){} }
    async function onScan(code){
        if (!code || locked) return; locked = true;
        const body = {code};
        let url = '{{ route('asrama.absensi') }}';
        if (mode.value === 'izin') {
            url = '{{ route('asrama.izin') }}';
            body.type = document.getElementById('type').value;
            body.reason = document.getElementById('reason').value;
            body.deadline = document.getElementById('deadline').value || null;
        }
        try {
            const r = await fetch(url, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:JSON.stringify(body)});
            const d = await r.json();
            const msg = d.ok ? d.message + (d.progress ? ' (' + d.progress + ')' : '') : (d.message || 'Gagal.');
            beep(!!d.ok); show(d.ok ? 'ok' : 'err', msg);
        } catch(e){ show('err', 'Koneksi gagal.'); }
        setTimeout(()=>{ locked = false; }, 2500);
    }
    document.getElementById('cari').onclick = () => onScan(document.getElementById('manual').value.trim());
    new Html5Qrcode('reader').start({facingMode:'environment'}, {fps:10, qrbox:250}, onScan)
        .catch(e => show('err', 'Kamera tidak bisa dibuka. Pakai kolom kode.'));
    </script>
</body>
</html>
