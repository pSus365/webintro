-- Migration to add advanced DB features

-- 1. Create assignments table (N:M relation between Drivers and Vehicles)
CREATE TABLE IF NOT EXISTS assignments (
    id SERIAL PRIMARY KEY,
    driver_id INTEGER NOT NULL REFERENCES drivers(id) ON DELETE CASCADE,
    vehicle_id INTEGER NOT NULL REFERENCES vehicles(id) ON DELETE CASCADE,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP,
    notes TEXT
);

-- 2. Create maintenances table (1:N relation Vehicles -> Maintenances)
CREATE TABLE IF NOT EXISTS maintenances (
    id SERIAL PRIMARY KEY,
    vehicle_id INTEGER NOT NULL REFERENCES vehicles(id) ON DELETE CASCADE,
    description TEXT NOT NULL,
    cost NUMERIC(10, 2) NOT NULL,
    maintenance_date DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'completed'
);

-- 3. Create user_settings table (1:1 relation Users -> Settings)
CREATE TABLE IF NOT EXISTS user_settings (
    user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    theme VARCHAR(20) DEFAULT 'light',
    notifications_enabled BOOLEAN DEFAULT TRUE
);

-- 4. VIEW: v_fleet_summary (Join Vehicles + Assignments)
CREATE OR REPLACE VIEW v_fleet_summary AS
SELECT 
    v.name AS vehicle_name,
    v.type AS vehicle_type,
    v.status AS vehicle_status,
    COUNT(a.id) AS total_assignments
FROM vehicles v
LEFT JOIN assignments a ON v.id = a.vehicle_id
GROUP BY v.id, v.name, v.type, v.status;

-- 5. VIEW: v_maintenance_costs (Join Vehicles + Maintenances)
CREATE OR REPLACE VIEW v_maintenance_costs AS
SELECT 
    v.name AS vehicle_name,
    COALESCE(SUM(m.cost), 0) AS total_maintenance_cost
FROM vehicles v
LEFT JOIN maintenances m ON v.id = m.vehicle_id
GROUP BY v.id, v.name;

-- 6. FUNCTION: calculate_total_maintenance_cost(vehicle_id)
CREATE OR REPLACE FUNCTION calculate_total_maintenance_cost(p_vehicle_id INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    total NUMERIC;
BEGIN
    SELECT COALESCE(SUM(cost), 0) INTO total
    FROM maintenances
    WHERE vehicle_id = p_vehicle_id;
    return total;
END;
$$ LANGUAGE plpgsql;

-- 7. TRIGGER FUNCTION: update_status_on_assignment
CREATE OR REPLACE FUNCTION update_status_on_assignment()
RETURNS TRIGGER AS $$
BEGIN
    -- Update vehicle status to 'w trasie' when assigned
    IF TG_OP = 'INSERT' AND NEW.end_date IS NULL THEN
        UPDATE vehicles SET status = 'w trasie' WHERE id = NEW.vehicle_id;
        UPDATE drivers SET status = 'w trasie' WHERE id = NEW.driver_id;
    END IF;
    
    -- Update vehicle status to 'wolny' when assignment ends
    IF TG_OP = 'UPDATE' AND OLD.end_date IS NULL AND NEW.end_date IS NOT NULL THEN
        UPDATE vehicles SET status = 'wolny' WHERE id = NEW.vehicle_id;
        UPDATE drivers SET status = 'wolny' WHERE id = NEW.driver_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- 8. TRIGGER: Create trigger on assignments
DROP TRIGGER IF EXISTS trg_update_status_on_assignment ON assignments;
CREATE TRIGGER trg_update_status_on_assignment
AFTER INSERT OR UPDATE ON assignments
FOR EACH ROW
EXECUTE FUNCTION update_status_on_assignment();

-- Insert Sample Data for new tables
INSERT INTO assignments (driver_id, vehicle_id, start_date) 
SELECT d.id, v.id, NOW() 
FROM drivers d, vehicles v 
WHERE d.status = 'w trasie' AND v.status = 'w trasie' 
LIMIT 1;

INSERT INTO maintenances (vehicle_id, description, cost, maintenance_date)
SELECT id, 'Oil Change', 250.00, '2024-01-10' FROM vehicles LIMIT 1;

INSERT INTO user_settings (user_id, theme)
SELECT id, 'dark' FROM users LIMIT 1;
