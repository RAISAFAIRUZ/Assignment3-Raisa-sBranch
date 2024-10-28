CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user with hashed password (admin123)
INSERT INTO users (username, password, is_admin) 
VALUES ('admin', '$2y$10$8i5Bx8X9a9luWneTOJ5Ih.QpMdF9sg6c0ZiAk0B5k1YxYQ1tyP6vW', TRUE);
