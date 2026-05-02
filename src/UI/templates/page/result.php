<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucky App — Result</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; background: #0f0f0f; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 2rem; width: 100%; max-width: 420px; text-align: center; }
        .rolled { font-size: 4rem; color: #f0c040; margin: 0.5rem 0; }
        .label { font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        .outcome-win  { font-size: 2rem; color: #2ecc71; font-weight: bold; margin: 0.5rem 0; }
        .outcome-lose { font-size: 2rem; color: #e74c3c; font-weight: bold; margin: 0.5rem 0; }
        .win-amount { font-size: 1.4rem; margin: 0.5rem 0 1.5rem; }
        .amount-value { color: #f0c040; }
        .divider { border: none; border-top: 1px solid #222; margin: 1rem 0; }
        .btn { padding: 0.6rem 1.2rem; border: none; cursor: pointer; font-family: monospace; font-size: 0.95rem; font-weight: bold; border-radius: 2px; text-decoration: none; display: inline-block; margin: 0.3rem; }
        .btn-primary   { background: #f0c040; color: #0f0f0f; }
        .btn-primary:hover { background: #ffe066; }
        .btn-secondary { background: #2a2a2a; color: #e0e0e0; border: 1px solid #444; }
        .btn-secondary:hover { background: #333; }
        <?php if ($gameResult->outcome()->value === 'win'): ?>
        body { animation: flash-win 0.4s ease; }
        @keyframes flash-win { 0%,100% { background: #0f0f0f; } 50% { background: #0d2d1a; } }
        <?php else: ?>
        body { animation: flash-lose 0.4s ease; }
        @keyframes flash-lose { 0%,100% { background: #0f0f0f; } 50% { background: #2d0d0d; } }
        <?php endif; ?>
    </style>
</head>
<body>
<div class="card">
    <p class="label">Rolled Number</p>
    <div class="rolled"><?= $gameResult->rolledNumber() ?></div>

    <hr class="divider">

    <?php if ($gameResult->outcome()->value === 'win'): ?>
        <div class="outcome-win">🏆 WIN</div>
        <div class="win-amount">Amount: <span class="amount-value"><?= number_format($gameResult->winAmount(), 2) ?></span></div>
    <?php else: ?>
        <div class="outcome-lose">💀 LOSE</div>
        <div class="win-amount">Amount: <span class="amount-value">0.00</span></div>
    <?php endif; ?>

    <div>
        <form method="POST" action="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/play" style="display:inline">
            <button type="submit" class="btn btn-primary">🎲 Play Again</button>
        </form>
        <a href="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>" class="btn btn-secondary">← Back</a>
        <a href="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/history" class="btn btn-secondary">📜 History</a>
    </div>
</div>
</body>
</html>
