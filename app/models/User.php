<?php
require_once __DIR__ . '/../database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, username, password_hash
               FROM users
              WHERE username = :username"
        );
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $username, string $password): bool {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash)
             VALUES (:username, :hash)"
        );
        return $stmt->execute([
            'username' => $username,
            'hash'     => $hash
        ]);
    }

    public function recordLoginAttempt(string $username, string $status): void {
        $stmt = $this->db->prepare(
            "INSERT INTO login_attempts (username, attempt_status)
             VALUES (:username, :status)"
        );
        $stmt->execute([
            'username' => $username,
            'status'   => $status
        ]);
    }

    public function getLastFailed(string $username): ?string {
        $stmt = $this->db->prepare(
            "SELECT attempted_at
               FROM login_attempts
              WHERE username = :username
                AND attempt_status = 'failure'
           ORDER BY attempted_at DESC
              LIMIT 1"
        );
        $stmt->execute(['username' => $username]);
        $ts = $stmt->fetchColumn();
        return $ts ?: null;
    }

    public function countRecentFails(string $username, int $secondsAgo): int {
        $since = date('Y-m-d H:i:s', time() - $secondsAgo);
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
               FROM login_attempts
              WHERE username = :username
                AND attempt_status = 'failure'
                AND attempted_at >= :since"
        );
        $stmt->execute([
            'username' => $username,
            'since'    => $since
        ]);
        return (int)$stmt->fetchColumn();
    }
}
