<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Database;

class HomeController {
    public function index(Request $request, Response $response): Response {
        $db = Database::get();
        $surveys = $db->query('SELECT * FROM surveys WHERE active=1 ORDER BY created_at DESC')->fetchAll(\PDO::FETCH_ASSOC);
        
        ob_start();
        include __DIR__ . '/../../templates/home.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function showSurvey(Request $request, Response $response, array $args): Response {
        $slug = $args['slug'];
        $db = Database::get();
        
        $survey = $db->prepare('SELECT * FROM surveys WHERE slug=? AND active=1')->execute([$slug]) 
            ? $db->query("SELECT * FROM surveys WHERE slug='$slug' AND active=1")->fetch(\PDO::FETCH_ASSOC)
            : null;
        
        if (!$survey) {
            $response->getBody()->write('Survey not found or inactive');
            return $response->withStatus(404);
        }

        $stmt = $db->prepare('SELECT * FROM questions WHERE survey_id=?');
        $stmt->execute([$survey['id']]);
        $questions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../../templates/survey.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function submitSurvey(Request $request, Response $response, array $args): Response {
        $slug = $args['slug'];
        $db = Database::get();
        
        $survey = $db->query("SELECT * FROM surveys WHERE slug='$slug'")->fetch(\PDO::FETCH_ASSOC);
        if (!$survey) {
            $response->getBody()->write('Survey not found');
            return $response->withStatus(404);
        }

        $stmt = $db->prepare('SELECT * FROM questions WHERE survey_id=?');
        $stmt->execute([$survey['id']]);
        $questions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $params = $request->getParsedBody();
        $answers = [];
        $score = 0;

        foreach ($questions as $q) {
            $userAnswer = $params['q_' . $q['id']] ?? '';
            $answers[] = [
                'question_id' => $q['id'],
                'question' => $q['question'],
                'user_answer' => $userAnswer,
                'correct_answer' => $q['correct_answer'],
                'is_correct' => $userAnswer === $q['correct_answer']
            ];
            if ($userAnswer === $q['correct_answer']) {
                $score++;
            }
        }

        $db->prepare('INSERT INTO responses (survey_id, answers, score, total) VALUES (?, ?, ?, ?)')
            ->execute([$survey['id'], json_encode($answers), $score, count($questions)]);

        ob_start();
        include __DIR__ . '/../../templates/result.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }
}
