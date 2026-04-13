<?php

class SurveyController {

    public function show($req, $res, $args) {

        $db = DB::connect();
  $stmt = $db->prepare("SELECT * FROM surveys WHERE slug = ? AND is_active = 1");
        $stmt->execute([$args['slug']]);
        $survey = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$survey) {
            $res->getBody()->write("Survey not available");
            return $res;
        }
  $stmt = $db->prepare("SELECT * FROM questions WHERE survey_id = ?");
        $stmt->execute([$survey['id']]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$q) {
            $stmt = $db->prepare("SELECT * FROM options WHERE question_id = ?");
            $stmt->execute([$q['id']]);
            $q['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        ob_start();
        include __DIR__ . '/../views/survey.php';
        $res->getBody()->write(ob_get_clean());
        return $res;
    }

    public function submit($req, $res, $args) {

        $db = DB::connect();
        $data = $req->getParsedBody();

        if (!isset($data['answers'])) {
            $res->getBody()->write("No answers submitted");
            return $res;
        }

        foreach ($data['answers'] as $qid => $ans) {
            $stmt = $db->prepare("
                INSERT INTO responses (survey_id, question_id, answer)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $data['survey_id'],
                $qid,
                $ans
            ]);
        }

        $res->getBody()->write("Thanks for submitting the survey!");
        return $res;
    }
}