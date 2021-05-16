CREATE TABLE users
(
  u_id INT NOT NULL AUTO_INCREMENT,
  u_name VARCHAR(30) NOT NULL,
  u_email VARCHAR(100) NOT NULL,
  u_salt CHAR(32) NOT NULL,
  u_pwd CHAR(64) NOT NULL,
  u_public_encrypt_key VARCHAR(2000) NOT NULL DEFAULT '',
  u_private_encrypt_key VARCHAR(2000) NOT NULL DEFAULT '',
  u_public_sign_key VARCHAR(2000) NOT NULL,
  u_private_sign_key VARCHAR(2000) NOT NULL,
  PRIMARY KEY (u_id),
  UNIQUE (u_name),
  UNIQUE (u_email)
);

CREATE TABLE online
(
  u_id INT NOT NULL,
  PRIMARY KEY (u_id),
  FOREIGN KEY (u_id) REFERENCES users(u_id) ON DELETE CASCADE
);

CREATE TABLE request_connection
(
  rc_sender INT NOT NULL,
  rc_receiver INT NOT NULL,
  PRIMARY KEY (rc_sender, rc_receiver),
  FOREIGN KEY (rc_sender) REFERENCES online(u_id)  ON DELETE CASCADE,
  FOREIGN KEY (rc_receiver) REFERENCES online(u_id) ON DELETE CASCADE
);

CREATE TABLE connected
(
  c_last_file VARCHAR(2000) NOT NULL DEFAULT '',
  c_encrypted BIT NOT NULL DEFAULT 0,
  c_last_file_ext VARCHAR(5) NOT NULL DEFAULT '',
  c_options VARCHAR(200) NOT NULL DEFAULT '',
  c_sender INT NOT NULL,
  c_receiver INT NOT NULL,
  PRIMARY KEY (c_sender, c_receiver),
  FOREIGN KEY (c_sender) REFERENCES online(u_id)  ON DELETE CASCADE,
  FOREIGN KEY (c_receiver) REFERENCES online(u_id)  ON DELETE CASCADE
);