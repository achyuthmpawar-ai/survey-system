<?php include __DIR__ . '/layout.php'; renderHead('SurveyLite'); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links">
        <a href="/admin/login">Admin Login</a>
    </div>
</div>
<div class="container">
    <h1 style="margin-bottom: 2rem;">Available Surveys</h1>
    <?php if (empty($surveys)): ?>
        <div class="card">
            <p style="color: var(--text2);">No active surveys available at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($surveys as $survey): ?>
        <div class="card">
            <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($survey['topic']) ?></h3>
            <p style="color: var(--text2); margin-bottom: 1rem;">
                Created: <?= date('M j, Y', strtotime($survey['created_at'])) ?>
            </p>
            <a href="/survey/<?= htmlspecialchars($survey['slug']) ?>" class="btn btn-primary">
                Take Survey →
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
