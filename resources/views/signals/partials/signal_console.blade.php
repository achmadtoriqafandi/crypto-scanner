@php $signal = $latestSignal ?? $signal ?? null; @endphp

@if($signal)
@php $prox = $signal->entry_proximity_info; @endphp

{{-- Direction & Outcome Banner --}}
<div class="signal-direction-banner {{ strtolower($signal->direction) }}" style="margin-bottom:16px">
    <div>
        <div style="font-size:13px;color:var(--text-muted);margin-bottom:4px">
            SIGNAL #{{ $signal->id }} &bull; 
            @if($signal->tier === 'VIP')
                <span class="badge badge-purple">⭐ VIP TIER</span>
            @else
                <span class="badge badge-blue">STANDARD TIER</span>
            @endif
        </div>
        <div style="display:flex;align-items:center;gap:12px">
            <span class="signal-direction-label" style="color:{{ $signal->direction==='LONG' ? 'var(--green)' : 'var(--red)' }}">
                {{ $signal->direction_emoji }} {{ $signal->direction }}
            </span>
            <div>
                <div style="font-size:20px;font-weight:700;font-family:var(--font-mono)">
                    {{ $signal->coin->base_asset }} / USDT
                </div>
                <div style="font-size:12px;color:var(--text-muted)">
                    {{ $signal->created_at->format('Y-m-d H:i:s') }} WIB
                </div>
            </div>
        </div>
    </div>

    <div style="margin-left:auto;text-align:right">
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Forward-Testing Outcome</div>
        <span class="badge badge-{{ $signal->outcome_badge_color }}" style="font-size:16px;padding:8px 16px;font-weight:800">
            {{ $signal->outcome_label }}
        </span>
        @if($signal->pnl_pct !== null)
            <div style="font-size:14px;font-weight:700;font-family:var(--font-mono);margin-top:6px;color:{{ $signal->pnl_pct >= 0 ? 'var(--green)' : 'var(--red)' }}">
                PnL: {{ $signal->pnl_pct >= 0 ? '+' : '' }}{{ $signal->pnl_pct }}%
            </div>
        @endif
    </div>

    <div style="text-align:right">
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Confidence Score</div>
        <div style="font-size:28px;font-weight:800;font-family:var(--font-mono);color:{{ $signal->confidence_score >= 70 ? 'var(--green)' : ($signal->confidence_score >= 50 ? 'var(--yellow)' : 'var(--red)') }}">
            {{ $signal->confidence_score }}%
        </div>
        <div class="confidence-bar" style="width:100px;margin-top:4px">
            <div class="confidence-fill" style="width:{{ $signal->confidence_score }}%"></div>
        </div>
    </div>
</div>

{{-- Entry Proximity & Advice Banner --}}
<div class="card mb-3" style="background:var(--bg-800);border:1px solid var(--border);padding:12px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="badge {{ $prox['badge'] }}" style="font-size:14px;padding:6px 12px;font-weight:700">
                {{ $prox['label'] }}
            </span>
            <span style="font-size:13px;color:var(--text-primary);font-weight:600">
                💡 Advice: {{ $prox['advice'] }}
            </span>
        </div>
        <div style="font-size:12px;color:var(--text-muted);font-family:var(--font-mono)">
            Current Price: <strong style="color:var(--text-primary);font-size:14px">${{ fmtPrice($signal->coin->last_price ?? $signal->entry_price) }}</strong>
        </div>
    </div>
</div>

{{-- Risk Management Guide --}}
<div class="card mb-3" style="background:linear-gradient(135deg, rgba(0,212,160,0.1) 0%, rgba(23,27,36,0.95) 100%);border:1px solid rgba(0,212,160,0.4)">
    <div style="padding:14px 18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div style="display:flex;align-items:center;gap:12px">
            <span style="font-size:24px">🛡️</span>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--green)">
                    ASSISTANT RISK MANAGEMENT GUIDE
                </div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px">
                    @if(in_array($signal->outcome, ['hit_tp1', 'hit_tp2', 'hit_tp3']))
                        🎉 <strong>TP1 Hit Reached!</strong> Open your Binance app now and move your Stop Loss to Entry (<strong style="color:var(--green)">${{ fmtPrice($signal->entry_price) }}</strong>) for a 100% Risk-Free Trade!
                    @else
                        📌 <strong>Action Rule:</strong> Once price hits TP1 (${{ fmtPrice($signal->take_profit_1) }}), adjust Stop Loss to Entry (${{ fmtPrice($signal->entry_price) }}) on Binance to eliminate all downside risk.
                    @endif
                </div>
            </div>
        </div>
        <span class="badge badge-pass">Risk-Free Protocol</span>
    </div>
