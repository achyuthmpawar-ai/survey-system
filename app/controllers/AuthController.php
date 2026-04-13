<?php

class AuthController {

    // Show login page
    public function loginPage($req, $res) {
        ob_start();
        include __DIR__ . '/../views/login.php';
        $res->getBody()->write(ob_get_clean());
        return $res;
    }

    // Handle login request
    public function login($req, $res) {

        $data = $req->getParsedBody();

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // simple demo authentication
        if ($username === 'admin' && $password === '1234') {

            $_SESSION['admin'] = true;

            // ✅ correct redirect using BASE_URL
            return $res
                ->withHeader('Location', BASE_URL . '/admin')
                ->withStatus(302);
        }

        // invalid login
        $res->getBody()->write("
            <h3>Invalid login</h3>
            <a href='" . BASE_URL . "/login'>Go back</a>
        ");
        return $res;
    }

    // Logout admin
    public function logout($req, $res) {

        session_destroy();

        // ✅ correct redirect
        return $res
            ->withHeader('Location', BASE_URL . '/login')
            ->withStatus(302);
    }
}