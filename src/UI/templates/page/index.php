<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucky App — Your Page</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; background: #0f0f0f; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        h1 { font-size: 1.8rem; margin-bottom: 0.3rem; color: #f0c040; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 2rem; width: 100%; max-width: 520px; }
        .welcome { color: #888; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .section-label { font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.4rem; margin-top: 1.2rem; }
        .link-box { background: #0f0f0f; border: 1px solid #444; padding: 0.6rem; word-break: break-all; font-size: 0.78rem; border-radius: 2px; color: #aaa; }
        .expires { font-size: 0.75rem; color: #555; margin-top: 0.3rem; }
        .actions { display: flex; gap: 0.7rem; flex-wrap: wrap; margin-top: 1.5rem; }
        .btn { padding: 0.6rem 1.2rem; border: none; cursor: pointer; font-family: monospace; font-size: 0.95rem; font-weight: bold; border-radius: 2px; text-decoration: none; display: inline-block; }
        .btn-primary   { background: #f0c040; color: #0f0f0f; }
        .btn-primary:hover { background: #ffe066; }
        .btn-secondary { background: #2a2a2a; color: #e0e0e0; border: 1px solid #444; }
        .btn-secondary:hover { background: #333; }
        .btn-danger    { background: #1a1a1a; color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-danger:hover { background: #2d1a1a; }
        .divider { border: none; border-top: 1px solid #222; margin: 1.5rem 0; }
    </style>
</head>
<body>
<div class="card">
    <h1>🎰 Your Lucky Page</h1>
    <p class="welcome">Welcome, <strong><?= htmlspecialchars($user->username()->toString()) ?></strong></p>

    <p class="section-label">Your Access Link</p>
    <div class="link-box">
        <?= htmlspecialchars(
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST']
            . '/page/' . $user->accessLink()->token()
        ) ?>
    </div>
    <p class="expires">Expires: <?= $user->accessLink()->expiresAt()->format('Y-m-d H:i') ?> UTC</p>

    <hr class="divider">

    <div class="actions">
        <form method="POST" action="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/play">
            <button type="submit" class="btn btn-primary">🎲 ImFeelingLucky</button>
        </form>

        <a href="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/history" class="btn btn-secondary">📜 History</a>

        <form method="POST" action="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/regenerate">
            <button type="submit" class="btn btn-secondary">🔄 Regenerate Link</button>
        </form>

        <form method="POST" action="/page/<?= htmlspecialchars($user->accessLink()->token()) ?>/deactivate"
              onsubmit="return confirm('Deactivate this link? You will lose access.')">
            <button type="submit" class="btn btn-danger">🚫 Deactivate Link</button>
        </form>
    </div>
</div>
</body>
</html>