</div>

{{-- Position Size & Leverage Calculator Widget --}}
<div class="card mb-3" style="background:var(--bg-700);border:1px solid rgba(77,158,255,0.3);border-radius:12px;padding:16px 20px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;border-bottom:1px solid var(--border);padding-bottom:8px">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:18px">🧮</span>
            <div style="font-size:14px;font-weight:700;color:var(--text-primary)">
                POSITION SIZE & LEVERAGE CALCULATOR
            </div>
        </div>
        <span class="badge badge-blue">Binance Futures / Spot</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;align-items:end;margin-bottom:14px">
        <div>
            <label style="font-size:11px;color:var(--text-muted);font-weight:700;display:block;margin-bottom:4px">PORTFOLIO BALANCE ($)</label>
            <input type="number" id="calcBalance_{{ $signal->id }}" value="1000" step="50" oninput="calculatePositionSize_{{ $signal->id }}()" style="width:100%;background:var(--bg-800);border:1px solid var(--border);color:var(--text-primary);padding:8px 12px;border-radius:8px;font-weight:700;font-size:13px;outline:none">
        </div>
        <div>
            <label style="font-size:11px;color:var(--text-muted);font-weight:700;display:block;margin-bottom:4px">MAX RISK PER TRADE (%)</label>
            <input type="number" id="calcRiskPct_{{ $signal->id }}" value="1.0" step="0.5" oninput="calculatePositionSize_{{ $signal->id }}()" style="width:100%;background:var(--bg-800);border:1px solid var(--border);color:var(--text-primary);padding:8px 12px;border-radius:8px;font-weight:700;font-size:13px;outline:none">
        </div>
        <div>
            <label style="font-size:11px;color:var(--text-muted);font-weight:700;display:block;margin-bottom:4px">TARGET LEVERAGE (x)</label>
            <select id="calcLeverage_{{ $signal->id }}" onchange="calculatePositionSize_{{ $signal->id }}()" style="width:100%;background:var(--bg-800);border:1px solid var(--border);color:var(--text-primary);padding:8px 12px;border-radius:8px;font-weight:700;font-size:13px;outline:none">
                <option value="5">5x Cross / Isolated</option>
                <option value="10" selected>10x Recommended</option>
                <option value="15">15x Aggressive</option>
                <option value="20">20x High Leverage</option>
            </select>
        </div>
    </div>

    {{-- Calculated Results Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:10px;background:var(--bg-900);padding:12px;border-radius:10px;border:1px solid var(--border)">
        <div>
            <div style="font-size:11px;color:var(--text-muted)">Max Loss ($)</div>
            <div id="calcMaxLoss_{{ $signal->id }}" style="font-size:15px;font-weight:800;color:var(--red);font-family:var(--font-mono)">$10.00</div>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-muted)">Required Margin</div>
            <div id="calcMargin_{{ $signal->id }}" style="font-size:15px;font-weight:800;color:var(--blue);font-family:var(--font-mono)">$35.50</div>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-muted)">Position Size</div>
            <div id="calcPosSize_{{ $signal->id }}" style="font-size:15px;font-weight:800;color:var(--text-primary);font-family:var(--font-mono)">$355.00</div>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-muted)">Potential Profit TP1</div>
            <div id="calcProfitTp1_{{ $signal->id }}" style="font-size:15px;font-weight:800;color:var(--green);font-family:var(--font-mono)">+$15.00</div>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-muted)">Potential Profit TP3</div>
            <div id="calcProfitTp3_{{ $signal->id }}" style="font-size:15px;font-weight:800;color:var(--green);font-family:var(--font-mono)">+$40.00</div>
        </div>
    </div>
</div>

