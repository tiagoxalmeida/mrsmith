CREATE TABLE users
(
  u_id INT NOT NULL AUTO_INCREMENT,
  u_name VARCHAR(50) NOT NULL,
  u_email VARCHAR(100) NOT NULL,
  u_salt CHAR(32) NOT NULL,
  u_pwd CHAR(64) NOT NULL,
  PRIMARY KEY (u_id)
);

CREATE TABLE connected
(
  c_id INT NOT NULL AUTO_INCREMENT,
  c_last_file VARCHAR(100) NOT NULL,
  c_encrypted BIT NOT NULL,
  c_sender INT NOT NULL,
  c_receiver INT NOT NULL,
  PRIMARY KEY (c_id),
  FOREIGN KEY (c_sender) REFERENCES users(u_id),
  FOREIGN KEY (c_receiver) REFERENCES users(u_id)
);

CREATE TABLE online
(
  u_id INT NOT NULL,
  FOREIGN KEY (u_id) REFERENCES users(u_id)
);

CREATE TABLE request_connection
(
  rc_sender INT NOT NULL,
  rc_receiver INT NOT NULL,
  FOREIGN KEY (rc_sender) REFERENCES users(u_id),
  FOREIGN KEY (rc_receiver) REFERENCES users(u_id)
);