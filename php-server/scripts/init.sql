CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user with hashed password (admin123)
INSERT INTO users (username, password, is_admin) 
VALUES ('admin', '$2y$10$uTwEZTAZY.J8I0SUvYpD6uG8q4V3Amr/NLiGJTzQRxnGxd2DyS5vO', TRUE);