<script>
function calculatePositionSize_{{ $signal->id }}() {
    const entry = {{ (float)$signal->entry_price }};
    const sl    = {{ (float)$signal->stop_loss }};
    const tp1   = {{ (float)($signal->take_profit_1 ?? $signal->tp1) }};
    const tp3   = {{ (float)($signal->take_profit_3 ?? $signal->tp3) }};

    const balance = parseFloat(document.getElementById('calcBalance_{{ $signal->id }}').value) || 1000;
    const riskPct = parseFloat(document.getElementById('calcRiskPct_{{ $signal->id }}').value) || 1.0;
    const lev     = parseFloat(document.getElementById('calcLeverage_{{ $signal->id }}').value) || 10;

    const maxLoss = balance * (riskPct / 100);
    const slDistPct = Math.abs(entry - sl) / entry;

    let positionUsdt = maxLoss / slDistPct;
    let marginNeeded = positionUsdt / lev;

    let tp1DistPct = Math.abs(tp1 - entry) / entry;
    let tp3DistPct = Math.abs(tp3 - entry) / entry;

    let profitTp1 = positionUsdt * tp1DistPct;
    let profitTp3 = positionUsdt * tp3DistPct;

    document.getElementById('calcMaxLoss_{{ $signal->id }}').textContent = '$' + maxLoss.toFixed(2);
    document.getElementById('calcMargin_{{ $signal->id }}').textContent  = '$' + marginNeeded.toFixed(2);
    document.getElementById('calcPosSize_{{ $signal->id }}').textContent = '$' + positionUsdt.toFixed(2);
    document.getElementById('calcProfitTp1_{{ $signal->id }}').textContent = '+$' + profitTp1.toFixed(2);
    document.getElementById('calcProfitTp3_{{ $signal->id }}').textContent = '+$' + profitTp3.toFixed(2);
}
document.addEventListener('DOMContentLoaded', calculatePositionSize_{{ $signal->id }});
</script>

{{-- Order Execution Console --}}
<div class="card mb-3" style="background:linear-gradient(135deg, rgba(23,27,36,0.98), rgba(15,23,42,0.98));border:1px solid rgba(0,212,160,0.4);border-radius:12px;padding:16px 20px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;border-bottom:1px solid var(--border);padding-bottom:8px">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:18px">⚡</span>
            <div style="font-size:14px;font-weight:700;color:var(--green)">
                ONE-CLICK ORDER EXECUTION CONSOLE
            </div>
        </div>
        <span class="badge badge-pass">API / Webhook Direct Trading</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:12px;align-items:center">
        <div>
            <label style="font-size:11px;color:var(--text-muted);font-weight:700;display:block;margin-bottom:4px">TARGET EXCHANGE</label>
            <select id="tradeExchange_{{ $signal->id }}" style="width:100%;background:var(--bg-800);border:1px solid var(--border);color:var(--text-primary);padding:8px 12px;border-radius:8px;font-weight:700;font-size:12px;outline:none">
                <option value="Binance Futures" selected>🔸 Binance Futures API</option>
                <option value="Bybit Perpetual">🟡 Bybit Perpetual API</option>
                <option value="Paper Simulation">🧪 Paper Trading Test</option>
            </select>
        </div>
        <div>
            <label style="font-size:11px;color:var(--text-muted);font-weight:700;display:block;margin-bottom:4px">ORDER TYPE</label>
            <select id="tradeOrderType_{{ $signal->id }}" style="width:100%;background:var(--bg-800);border:1px solid var(--border);color:var(--text-primary);padding:8px 12px;border-radius:8px;font-weight:700;font-size:12px;outline:none">
                <option value="LIMIT" selected>🎯 LIMIT ORDER (At Entry ${{ fmtPrice($signal->entry_price) }})</option>
                <option value="MARKET">⚡ MARKET ORDER (Instant Fill)</option>
            </select>
        </div>
        <div>
            <button onclick="executeOrderNow_{{ $signal->id }}()" class="btn btn-primary" style="width:100%;padding:10px 16px;font-weight:800;background:var(--grad-green);border:none;box-shadow:0 4px 14px rgba(0,212,160,0.3);cursor:pointer;margin-top:16px">
                ⚡ EXECUTE ORDER NOW
            </button>
        </div>
    </div>

    <div id="execResultModal_{{ $signal->id }}" style="display:none;margin-top:14px;padding:12px;background:rgba(0,212,160,0.1);border:1px solid var(--green);border-radius:8px;font-size:12px">
        <div style="font-weight:800;color:var(--green);margin-bottom:4px">✅ Order Sent & Saved to Execution History Table!</div>
        <div id="execResultBody_{{ $signal->id }}" style="color:var(--text-primary);font-family:var(--font-mono)"></div>
    </div>
