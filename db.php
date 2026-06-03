<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'registration_form';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed.'
    ]);
    exit;
}

$conn->set_charset('utf8mb4');

$createClientsTable = "
    CREATE TABLE IF NOT EXISTS clients (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        client_name VARCHAR(150) NOT NULL,
        client_email VARCHAR(150) NOT NULL,
        client_phone VARCHAR(20) NOT NULL,
        company_name VARCHAR(150) NULL,
        client_address TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_client_email (user_id, client_email),
        CONSTRAINT fk_clients_user
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

$conn->query($createClientsTable);

$createMonthlySalesTable = "
    CREATE TABLE IF NOT EXISTS monthly_sales (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        client_id INT UNSIGNED NULL,
        sale_month DATE NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        note TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_monthly_sales_user
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_monthly_sales_client
            FOREIGN KEY (client_id) REFERENCES clients(id)
            ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

$conn->query($createMonthlySalesTable);

$createLeadsTable = "
    CREATE TABLE IF NOT EXISTS leads (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        lead_name VARCHAR(150) NOT NULL,
        lead_email VARCHAR(150) NULL,
        lead_phone VARCHAR(20) NULL,
        lead_source VARCHAR(100) NULL,
        lead_status VARCHAR(50) NOT NULL DEFAULT 'New',
        note TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_leads_user
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

$conn->query($createLeadsTable);
