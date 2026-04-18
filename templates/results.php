<?php include __DIR__ . '/layout.php'; renderHead('Results — ' . $survey['topic']); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links">
        <a href="/admin/dashboard">← Dashboard</a>
    </div>
</div>
<div class="container" style="max-width: 1000px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Results: <?= htmlspecialchars($survey['topic']) ?></h1>
        <a href="/admin/surveys/<?= $survey['id'] ?>/download" class="btn btn-primary">
            Download CSV
        </a>
    </div>

    <?php if (empty($responses)): ?>
    <div class="card">
        <p style="color: var(--text2);">No responses yet.</p>
    </div>
    <?php else: ?>
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Overview</h3>
        <p style="color: var(--text2);">
            Total Responses: <strong style="color: var(--text);"><?= count($responses) ?></strong>
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem;">Individual Responses</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Submitted</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($responses as $r): 
                    $percentage = round(($r['score'] / $r['total']) * 100, 1);
                    $answers = json_decode($r['answers'], true);
                ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td><?= $r['score'] ?> / <?= $r['total'] ?></td>
                    <td>
                        <span class="badge <?= $percentage >= 70 ? 'badge-success' : 'badge-danger' ?>">
                            <?= $percentage ?>%
                        </span>
                    </td>
                    <td><?= date('M j, Y g:i A', strtotime($r['submitted_at'])) ?></td>
                    <td>
                        <details style="cursor: pointer;">
                            <summary style="color: var(--primary); font-weight: 500;">View Answers</summary>
                            <div style="margin-top: 1rem; padding: 1rem; background: var(--bg); border-radius: 6px;">
                                <?php foreach ($answers as $i => $a): ?>
                                <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                                    <div style="font-weight: 600; margin-bottom: 0.5rem;">
                                        Q<?= $i + 1 ?>: <?= htmlspecialchars($a['question']) ?>
                                    </div>
                                    <div style="color: <?= $a['is_correct'] ? 'var(--success)' : 'var(--danger)' ?>;">
                                        Answer: <?= htmlspecialchars($a['user_answer']) ?> 
                                        <?= $a['is_correct'] ? '✓' : '✗' ?>
                                    </div>
                                    <?php if (!$a['is_correct']): ?>
                                    <div style="color: var(--text2); font-size: 0.875rem;">
                                        Correct: <?= htmlspecialchars($a['correct_answer']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
