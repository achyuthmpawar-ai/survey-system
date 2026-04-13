<form method="POST">

<input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">

<?php foreach ($questions as $q): ?>

    <p><?= htmlspecialchars($q['question']) ?></p>

    <?php foreach ($q['options'] as $o): ?>
        <label>
            <input type="radio"
                   name="answers[<?= $q['id'] ?>]"
                   value="<?= htmlspecialchars($o['option_text']) ?>">
            <?= htmlspecialchars($o['option_text']) ?>
        </label>
        <br>
    <?php endforeach; ?>

<?php endforeach; ?>

<button type="submit">Submit</button>

</form>