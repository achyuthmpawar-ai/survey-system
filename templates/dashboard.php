<?php include __DIR__ . '/layout.php'; renderHead('Admin Dashboard — SurveyLite'); ?>
<div class="nav">
    <a href="/" class="nav-brand">▲ SurveyLite</a>
    <div class="nav-links">
        <span style="color: var(--text2);">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
        <a href="/admin/logout">Logout</a>
    </div>
</div>
<div class="container" style="max-width: 1000px;">
    <h1 style="margin-bottom: 2rem;">Admin Dashboard</h1>
    
    <?php if (isset($_GET['success'])): ?>
    <div style="padding: 1rem; background: rgba(34, 197, 94, 0.1); border: 1px solid var(--success); border-radius: 6px; margin-bottom: 1rem; color: var(--success);">
        Survey created successfully!
    </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1rem;">Create New Survey</h2>
        <form method="POST" action="/admin/surveys/create" enctype="multipart/form-data">
            <div class="form-group">
                <label>Survey Topic</label>
                <input type="text" name="topic" placeholder="e.g., Computer Science Basics" required>
            </div>
            <div class="form-group">
                <label>CSV File</label>
                <input type="file" name="csv_file" accept=".csv" required>
                <p style="color: var(--text2); font-size: 0.875rem; margin-top: 0.5rem;">
                    Format: Question, CorrectAnswer, WrongOption1, WrongOption2, ...
                </p>
            </div>
            <button type="submit" class="btn btn-success">Create Survey</button>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 1rem;">Manage Surveys</h2>
        <?php if (empty($surveys)): ?>
        <p style="color: var(--text2);">No surveys created yet.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Responses</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($surveys as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['topic']) ?></td>
                    <td>
                        <?php if ($s['active']): ?>
                        <span class="badge badge-success">Active</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $s['response_count'] ?></td>
                    <td><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
                    <td>
                        <form method="POST" action="/admin/surveys/<?= $s['id'] ?>/toggle" style="display: inline;">
                            <button type="submit" class="btn" style="background: var(--border); color: var(--text);">
                                <?= $s['active'] ? 'Disable' : 'Enable' ?>
                            </button>
                        </form>
                        <a href="/admin/surveys/<?= $s['id'] ?>/results" class="btn btn-primary">
                            View Results
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
