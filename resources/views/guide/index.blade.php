@extends('layouts.app')

@section('title', 'Panduan Penggunaan')
@section('page-title', '📖 Panduan Penggunaan Platform')
@section('page-subtitle', 'Buku Panduan Lengkap & SOP Eksekusi Trading CryptoSignal Scanner')

@section('topbar-actions')
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">🏠 Dashboard</a>
    <a href="{{ route('signals.index') }}" class="btn btn-primary">⚡ Lihat Sinyal Active</a>
@endsection

@section('content')

{{-- Quick Navigation Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-bottom:24px">
    <a href="#bab-1" style="background:var(--bg-700);border:1px solid var(--border);border-radius:10px;padding:14px;text-decoration:none;transition:transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size:24px;margin-bottom:6px">🚀</div>
        <div style="font-weight:700;font-size:14px;color:var(--text-primary)">BAB 1: Pengenalan</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Arsitektur Bot & Indikator</div>
    </a>
    <a href="#bab-2" style="background:var(--bg-700);border:1px solid var(--border);border-radius:10px;padding:14px;text-decoration:none;transition:transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size:24px;margin-bottom:6px">⚡</div>
        <div style="font-weight:700;font-size:14px;color:var(--text-primary)">BAB 2: Anatomi Sinyal</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">VIP Tier, Score & Target</div>
    </a>
    <a href="#bab-3" style="background:var(--bg-700);border:1px solid rgba(0,212,160,0.4);border-radius:10px;padding:14px;text-decoration:none;transition:transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size:24px;margin-bottom:6px">🎯</div>
        <div style="font-weight:700;font-size:14px;color:var(--green)">BAB 3: SOP Eksekusi Order</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Entry Zone & Risk Calculator</div>
    </a>
    <a href="#bab-4" style="background:var(--bg-700);border:1px solid var(--border);border-radius:10px;padding:14px;text-decoration:none;transition:transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size:24px;margin-bottom:6px">📊</div>
        <div style="font-size:14px;font-weight:700;color:var(--text-primary)">BAB 4: 15M Scalping</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Multi-Timeframe Matrix</div>
    </a>
    <a href="#bab-5" style="background:var(--bg-700);border:1px solid var(--border);border-radius:10px;padding:14px;text-decoration:none;transition:transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size:24px;margin-bottom:6px">📅</div>
        <div style="font-weight:700;font-size:14px;color:var(--text-primary)">BAB 5: Kalender Makro</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">CPI, FOMC & AI Sentiment</div>
    </a>
</div>

{{-- BAB 1 --}}
<div id="bab-1" class="card mb-4" style="background:var(--bg-700);border:1px solid var(--border)">
    <div class="card-header">
        <div class="card-title">🚀 BAB 1: Pengenalan Platform CryptoSignal Scanner</div>
    </div>
    <div class="card-body" style="line-height:1.7;color:var(--text-secondary)">
        <p>
            <strong>CryptoSignal Scanner Bot</strong> adalah sistem otomatisasi analitik dan pemantauan pasar kripto real-time. Platform ini memindai puluhan pasangan aset kripto di Binance Futures & Spot secara terus-menerus untuk menemukan peluang trading probabilitas tinggi.
        </p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:16px">
            <div style="background:var(--bg-800);padding:14px;border-radius:8px;border:1px solid var(--border)">
                <strong style="color:var(--text-primary);display:block;margin-bottom:4px">📊 Multi-Indikator Presisi</strong>
                Menggabungkan EMA9/21, MA20/50, RSI (14), Stochastic, MACD (12,26,9), Bollinger Bands, ATR Volatilitas, dan Open Interest.
            </div>
            <div style="background:var(--bg-800);padding:14px;border-radius:8px;border:1px solid var(--border)">
                <strong style="color:var(--text-primary);display:block;margin-bottom:4px">🌐 Multi-Timeframe Alignment</strong>
                Menilai keselarasan tren antara timeframe mikro 15M, timeframe utama 1H, dan tren makro 4H & 1D.
            </div>
            <div style="background:var(--bg-800);padding:14px;border-radius:8px;border:1px solid var(--border)">
                <strong style="color:var(--text-primary);display:block;margin-bottom:4px">🤖 Integration & Alerts</strong>
                Terhubung dengan notifikasi otomatis Telegram Bot & Konsol Eksekusi Order Sekali Klik (*One-Click Trading*).
            </div>
        </div>
    </div>
</div>

{{-- BAB 2 --}}
<div id="bab-2" class="card mb-4" style="background:var(--bg-700);border:1px solid var(--border)">
    <div class="card-header">
        <div class="card-title">⚡ BAB 2: Anatomi Sinyal Trading (VIP vs Standard & Confidence Score)</div>
    </div>
    <div class="card-body" style="line-height:1.7;color:var(--text-secondary)">
        <p>Setiap sinyal yang dihasilkan oleh AI Scanner dilengkapi dengan indikator kualitas sebagai berikut:</p>
        
        <div style="margin-top:14px">
            <h4 style="color:var(--text-primary);margin-bottom:8px">1. Klasifikasi Tier Sinyal:</h4>
            <ul style="padding-left:20px;margin-bottom:16px">
                <li><span class="badge badge-purple" style="font-weight:700">⭐ VIP PRO TIER</span>: Sinyal dengan konfirmasi indikator sangat tinggi (Skor $\ge 5$). Memiliki probabilitas sukses paling optimal dengan dukungan konfirmasi volume & tren multi-timeframe.</li>
                <li><span class="badge badge-blue" style="font-weight:700">STANDARD TIER</span>: Sinyal trading standar yang memenuhi syarat minimal konfirmasi indikator teknikal (Skor 2 - 4).</li>
            </ul>

            <h4 style="color:var(--text-primary);margin-bottom:8px">2. Skor Keyakinan (Confidence Score %):</h4>
            <p>Skor 0 - 100% yang dihitung dari akumulasi poin indikator teknikal:</p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px">
                <span class="badge badge-pass">Score $\ge 70\%$ : Sangat Kuat (Hijau)</span>
                <span class="badge badge-pending">Score $50 - 69\%$ : Moderat (Kuning)</span>
                <span class="badge badge-short">Score $< 50\%$ : Hati-hati (Merah)</span>
            </div>
        </div>
    </div>
</div>

{{-- BAB 3 --}}
<div id="bab-3" class="card mb-4" style="background:var(--bg-700);border:1px solid rgba(0,212,160,0.4)">
    <div class="card-header">
        <div class="card-title">🎯 BAB 3: SOP Eksekusi Order (Panduan 3 Detik Bagi Trader)</div>
        <span class="badge badge-pass">Paling Penting!</span>
    </div>
    <div class="card-body" style="line-height:1.7;color:var(--text-secondary)">
        <p>Ketika Anda menerima notifikasi sinyal di Telegram atau melihat sinyal aktif di Dashboard, ikuti **SOP 3 Langkah** berikut:</p>

        <div style="background:var(--bg-800);padding:16px;border-radius:10px;border-left:4px solid var(--green);margin:16px 0">
            <h4 style="color:var(--green);margin-top:0">Langkah 1: Cek Badge Status Entry Zone</h4>
            <p style="margin-bottom:8px">Buka halaman detail sinyal atau halaman koin. Perhatikan badge **Entry Zone Advice**:</p>
            <ul style="padding-left:20px;margin:0">
                <li><strong style="color:var(--green)">IN ENTRY ZONE (Hijau)</strong>: Harga running masih di area Entry $\rightarrow$ <strong>Aman masuk langsung (Market/Limit Order)</strong>.</li>
                <li><strong style="color:var(--blue)">PULLBACK / DISCOUNT (Biru)</strong>: Harga sedang mengalami koreksi di bawah Entry (untuk LONG) $\rightarrow$ <strong>Kesempatan Emas! Risk-Reward jauh lebih bagus</strong>.</li>
                <li><strong style="color:var(--yellow)">CHASING / OUT OF RANGE (Kuning/Merah)</strong>: Harga sudah melesat jauh naik mendekati TP1 $\rightarrow$ <strong>DILARANG Market Buy! Pasang Limit Order di harga Entry awal atau Skip Trade.</strong></li>
            </ul>
        </div>

        <div style="background:var(--bg-800);padding:16px;border-radius:10px;border-left:4px solid var(--blue);margin:16px 0">
            <h4 style="color:var(--blue);margin-top:0">Langkah 2: Gunakan Kalkulator Ukuran Posisi (Position Size Calculator)</h4>
            <p style="margin-bottom:8px">Masukkan modal portofolio Anda (misal $1,000) dan batas risiko per trade (misal 1%). Kalkulator akan otomatis menampilkan:</p>
            <ul style="padding-left:20px;margin:0">
                <li><strong>Max Loss ($)</strong>: Batas maksimum kerugian jika menyentuh Stop Loss.</li>
                <li><strong>Required Margin ($)</strong>: Uang jaminan yang dipakai di exchange.</li>
                <li><strong>Contract Quantity</strong>: Jumlah persis token/koin yang dimasukkan di Binance/Bybit (misal: <code>1,420.5 JUP</code>).</li>
            </ul>
        </div>

        <div style="background:var(--bg-800);padding:16px;border-radius:10px;border-left:4px solid var(--purple);margin:16px 0">
            <h4 style="color:var(--purple);margin-top:0">Langkah 3: Terapkan Protokol Bebas Risiko (Risk-Free Protocol)</h4>
            <p style="margin:0">
                🛡️ <strong>Aturan Emas:</strong> Begitu harga menyentuh <strong>Take Profit 1 (TP1)</strong>, segera buka aplikasi Binance/Bybit Anda dan **geser nilai Stop Loss Anda tepat ke titik harga Entry**. Dengan begitu, trade Anda menjadi <strong>100% Bebas Risiko (Risk-Free)</strong>!
            </p>
        </div>
    </div>
</div>

{{-- BAB 4 --}}
<div id="bab-4" class="card mb-4" style="background:var(--bg-700);border:1px solid var(--border)">
    <div class="card-header">
        <div class="card-title">📊 BAB 4: Fitur 15M Scalping & Multi-Timeframe Matrix</div>
    </div>
    <div class="card-body" style="line-height:1.7;color:var(--text-secondary)">
        <p>
            Pada menu <strong>15M Scalper</strong>, sistem khusus memindai momentum cepat pada timeframe 15 Menit. Matrix Multi-Timeframe membantu Anda menghindari jebakan *Fakeout*:
        </p>
        <ul style="padding-left:20px">
            <li><strong>15M Micro Momentum</strong>: Menunjukkan arah cepat 15 menit saat ini.</li>
            <li><strong>1H Primary Trend</strong>: Menunjukkan arah tren utama 1 jam.</li>
            <li><strong>4H & 1D Macro Structure</strong>: Menunjukkan struktur tren jangka menengah & panjang.</li>
        </ul>
        <div style="background:rgba(255,183,3,0.12);border:1px solid rgba(255,183,3,0.4);padding:12px 16px;border-radius:8px;font-size:12px;color:var(--yellow);margin-top:12px">
            💡 <strong>Tips Alignment:</strong> Perdagangan terbaik adalah saat momentum 15M searah dengan tren 1H dan 4H (diberi penanda 🟢 <strong>ULTRA-HIGH PROBABILITY</strong>).
        </div>
    </div>
</div>

{{-- BAB 5 --}}
<div id="bab-5" class="card mb-4" style="background:var(--bg-700);border:1px solid var(--border)">
    <div class="card-header">
        <div class="card-title">📅 BAB 5: Macro Economic Calendar & Volatility Alerts</div>
    </div>
    <div class="card-body" style="line-height:1.7;color:var(--text-secondary)">
        <p>
            Komponen ini memantau secara otomatis 3 rilis berita makro ekonomi AS terbesar yang paling berdampak pada volatilitas Bitcoin (BTC) & Altcoins:
        </p>
        <ol style="padding-left:20px">
            <li>🔥 <strong>US CPI Inflation Rate (YoY)</strong> (Rilis setiap pertengahan bulan jam 19:30 WIB) $\rightarrow$ Memicu lonjakan volatilitas ekstrim pada BTC/ETH.</li>
            <li>🏛️ <strong>FOMC Rate Decision & Press Conf</strong> (Keputusan suku bunga acuan The Fed) $\rightarrow$ Katalis utama pergerakan arah tren makro pasar.</li>
            <li>💼 <strong>US Non-Farm Payrolls (NFP)</strong> (Rilis Jumat pertama tiap bulan jam 19:30 WIB) $\rightarrow$ Berdampak pada indeks DXY dan pasar kripto.</li>
        </ol>
    </div>
</div>

{{-- FAQ --}}
<div class="card mb-4" style="background:var(--bg-700);border:1px solid var(--border)">
    <div class="card-header">
        <div class="card-title">❓ Pertanyaan Sering Diajukan (FAQ)</div>
    </div>
    <div class="card-body" style="line-height:1.7">
        <details style="margin-bottom:12px;background:var(--bg-800);padding:12px 16px;border-radius:8px;border:1px solid var(--border)">
            <summary style="font-weight:700;color:var(--text-primary);cursor:pointer">Apakah sinyal ini pasti 100% win rate?</summary>
            <p style="margin-top:8px;color:var(--text-secondary);font-size:13px">
                Tidak ada sistem trading yang 100% selalu benar di pasar finansial. Sinyal yang dihasilkan dirancang menggunakan manajemen risiko ATR dan rasio Risk-to-Reward $\ge 1:1.5$. Selalu gunakan manajemen risiko dan jangan mempertaruhkan lebih dari 1-2% modal Anda per trade.
            </p>
        </details>
        <details style="margin-bottom:12px;background:var(--bg-800);padding:12px 16px;border-radius:8px;border:1px solid var(--border)">
            <summary style="font-weight:700;color:var(--text-primary);cursor:pointer">Bagaimana jika notifikasi Telegram tidak masuk?</summary>
            <p style="margin-top:8px;color:var(--text-secondary);font-size:13px">
                Pastikan Bot Token dan Chat ID sudah dikonfigurasi dengan benar di menu <a href="{{ route('settings.index') }}" style="color:var(--green)">Settings</a>, lalu klik tombol "Test Telegram Alert".
            </p>
        </details>
    </div>
</div>

@endsection
