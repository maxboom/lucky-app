<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucky App — Register</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; background: #0f0f0f; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        h1 { font-size: 2rem; margin-bottom: 0.4rem; color: #f0c040; letter-spacing: 2px; }
        .sub { color: #666; font-size: 0.85rem; margin-bottom: 1.8rem; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 2rem; width: 100%; max-width: 420px; }
        label { display: block; margin-bottom: 0.3rem; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        input { width: 100%; padding: 0.6rem 0.8rem; background: #0f0f0f; border: 1px solid #444; color: #e0e0e0; font-family: monospace; font-size: 1rem; border-radius: 2px; margin-bottom: 1.2rem; }
        input:focus { outline: none; border-color: #f0c040; }
        .btn { width: 100%; padding: 0.7rem; background: #f0c040; color: #0f0f0f; border: none; cursor: pointer; font-family: monospace; font-size: 1rem; font-weight: bold; border-radius: 2px; letter-spacing: 1px; }
        .btn:hover { background: #ffe066; }
        .errors { margin-bottom: 1rem; }
        .errors li { list-style: none; color: #e74c3c; padding: 0.3rem 0; font-size: 0.9rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>🎲 LUCKY APP</h1>
    <p class="sub">Register to get your unique access link</p>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $err): ?>
                <li>⚠ <?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/register">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               placeholder="john_doe" required>

        <label for="phone_number">Phone Number</label>
        <input type="tel" id="phone_number" name="phone_number"
               value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>"
               placeholder="+380991234567" required>

        <button type="submit" class="btn">Register</button>
    </form>
</div>
</body>
</html>