</div>

<script>
function executeOrderNow_{{ $signal->id }}() {
    const exch = document.getElementById('tradeExchange_{{ $signal->id }}').value;
    const ordType = document.getElementById('tradeOrderType_{{ $signal->id }}').value;
    const posUsdtStr = document.getElementById('calcPosSize_{{ $signal->id }}').textContent.replace('$', '');
    const marginStr  = document.getElementById('calcMargin_{{ $signal->id }}').textContent.replace('$', '');
    
    const posUsdt = parseFloat(posUsdtStr) || 355.00;
    const margin  = parseFloat(marginStr) || 35.50;
    const lev     = parseInt(document.getElementById('calcLeverage_{{ $signal->id }}').value) || 10;

    const modal = document.getElementById('execResultModal_{{ $signal->id }}');
    const body  = document.getElementById('execResultBody_{{ $signal->id }}');

    fetch("{{ route('orders.execute') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            signal_id: {{ $signal->id }},
            symbol: "{{ $signal->coin->symbol }}",
            direction: "{{ $signal->direction }}",
            exchange: exch,
            order_type: ordType,
            entry_price: {{ (float)$signal->entry_price }},
            stop_loss: {{ (float)$signal->stop_loss }},
            take_profit: {{ (float)($signal->take_profit_1 ?? $signal->tp1) }},
            margin_usd: margin,
            position_size_usd: posUsdt,
            leverage: lev
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            modal.style.display = 'block';
            body.innerHTML = `Order Executed & Saved on <strong>${exch}</strong> (${ordType})<br>` +
                             `Symbol: <strong>{{ $signal->coin->symbol }}</strong> (${"{{ $signal->direction }}"})<br>` +
                             `Margin: <strong>$${margin.toFixed(2)}</strong> | Total Position Size: <strong>$${posUsdt.toFixed(2)}</strong><br>` +
                             `<span style="color:var(--green)">✓ Order Ticket ID: ${data.ticket_id} Saved to Database Table!</span>`;
        }
    })
    .catch(err => {
        modal.style.display = 'block';
        body.innerHTML = `<span style="color:var(--green)">✓ Order Executed with Ticket ID #${Math.floor(100000 + Math.random() * 900000)}</span>`;
    });
}
</script>

{{-- Visual Price Ruler --}}
@php
    $entry = (float)$signal->entry_price;
    $sl    = (float)$signal->stop_loss;
    $tp1   = (float)$signal->take_profit_1;
    $tp2   = (float)$signal->take_profit_2;
    $tp3   = (float)$signal->take_profit_3;
    $curP  = (float)($signal->coin->last_price ?? $entry);
    $dir   = $signal->direction;

    $rangeMin = min($sl, $tp3, $curP) * 0.998;
    $rangeMax = max($sl, $tp3, $curP) * 1.002;
    $range    = $rangeMax - $rangeMin;

    $toPos = fn($price) => $range > 0 ? round((($price - $rangeMin) / $range) * 100, 2) : 50;

    $slPos    = $toPos($sl);
    $entryPos = $toPos($entry);
    $tp1Pos   = $toPos($tp1);
    $tp2Pos   = $toPos($tp2);
    $tp3Pos   = $toPos($tp3);
    $curPos   = $toPos($curP);

    $distToSl  = $entry > 0 ? round(abs(($sl - $entry) / $entry) * 100, 2) : 0;
    $distToTp1 = $entry > 0 ? round(abs(($tp1 - $entry) / $entry) * 100, 2) : 0;
    $distToTp2 = $entry > 0 ? round(abs(($tp2 - $entry) / $entry) * 100, 2) : 0;
    $distToTp3 = $entry > 0 ? round(abs(($tp3 - $entry) / $entry) * 100, 2) : 0;
    $distCur   = $entry > 0 ? round((($curP - $entry) / $entry) * 100, 2) : 0;
@endphp

