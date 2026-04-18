<?php include __DIR__ . '/layout.php'; renderHead($survey['topic'] . ' — SurveyLite'); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links"><a href="/">← Back</a></div>
</div>
<div class="container">
    <h1><?= htmlspecialchars($survey['topic']) ?></h1>
    <p style="color:var(--text2);margin-bottom:1.5rem;"><?= count($questions) ?> questions</p>
    <form method="POST" action="/survey/<?= htmlspecialchars($survey['slug']) ?>/submit">
        <?php foreach ($questions as $i => $q):
            $options = array_merge([$q['correct_answer']], json_decode($q['wrong_options'], true));
            shuffle($options);
        ?>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">
                Question <?= $i + 1 ?>
            </div>
            <div style="font-size: 1.125rem; margin-bottom: 1rem;">
                <?= htmlspecialchars($q['question']) ?>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach ($options as $opt): ?>
                <label style="display: flex; align-items: center; padding: 0.75rem; background: var(--bg); border: 1px solid var(--border); border-radius: 6px; cursor: pointer;">
                    <input type="radio" name="q_<?= $q['id'] ?>" value="<?= htmlspecialchars($opt) ?>" required style="margin-right: 0.75rem;">
                    <span><?= htmlspecialchars($opt) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem;">
            Submit Answers
        </button>
    </form>
</div>
</body>
</html>
