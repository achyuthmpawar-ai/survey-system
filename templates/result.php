<?php include __DIR__ . '/layout.php'; renderHead('Results — SurveyLite'); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links"><a href="/">← Home</a></div>
</div>
<div class="container">
    <div class="card" style="text-align: center; margin-bottom: 2rem;">
        <h1 style="margin-bottom: 1rem;">Survey Complete!</h1>
        <div style="font-size: 3rem; font-weight: 700; color: var(--primary); margin: 1rem 0;">
            <?= $score ?> / <?= count($questions) ?>
        </div>
        <p style="color: var(--text2); font-size: 1.125rem;">
            <?= round(($score / count($questions)) * 100, 1) ?>% Correct
        </p>
    </div>

    <h2 style="margin-bottom: 1rem;">Review Your Answers</h2>
    <?php foreach ($answers as $i => $a): ?>
    <div class="card">
        <div style="font-weight: 600; color: var(--text2); margin-bottom: 0.5rem;">
            Question <?= $i + 1 ?>
        </div>
        <div style="margin-bottom: 1rem;">
            <?= htmlspecialchars($a['question']) ?>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="padding: 0.5rem; background: <?= $a['is_correct'] ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; border-radius: 4px;">
                <strong>Your answer:</strong> <?= htmlspecialchars($a['user_answer']) ?>
                <?= $a['is_correct'] ? '✓' : '✗' ?>
            </div>
            <?php if (!$a['is_correct']): ?>
            <div style="padding: 0.5rem; background: rgba(34, 197, 94, 0.1); border-radius: 4px;">
                <strong>Correct answer:</strong> <?= htmlspecialchars($a['correct_answer']) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
