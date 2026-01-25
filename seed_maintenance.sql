-- Insert additional sample maintenance data
INSERT INTO maintenances (vehicle_id, description, cost, maintenance_date, status) VALUES 
((SELECT id FROM vehicles LIMIT 1), 'Brake Pad Replacement', 450.00, CURRENT_DATE + INTERVAL '5 days', 'pending'),
((SELECT id FROM vehicles OFFSET 1 LIMIT 1), 'Tire Rotation', 120.00, CURRENT_DATE + INTERVAL '12 days', 'pending'),
((SELECT id FROM vehicles LIMIT 1), 'Annual Inspection', 150.00, CURRENT_DATE + INTERVAL '30 days', 'scheduled');
