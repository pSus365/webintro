-- Add role column to users table
ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'driver';

-- Set existing users to 'admin' (assuming current users are owners)
UPDATE users SET role = 'admin';
