create database event_booking;
CREATE TABLE participation (
  participation_id INT PRIMARY KEY,
  employee_name VARCHAR(255),
  employee_mail VARCHAR(255),
  event_id INT,
  event_name VARCHAR(255),
  participation_fee DECIMAL(10, 2),
  event_date DATE,
  version VARCHAR(20)
);