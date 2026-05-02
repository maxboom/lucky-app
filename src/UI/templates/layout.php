<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Lucky App') ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; background: #0f0f0f; color: #e0e0e0; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; }
        h1 { font-size: 2rem; margin-bottom: 1.5rem; color: #f0c040; letter-spacing: 2px; }
        h2 { font-size: 1.2rem; margin-bottom: 1rem; color: #aaa; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 2rem; width: 100%; max-width: 520px; }
        label { display: block; margin-bottom: 0.3rem; font-size: 0.85rem; color: #888; text-transform: uppercase; }
        input[type=text], input[type=tel] { width: 100%; padding: 0.6rem 0.8rem; background: #0f0f0f; border: 1px solid #444; color: #e0e0e0; font-family: monospace; font-size: 1rem; border-radius: 2px; margin-bottom: 1rem; }
        input:focus { outline: none; border-color: #f0c040; }
        .btn { display: inline-block; padding: 0.6rem 1.4rem; background: #f0c040; color: #0f0f0f; border: none; cursor: pointer; font-family: monospace; font-size: 0.95rem; font-weight: bold; border-radius: 2px; text-decoration: none; }
        .btn:hover { background: #ffe066; }
        .btn-danger { background: #c0392b; color: #fff; }
        .btn-danger:hover { background: #e74c3c; }
        .btn-secondary { background: #333; color: #e0e0e0; }
        .btn-secondary:hover { background: #444; }
        .errors { color: #e74c3c; margin-bottom: 1rem; font-size: 0.9rem; }
        .errors li { list-style: none; padding: 0.3rem 0; }
        .actions { display: flex; gap: 0.8rem; flex-wrap: wrap; margin-top: 1rem; }
        .info { color: #888; font-size: 0.85rem; margin-top: 0.5rem; }
        .result-number { font-size: 3rem; color: #f0c040; text-align: center; margin: 1rem 0; }
        .outcome-win  { color: #2ecc71; font-size: 1.5rem; font-weight: bold; text-align: center; }
        .outcome-lose { color: #e74c3c; font-size: 1.5rem; font-weight: bold; text-align: center; }
        .win-amount { text-align: center; font-size: 1.2rem; margin-top: 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.5rem 0.8rem; border: 1px solid #333; text-align: left; font-size: 0.9rem; }
        th { background: #222; color: #888; text-transform: uppercase; font-size: 0.75rem; }
        .tag-win  { color: #2ecc71; }
        .tag-lose { color: #e74c3c; }
        .link-box { background: #0f0f0f; border: 1px solid #444; padding: 0.6rem; word-break: break-all; font-size: 0.8rem; margin-top: 0.5rem; border-radius: 2px; }
        .back { margin-top: 1.5rem; font-size: 0.85rem; }
        .back a { color: #f0c040; }
    </style>
</head>
<body>
<?php require __DIR__ . '/' . $template . '.php'; ?>
</body>
</html>