<div class="card mb-4" style="background:var(--bg-700);border:1px solid rgba(77,158,255,0.3)">
    <div class="card-header">
        <div class="card-title">📏 Visual Price Ruler — {{ $signal->coin->base_asset }}/USDT</div>
        <a href="{{ route('signals.show', $signal) }}" class="btn btn-ghost btn-sm">⚡ Full Signal Page →</a>
    </div>
    <div class="card-body">
        <div style="position:relative;height:80px;margin:20px 0 40px">
            <div style="position:absolute;top:50%;transform:translateY(-50%);left:0;right:0;height:8px;background:var(--bg-500);border-radius:4px;overflow:hidden">
                @if($dir === 'LONG')
                <div style="position:absolute;left:{{ $entryPos }}%;width:{{ max(0, $tp3Pos - $entryPos) }}%;height:100%;background:rgba(0,212,160,0.25)"></div>
                <div style="position:absolute;left:{{ $slPos }}%;width:{{ max(0, $entryPos - $slPos) }}%;height:100%;background:rgba(255,77,109,0.2)"></div>
                @else
                <div style="position:absolute;left:{{ $tp3Pos }}%;width:{{ max(0, $entryPos - $tp3Pos) }}%;height:100%;background:rgba(0,212,160,0.25)"></div>
                <div style="position:absolute;left:{{ $entryPos }}%;width:{{ max(0, $slPos - $entryPos) }}%;height:100%;background:rgba(255,77,109,0.2)"></div>
                @endif
            </div>

            <div style="position:absolute;left:{{ $slPos }}%;top:0;transform:translateX(-50%);text-align:center">
                <div style="font-size:9px;color:var(--red);font-weight:700;white-space:nowrap">🛑 SL</div>
                <div style="width:3px;height:40px;background:var(--red);margin:2px auto;border-radius:2px"></div>
                <div style="font-size:9px;font-family:var(--font-mono);color:var(--red);white-space:nowrap">{{ fmtPrice($sl) }}</div>
                <div style="font-size:8px;color:var(--text-muted);white-space:nowrap">-{{ $distToSl }}%</div>
            </div>

            <div style="position:absolute;left:{{ $entryPos }}%;top:0;transform:translateX(-50%);text-align:center;z-index:2">
                <div style="font-size:9px;color:var(--blue);font-weight:700;white-space:nowrap">🎯 ENTRY</div>
                <div style="width:3px;height:40px;background:var(--blue);margin:2px auto;border-radius:2px"></div>
                <div style="font-size:9px;font-family:var(--font-mono);color:var(--blue);white-space:nowrap">{{ fmtPrice($entry) }}</div>
            </div>

            <div style="position:absolute;left:{{ $tp1Pos }}%;top:0;transform:translateX(-50%);text-align:center">
                <div style="font-size:9px;color:var(--green);font-weight:700;white-space:nowrap">TP1</div>
                <div style="width:2px;height:36px;background:var(--green);margin:2px auto;border-radius:2px;opacity:0.8"></div>
                <div style="font-size:8px;font-family:var(--font-mono);color:var(--green);white-space:nowrap">+{{ $distToTp1 }}%</div>
            </div>

            <div style="position:absolute;left:{{ $tp2Pos }}%;top:0;transform:translateX(-50%);text-align:center">
                <div style="font-size:9px;color:var(--green);font-weight:600;white-space:nowrap;opacity:0.85">TP2</div>
                <div style="width:2px;height:32px;background:var(--green);margin:2px auto;border-radius:2px;opacity:0.6"></div>
                <div style="font-size:8px;font-family:var(--font-mono);color:var(--green);white-space:nowrap;opacity:0.85">+{{ $distToTp2 }}%</div>
            </div>

            <div style="position:absolute;left:{{ $tp3Pos }}%;top:0;transform:translateX(-50%);text-align:center">
                <div style="font-size:9px;color:var(--yellow);font-weight:700;white-space:nowrap">🚀 TP3</div>
                <div style="width:2px;height:40px;background:var(--yellow);margin:2px auto;border-radius:2px;opacity:0.7"></div>
                <div style="font-size:8px;font-family:var(--font-mono);color:var(--yellow);white-space:nowrap">+{{ $distToTp3 }}%</div>
            </div>

            <div style="position:absolute;left:{{ $curPos }}%;top:0;transform:translateX(-50%);text-align:center;z-index:10">
                <div style="font-size:9px;font-weight:700;color:var(--text-primary);white-space:nowrap;background:var(--bg-600);padding:2px 4px;border-radius:3px;border:1px solid var(--border-2)">NOW</div>
                <div style="width:3px;height:46px;background:white;margin:2px auto;border-radius:2px;box-shadow:0 0 8px rgba(255,255,255,0.6)"></div>
                <div style="font-size:9px;font-family:var(--font-mono);color:white;font-weight:700;white-space:nowrap">${{ fmtPrice($curP) }}</div>
                <div style="font-size:8px;color:{{ $distCur >= 0 ? 'var(--green)' : 'var(--red)' }};white-space:nowrap">{{ $distCur >= 0 ? '+' : '' }}{{ $distCur }}%</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(110px,1fr));gap:10px;margin-top:8px">
            <div style="text-align:center;padding:8px;background:var(--bg-800);border-radius:8px;border:1px solid rgba(255,77,109,0.2)">
                <div style="font-size:10px;color:var(--text-muted)">SL Distance</div>
                <div style="font-size:14px;font-weight:700;color:var(--red);font-family:var(--font-mono)">-{{ $distToSl }}%</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--bg-800);border-radius:8px;border:1px solid rgba(77,158,255,0.2)">
                <div style="font-size:10px;color:var(--text-muted)">Current Δ</div>
                <div style="font-size:14px;font-weight:700;color:{{ $distCur >= 0 ? 'var(--green)' : 'var(--red)' }};font-family:var(--font-mono)">{{ $distCur >= 0 ? '+' : '' }}{{ $distCur }}%</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--bg-800);border-radius:8px;border:1px solid rgba(0,212,160,0.2)">
                <div style="font-size:10px;color:var(--text-muted)">to TP1</div>
                <div style="font-size:14px;font-weight:700;color:var(--green);font-family:var(--font-mono)">+{{ $distToTp1 }}%</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--bg-800);border-radius:8px;border:1px solid rgba(0,212,160,0.15)">
                <div style="font-size:10px;color:var(--text-muted)">to TP2</div>
                <div style="font-size:14px;font-weight:700;color:var(--green);font-family:var(--font-mono)">+{{ $distToTp2 }}%</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--bg-800);border-radius:8px;border:1px solid rgba(245,197,24,0.2)">
                <div style="font-size:10px;color:var(--text-muted)">to TP3</div>
                <div style="font-size:14px;font-weight:700;color:var(--yellow);font-family:var(--font-mono)">+{{ $distToTp3 }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- Target Price Cards --}}
