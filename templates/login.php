<?php include __DIR__ . '/layout.php'; renderHead('Admin Login — SurveyLite'); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links"><a href="/">← Home</a></div>
</div>
<div class="container" style="max-width: 400px;">
    <div class="card">
        <h1 style="margin-bottom: 1.5rem;">Admin Login</h1>
        <?php if (isset($error)): ?>
        <div style="padding: 0.75rem; background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); border-radius: 6px; margin-bottom: 1rem; color: var(--danger);">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="/admin/login">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Login
            </button>
        </form>
        <p style="margin-top: 1rem; color: var(--text2); font-size: 0.875rem; text-align: center;">
            Default: admin / admin123
        </p>
    </div>
</div>
</body>
</html>
