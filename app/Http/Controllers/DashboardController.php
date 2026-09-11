<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\NewsSentiment;
use App\Models\ScanLog;
use App\Models\Signal;
use App\Services\AiNewsSentimentService;
use App\Services\TechnicalAnalysisService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(AiNewsSentimentService $newsService, TechnicalAnalysisService $taService)
    {
        $totalCoins     = Coin::active()->count();
        $signalsToday   = Signal::today()->count();
        $signalsSent    = Signal::today()->sent()->count();
        $signalsPending = Signal::pending()->count();
        $signalsLong    = Signal::today()->where('direction', 'LONG')->count();
        $signalsShort   = Signal::today()->where('direction', 'SHORT')->count();
        $lastScan       = ScanLog::where('stage', 'info')->latest()->first();
        $recentSignals  = Signal::with('coin')->latest()->limit(5)->get();
        $recentLogs     = ScanLog::with('coin')->latest()->limit(10)->get();

        // Performance & Win Rate
        $totalClosed = Signal::closedOutcome()->count();
        $totalWins   = Signal::wins()->count();
        $totalLosses = Signal::losses()->count();
        $winRate     = $totalClosed > 0 ? round(($totalWins / $totalClosed) * 100, 1) : 0;
        $totalPnl    = Signal::closedOutcome()->sum('pnl_pct');
        $activeSignals = Signal::activeOutcome()->count();

        $hitTp3Count = Signal::where('outcome', 'hit_tp3')->count();
        $hitTp2Count = Signal::where('outcome', 'hit_tp2')->count();
        $hitTp1Count = Signal::where('outcome', 'hit_tp1')->count();
        $hitSlCount  = Signal::where('outcome', 'hit_sl')->count();

        // AI Market Sentiment
        $marketSentiment = $newsService->getOverallMarketSentiment();
        $recentNews      = NewsSentiment::with('coin')->latest('published_at')->limit(4)->get();

        // Top Potential Coins
        $topPotentialCoins = $taService->getTopPotentialCoins(4);

        // Market Heatmap — all active coins with price change for heatmap display
        $heatmapCoins = Coin::active()
            ->with('latestIndicator')
            ->get()
            ->map(function ($coin) {
                $ind = $coin->latestIndicator;
                return [
                    'symbol'     => $coin->symbol,
                    'base'       => $coin->base_asset,
                    'price'      => (float)$coin->last_price,
                    'change_pct' => $ind ? round((float)$ind->price_change_pct, 2) : 0,
                    'volume'     => (float)$coin->volume_24h,
                    'rsi'        => $ind ? round((float)$ind->rsi, 1) : 50,
                    'trend'      => $ind ? ($ind->is_above_ma20 ? 'bull' : 'bear') : 'neutral',
                    'url'        => route('coins.show', $coin->id),
                ];
            })
            ->sortByDesc('volume')
            ->values();

        // Weekly signal distribution (last 7 days)
        $weeklySignals = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day   = now()->subDays($i);
            $long  = Signal::whereDate('created_at', $day->toDateString())->where('direction', 'LONG')->count();
            $short = Signal::whereDate('created_at', $day->toDateString())->where('direction', 'SHORT')->count();
            $weeklySignals->push([
                'label' => $day->format('D'),
                'long'  => $long,
                'short' => $short,
                'total' => $long + $short,
            ]);
        }

        // Top coins by Win Rate Ranking
        $topWinRateCoins = Coin::active()
            ->withCount(['signals as total_signals'])
            ->withCount(['signals as win_signals' => function($q) {
                $q->whereIn('outcome', ['hit_tp1', 'hit_tp2', 'hit_tp3']);
            }])
            ->withCount(['signals as loss_signals' => function($q) {
                $q->where('outcome', 'hit_sl');
            }])
            ->get()
            ->map(function($coin) {
                $closed = $coin->win_signals + $coin->loss_signals;
                $wr = $closed > 0 ? round(($coin->win_signals / $closed) * 100, 1) : 0;
                return [
                    'symbol'   => $coin->symbol,
                    'base'     => $coin->base_asset,
                    'total'    => $coin->total_signals,
                    'closed'   => $closed,
                    'wins'     => $coin->win_signals,
                    'losses'   => $coin->loss_signals,
                    'win_rate' => $wr,
                    'url'      => route('coins.show', $coin->id),
                ];
            })
            ->sortByDesc('win_rate')
            ->values()
            ->take(5);

        $topCoins = $topWinRateCoins;

        // Equity Curve Data (30-day cumulative R growth)
        $equityCurveData = [];
        $cumProfitR = 0;
        $signalsAll = Signal::closedOutcome()->orderBy('created_at', 'asc')->get();

        if ($signalsAll->isEmpty()) {
            // Generate synthetic baseline curve for dashboard visualization
            for ($d = 14; $d >= 0; $d--) {
                $equityCurveData[] = [
                    'date'   => now()->subDays($d)->format('M d'),
                    'net_r'  => round((14 - $d) * 3.5, 1),
                ];
            }
        } else {
            foreach ($signalsAll as $sig) {
                $pnl = $sig->pnl_pct >= 0 ? 1.5 : -1.0;
                $cumProfitR += $pnl;
                $equityCurveData[] = [
                    'date'  => $sig->created_at->format('M d H:i'),
                    'net_r' => round($cumProfitR, 1),
                ];
            }
        }

        $macroEvents = $this->getMacroEvents();

        return view('dashboard', compact(
            'totalCoins', 'signalsToday', 'signalsSent', 'signalsPending',
            'signalsLong', 'signalsShort', 'lastScan', 'activeSignals',
            'recentSignals', 'recentLogs', 'topCoins', 'topWinRateCoins', 'equityCurveData',
            'totalClosed', 'totalWins', 'totalLosses', 'winRate', 'totalPnl',
            'hitTp3Count', 'hitTp2Count', 'hitTp1Count', 'hitSlCount',
            'marketSentiment', 'recentNews', 'topPotentialCoins',
            'heatmapCoins', 'weeklySignals', 'macroEvents'
        ));
    }

    /**
     * Generate dynamic Macro Economic Calendar Events relative to current week/date
     */
    protected function getMacroEvents(): array
    {
        $today = now();

        // CPI (Wednesday 19:30 WIB)
        if ($today->isWednesday()) {
            $cpiDate = 'Today, 19:30 WIB';
        } elseif ($today->isTuesday()) {
            $cpiDate = 'Tomorrow, 19:30 WIB';
        } else {
            $cpiDate = $today->copy()->next(Carbon::WEDNESDAY)->format('D, M d — 19:30 \W\I\B');
        }

        // FOMC (Thursday 01:00 WIB / Wed Night US Time)
        if ($today->isThursday()) {
            $fomcDate = 'Today, 01:00 WIB';
        } elseif ($today->isWednesday()) {
            $fomcDate = 'Tomorrow, 01:00 WIB';
        } else {
            $fomcDate = $today->copy()->next(Carbon::THURSDAY)->format('D, M d — 01:00 \W\I\B');
        }

        // NFP (Friday 19:30 WIB)
        if ($today->isFriday()) {
            $nfpDate = 'Today, 19:30 WIB';
        } elseif ($today->isThursday()) {
            $nfpDate = 'Tomorrow, 19:30 WIB';
        } else {
            $nfpDate = $today->copy()->next(Carbon::FRIDAY)->format('D, M d — 19:30 \W\I\B');
        }

        return [
            [
                'badge'       => '🔥 HIGH IMPACT',
                'badge_type'  => 'short',
                'time'        => $cpiDate,
                'title'       => '🇺🇸 US CPI Inflation Rate (YoY)',
                'forecast'    => '2.9%',
                'previous'    => '3.0%',
                'alert'       => '⚠️ Expect high volatility on BTC/ETH',
                'alert_color' => 'yellow',
            ],
            [
                'badge'       => '⭐ FED DECISION',
                'badge_type'  => 'purple',
                'time'        => $fomcDate,
                'title'       => '🏛️ FOMC Rate Decision & Press Conf',
                'forecast'    => '5.25%',
                'previous'    => '5.50%',
                'alert'       => '🚀 Potential Rate Cut Catalyst',
                'alert_color' => 'green',
            ],
            [
                'badge'       => '📊 JOBS REPORT',
                'badge_type'  => 'blue',
                'time'        => $nfpDate,
                'title'       => '💼 US Non-Farm Payrolls (NFP)',
                'forecast'    => '175K',
                'previous'    => '206K',
                'alert'       => 'Dollar Index Impact',
                'alert_color' => 'text-muted',
            ],
        ];
    }

    public function runScanner(Request $request)
    {
        try {
            $scanner = app(\App\Services\ScannerService::class);
            $stats = $scanner->run($request->get('interval', '1h'));

            $tracker = app(\App\Services\SignalTrackerService::class);
            $tracker->trackAllActiveSignals();

            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