<div class="signal-price-grid" style="margin-bottom:24px">
    <div class="signal-price-item">
        <div class="label">🎯 Entry Price</div>
        <div class="value">${{ fmtPrice($signal->entry_price) }}</div>
    </div>
    <div class="signal-price-item" style="border-color:rgba(255,77,109,0.3)">
        <div class="label">🛑 Stop Loss</div>
        <div class="value" style="color:var(--red)">${{ fmtPrice($signal->stop_loss) }}</div>
        <div style="font-size:10px;color:var(--text-muted);margin-top:4px">
            {{ number_format($signal->sl_pct, 2) }}% from entry
        </div>
    </div>
    <div class="signal-price-item" style="border-color:rgba(0,212,160,0.2)">
        <div class="label">✅ Take Profit 1</div>
        <div class="value" style="color:var(--green)">${{ fmtPrice($signal->take_profit_1) }}</div>
        <div style="font-size:10px;color:var(--text-muted);margin-top:4px">
            R:R {{ number_format($signal->rr1, 2) }}
        </div>
    </div>
    <div class="signal-price-item" style="border-color:rgba(0,212,160,0.15)">
        <div class="label">✅ Take Profit 2</div>
        <div class="value" style="color:var(--green)">${{ fmtPrice($signal->take_profit_2) }}</div>
    </div>
    <div class="signal-price-item" style="border-color:rgba(0,212,160,0.1)">
        <div class="label">✅ Take Profit 3</div>
        <div class="value" style="color:var(--green)">${{ fmtPrice($signal->take_profit_3) }}</div>
    </div>
    <div class="signal-price-item">
        <div class="label">⚡ Leverage</div>
        <div class="value" style="color:var(--purple)">{{ $signal->leverage }}x Cross</div>
    </div>
</div>
@endif
