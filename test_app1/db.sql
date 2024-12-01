CREATE TABLE IF NOT EXISTS test_users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  session_code VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS test_todolist (
  task_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  task VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES test_users(user_id)
);

INSERT INTO test_users (username, session_code, email) VALUES
  ('1', 'sessioncode1', 'testuser1@example.com'),
  ('2', 'sessioncode2', 'testuser2@example.com');

INSERT INTO test_todolist (user_id, task) VALUES
  (1, 'Task 1'),
  (1, 'Task 2'),
  (2, 'Task 3');
