<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir Kantin</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:480px;margin:0 auto;padding:16px}
        #reader{width:100%}
        #panel{margin-top:12px;padding:16px;border-radius:12px;display:none;font-size:18px}
        .ok{background:#d1fae5}.err{background:#fee2e2}
        input,button{width:100%;padding:12px;font-size:18px;margin-top:8px;box-sizing:border-box}
        .manual{margin-top:16px;border-top:1px solid #ddd;padding-top:8px}
    </style>
</head>
<body>
    <h2>Kasir Kantin</h2>
    <div id="reader"></div>

    <div class="manual">
        <input id="manual" placeholder="Atau tempel kode kartu di sini">
        <button id="cari">Cari santri</button>
    </div>

    <div id="panel"></div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    const panel = document.getElementById('panel');
    const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
    let walletId = null, locked = false;

    function show(cls, html){ panel.className = cls; panel.innerHTML = html; panel.style.display = 'block'; }
    function beep(ok){ try{ const c=new AudioContext(),o=c.createOscillator(); o.frequency.value=ok?880:220; o.connect(c.destination); o.start(); setTimeout(()=>o.stop(),150);}catch(e){} }
    async function post(url, body){
        const r = await fetch(url, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:JSON.stringify(body)});
        return r.json();
    }

    async function onScan(code){
        if (!code || locked) return; locked = true;
        const d = await post('{{ route('kasir.lookup') }}', {code});
        if (!d.ok){ beep(false); show('err', d.message || 'Gagal.'); setTimeout(()=>{locked=false;panel.style.display='none'}, 2500); return; }
        beep(true); walletId = d.wallet_id;
        show('ok', `<b>${d.name}</b><br>Saldo: ${rp(d.balance)}
            <input id="amt" type="number" placeholder="Total belanja (Rp)" inputmode="numeric">
            <button id="pay">Bayar</button>`);
        document.getElementById('pay').onclick = bayar;
    }

    async function bayar(){
        const btn = document.getElementById('pay'); btn.disabled = true;
        const amount = parseInt(document.getElementById('amt').value, 10);
        const d = await post('{{ route('kasir.bayar') }}', {wallet_id: walletId, amount, key: crypto.randomUUID()});
        beep(d.ok);
        show(d.ok ? 'ok' : 'err', d.ok ? `Berhasil. Sisa saldo ${rp(d.balance)}` : (d.message || 'Gagal.'));
        setTimeout(()=>{ locked = false; panel.style.display='none'; }, 3000);
    }

    document.getElementById('cari').onclick = () => onScan(document.getElementById('manual').value.trim());

    new Html5Qrcode('reader').start({facingMode:'environment'}, {fps:10, qrbox:250}, onScan)
        .catch(e => show('err', 'Kamera tidak bisa dibuka: ' + e + '<br>Pakai kolom kode di bawah.'));
    </script>
</body>
</html>