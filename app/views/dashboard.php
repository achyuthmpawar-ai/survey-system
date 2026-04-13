<h1>Dashboard</h1>

<form method="POST" action="/survey-system/public/admin/upload" enctype="multipart/form-data">
    <input name="title">
    <input type="file" name="csv">
    <button>Upload</button>
</form>

<?php foreach ($surveys as $s): ?>
    <p>
        <?= $s['title'] ?>
        <a href="/survey-system/public/admin/toggle/<?= $s['id'] ?>">Toggle</a>
        <a href="/survey-system/public/survey/<?= $s['slug'] ?>">Open</a>
    </p>
<?php endforeach; ?>