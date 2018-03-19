CREATE TABLE IF NOT EXISTS :prefix_migrations (
  id       INTEGER PRIMARY KEY,
  date     DATE         NOT NULL,
  filename VARCHAR(255) UNIQUE,
  name     VARCHAR(255) NOT NULL,
  info  text
);


