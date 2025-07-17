<?php
class Verify extends Controller {
    public function index(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $u     = trim($_POST['username'] ?? '');
        $pw    = $_POST['password'] ?? '';
        $userM = $this->model('User');

        // Check failed attempts in last 60 seconds
        $fails = $userM->countRecentFails($u, 60);
        if ($fails >= 3) {
            $lastFail  = $userM->getLastFailed($u);
            $remaining = (strtotime($lastFail) + 60) - time();
            $this->view('login/index', [
                'error'    => "Account locked. Try again in {$remaining}s.",
                'username' => $u
            ]);
            return;
        }

        // Fetch and verify user
        $user = $userM->findByUsername($u);
        if (!$user || !password_verify($pw, $user['password_hash'])) {
            $userM->recordLoginAttempt($u, 'failure');
            $this->view('login/index', [
                'error'    => 'Invalid credentials.',
                'username' => $u
            ]);
            return;
        }

        // Successful login
        $userM->recordLoginAttempt($u, 'success');
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['username']   = $u;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');

        $this->redirect('/home');
    }
}
