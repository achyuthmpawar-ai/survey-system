CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

CREATE TABLE surveys (
    id VARCHAR(80) PRIMARY KEY,
    title VARCHAR(255),
    slug VARCHAR(255),
    is_active TINYINT DEFAULT 1
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    survey_id VARCHAR(80),
    question TEXT,
    correct_answer TEXT,
    options TEXT
);

CREATE TABLE responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    survey_id VARCHAR(80),
    question_id INT,
    answer TEXT
);