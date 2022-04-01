<?php

class Database
{
    protected PDO $db;
    private bool $local;
    private array $config;
    public int $serverTimestamp;

    public function __construct()
    {
        $this->setLocal();
        $this->setConfig();
        $this->setDb();
        $this->serverTimestamp = strtotime('now');
    }

    private function setLocal(): void
    {
        $localhost = [
            '127.0.0.1',
            '::1'
        ];
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->local = in_array($ip, $localhost);
    }

    private function setConfig(): void
    {
        if ($this->local) {
            $this->config = [
                'host' => 'localhost',
                'database' => 'webdevproject',
                'username' => 'root',
                'password' => ''
            ];
        } else {
            $this->config = [
                'host' => 'localhost',
                'database' => '1',
                'username' => '1',
                'password' => '1'
            ];
        }
    }

    private function setDb(): void
    {
        try {
            $this->db = new PDO("mysql:host={$this->config['host']};dbname={$this->config['database']};charset=utf8", $this->config['username'], $this->config['password'], []);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->db->setAttribute(PDO::ATTR_PERSISTENT, true);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}