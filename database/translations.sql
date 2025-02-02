CREATE TABLE translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    locale_id INT NOT NULL,
    key_name VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    tags JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (locale_id) REFERENCES locales(id) ON DELETE CASCADE
);