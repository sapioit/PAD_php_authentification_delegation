-- Create tables for the dissertation app
CREATE TABLE IF NOT EXISTS users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  session_code VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS websites (
  website_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  website_name VARCHAR(100) NOT NULL,
  website_url VARCHAR(255) NOT NULL,
  post_url VARCHAR(255) NOT NULL,
  redirect_url VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessions (
  session_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  website_id INT NOT NULL,
  session_code VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (website_id) REFERENCES websites(website_id) ON DELETE CASCADE
);

-- Create tables for the first test app
CREATE TABLE IF NOT EXISTS atest1_users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  session_code VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS atest1_todolist (
  task_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  task VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES atest1_users(user_id) ON DELETE CASCADE
);

-- Create tables for the second test app
CREATE TABLE IF NOT EXISTS atest2_users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  session_code VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS atest2_todolist (
  task_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  task VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES atest2_users(user_id) ON DELETE CASCADE
);



-- Insert sample data for the dissertation app
INSERT INTO users (username, password, email, session_code) VALUES
  ('a1', '1', 'user1@example.com', 'sessioncode1'),
  ('a2', '2', 'user2@example.com', 'sessioncode2'),
  ('a3', '3', 'user3@example.com', 'sessioncode3'),
  ('a4', '4', 'user4@example.com', 'sessioncode4');

INSERT INTO websites (user_id, website_name, website_url, post_url, redirect_url) VALUES
  (1, 'Website 1', 'test_app1/', 'test_app1/auth.php', 'test_app1/remote_login.php'),
  (2, 'Website 2', 'test_app2/', 'test_app2/auth.php', 'test_app2/remote_login.php'),
  (3, 'Website 1 again', 'test_app1/', 'test_app1/auth.php', 'test_app1/remote_login.php'),
  (3, 'Website 2 again', 'test_app2/', 'test_app2/auth.php', 'test_app2/remote_login.php');

INSERT INTO sessions (user_id, website_id, session_code) VALUES
-- user_id: 1
  (1, 1, 'sessioncode1'),
-- user_id: 2
  (2, 2, 'sessioncode2'),
  (2, 3, 'sessioncode2'),
  (2, 4, 'sessioncode5'),
-- user_id: 3
  (3, 3, 'sessioncode3'),
  (3, 4, 'sessioncode3');
-- user_id: 4
-- website_id: 1
-- (1, 1, 'sessioncode1'),
-- website_id: 2
-- (2, 2, 'sessioncode2'),
-- (4, 2, 'sessioncode4'),
-- website_id: 3
-- (2, 3, 'sessioncode2'),
-- (3, 3, 'sessioncode3'),
-- (4, 3, 'sessioncode5');
-- website_id: 4

-- Insert sample data for the first test app
INSERT INTO atest1_users (username, session_code, email) VALUES
  ('a1', 'sessioncode1', 'testuser1@example.com'),
  ('a2', 'sessioncode2', 'testuser2@example.com'),
  ('a3', 'sessioncode3', 'testuser3@example.com');

INSERT INTO atest1_todolist (user_id, task) VALUES
  (1, 'Task 1'),
  (1, 'Task 2'),
  (2, 'Task 3');


-- Insert sample data for the second test app
INSERT INTO atest2_users (username, session_code, email) VALUES
  ('a2', 'sessioncode2', 'testuser2@example.com'),
  ('a3', 'sessioncode3', 'testuser3@example.com');

INSERT INTO atest2_todolist (user_id, task) VALUES
  (1, 'Task 1');