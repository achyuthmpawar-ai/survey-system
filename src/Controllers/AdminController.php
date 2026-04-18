<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Database;
use App\Models\CsvParser;

class AdminController {
    public function loginForm(Request $request, Response $response): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        ob_start();
        include __DIR__ . '/../../templates/login.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function login(Request $request, Response $response): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $params = $request->getParsedBody();
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        $db = Database::get();
        $admin = $db->prepare('SELECT * FROM admins WHERE username=?');
        $admin->execute([$username]);
        $admin = $admin->fetch(\PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            return $response->withHeader('Location', '/admin/dashboard')->withStatus(302);
        }

        $error = 'Invalid credentials';
        ob_start();
        include __DIR__ . '/../../templates/login.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function logout(Request $request, Response $response): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        session_destroy();
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function dashboard(Request $request, Response $response): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $db = Database::get();
        $surveys = $db->query('SELECT s.*, COUNT(r.id) as response_count FROM surveys s LEFT JOIN responses r ON s.id=r.survey_id GROUP BY s.id ORDER BY s.created_at DESC')->fetchAll(\PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../../templates/dashboard.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function createSurvey(Request $request, Response $response): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $params = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        
        $topic = $params['topic'] ?? '';
        $csvFile = $uploadedFiles['csv_file'] ?? null;

        if (!$topic || !$csvFile || $csvFile->getError() !== UPLOAD_ERR_OK) {
            return $response->withHeader('Location', '/admin/dashboard?error=invalid')->withStatus(302);
        }

        // Save uploaded file
        $filename = uniqid() . '_' . $csvFile->getClientFilename();
        $filepath = __DIR__ . '/../../storage/' . $filename;
        $csvFile->moveTo($filepath);

        // Parse CSV
        $questions = CsvParser::parse($filepath);
        if (empty($questions)) {
            unlink($filepath);
            return $response->withHeader('Location', '/admin/dashboard?error=empty')->withStatus(302);
        }

        // Create survey
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $topic)) . '-' . substr(md5($topic . time()), 0, 6);
        $db = Database::get();
        $db->prepare('INSERT INTO surveys (topic, slug, csv_filename) VALUES (?, ?, ?)')
            ->execute([$topic, $slug, $filename]);
        $surveyId = $db->lastInsertId();

        // Insert questions
        foreach ($questions as $q) {
            $db->prepare('INSERT INTO questions (survey_id, question, correct_answer, wrong_options) VALUES (?, ?, ?, ?)')
                ->execute([$surveyId, $q['question'], $q['correct_answer'], json_encode($q['wrong_options'])]);
        }

        return $response->withHeader('Location', '/admin/dashboard?success=1')->withStatus(302);
    }

    public function toggleSurvey(Request $request, Response $response, array $args): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $id = $args['id'];
        $db = Database::get();
        
        $survey = $db->prepare('SELECT * FROM surveys WHERE id=?');
        $survey->execute([$id]);
        $survey = $survey->fetch(\PDO::FETCH_ASSOC);

        if ($survey) {
            $newStatus = $survey['active'] ? 0 : 1;
            $db->prepare('UPDATE surveys SET active=? WHERE id=?')->execute([$newStatus, $id]);
        }

        return $response->withHeader('Location', '/admin/dashboard')->withStatus(302);
    }

    public function viewResults(Request $request, Response $response, array $args): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $id = $args['id'];
        $db = Database::get();
        
        $survey = $db->prepare('SELECT * FROM surveys WHERE id=?');
        $survey->execute([$id]);
        $survey = $survey->fetch(\PDO::FETCH_ASSOC);

        if (!$survey) {
            $response->getBody()->write('Survey not found');
            return $response->withStatus(404);
        }

        $stmt = $db->prepare('SELECT * FROM responses WHERE survey_id=? ORDER BY submitted_at DESC');
        $stmt->execute([$id]);
        $responses = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../../templates/results.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }

    public function downloadResults(Request $request, Response $response, array $args): Response {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        $id = $args['id'];
        $db = Database::get();
        
        $survey = $db->prepare('SELECT * FROM surveys WHERE id=?');
        $survey->execute([$id]);
        $survey = $survey->fetch(\PDO::FETCH_ASSOC);

        if (!$survey) {
            return $response->withStatus(404);
        }

        $stmt = $db->prepare('SELECT * FROM responses WHERE survey_id=?');
        $stmt->execute([$id]);
        $responses = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $csv = "ID,Score,Total,Percentage,Submitted At\n";
        foreach ($responses as $r) {
            $percentage = round(($r['score'] / $r['total']) * 100, 1);
            $csv .= "{$r['id']},{$r['score']},{$r['total']},{$percentage}%,{$r['submitted_at']}\n";
        }

        $response->getBody()->write($csv);
        return $response
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="results-' . $survey['slug'] . '.csv"');
    }
}
