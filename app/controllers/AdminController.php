<?php

class AdminController {

    public function dashboard($req, $res) {

        if (!isset($_SESSION['admin'])) {
            return $res->withHeader('Location', '/login')->withStatus(302);
        }

        $db = DB::connect();
        $surveys = $db->query("SELECT * FROM surveys")->fetchAll();

        ob_start();
        include __DIR__ . '/../views/dashboard.php';
        $res->getBody()->write(ob_get_clean());
        return $res;
    }

    public function uploadCSV($req, $res) {

        $db = DB::connect();

        $data = $req->getParsedBody();
        $file = $_FILES['csv']['tmp_name'];

        $slug = strtolower(str_replace(' ', '-', $data['title'])) . time();

        $stmt = $db->prepare("INSERT INTO surveys (title, slug) VALUES (?, ?)");
        $stmt->execute([$data['title'], $slug]);

        $survey_id = $db->lastInsertId();

        $handle = fopen($file, "r");
        $first = true;

        while (($row = fgetcsv($handle)) !== false) {

            if ($first) { $first = false; continue; }

            $db->prepare("INSERT INTO questions (survey_id, question, correct_answer)
            VALUES (?, ?, ?)")
            ->execute([$survey_id, $row[0], $row[1]]);

            $qid = $db->lastInsertId();

            for ($i = 2; $i <= 4; $i++) {
                $db->prepare("INSERT INTO options (question_id, option_text)
                VALUES (?, ?)")
                ->execute([$qid, $row[$i]]);
            }
        }

        fclose($handle);

        return $res->withHeader('Location', '/admin')->withStatus(302);
    }

    public function toggle($req, $res, $args) {

        $db = DB::connect();
        $id = $args['id'];

        $db->exec("UPDATE surveys SET is_active = NOT is_active WHERE id=$id");

        return $res->withHeader('Location', '/admin')->withStatus(302);
    }

    public function download($req, $res, $args) {

        $db = DB::connect();
        $id = $args['id'];

        $rows = $db->query("SELECT * FROM responses WHERE survey_id=$id")->fetchAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="results.csv"');

        $out = fopen('php://output', 'w');

        foreach ($rows as $r) {
            fputcsv($out, $r);
        }

        fclose($out);
        exit;
    }
}