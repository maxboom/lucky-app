<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucky App — History</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; background: #0f0f0f; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        h1 { font-size: 1.5rem; color: #f0c040; margin-bottom: 1.5rem; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 2rem; width: 100%; max-width: 560px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.6rem 0.8rem; border: 1px solid #2a2a2a; text-align: left; }
        th { background: #111; color: #666; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 1px; }
        .win  { color: #2ecc71; font-weight: bold; }
        .lose { color: #e74c3c; font-weight: bold; }
        .empty { color: #555; text-align: center; padding: 2rem; }
        .btn { padding: 0.5rem 1.1rem; border: none; cursor: pointer; font-family: monospace; font-size: 0.9rem; font-weight: bold; border-radius: 2px; text-decoration: none; display: inline-block; margin-top: 1.2rem; background: #2a2a2a; color: #e0e0e0; border: 1px solid #444; }
        .btn:hover { background: #333; }
    </style>
</head>
<body>
<div class="card">
    <h1>📜 Last 3 Results</h1>

    <?php if (empty($history)): ?>
        <p class="empty">No games played yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Number</th>
                    <th>Outcome</th>
                    <th>Win Amount</th>
                    <th>Played At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $i => $result): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $result->rolledNumber() ?></td>
                    <td class="<?= $result->outcome()->value ?>">
                        <?= strtoupper($result->outcome()->value) ?>
                    </td>
                    <td><?= $result->outcome()->value === 'win' ? number_format($result->winAmount(), 2) : '—' ?></td>
                    <td><?= $result->playedAt()->format('Y-m-d H:i:s') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>" class="btn">← Back</a>
</div>
</body>
</html>
