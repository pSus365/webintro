CREATE TABLE vehicles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    mileage INTEGER NOT NULL,
    next_service_date DATE,
    estimated_service_cost NUMERIC(10, 2),
    status VARCHAR(50) CHECK (status IN ('w trasie', 'wolny', 'serwis'))
);

CREATE TABLE drivers (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    birth_date DATE,
    city VARCHAR(100),
    street VARCHAR(100),
    house_number VARCHAR(20),
    employment_date DATE NOT NULL,
    status VARCHAR(50) CHECK (status IN ('wolny', 'w trasie', 'L4'))
);

-- Insert some sample data
INSERT INTO vehicles (name, type, mileage, next_service_date, estimated_service_cost, status) VALUES 
('Truck 1', 'Heavy Duty', 150000, '2024-08-15', 500.00, 'wolny'),
('Van 2', 'Delivery Van', 45000, '2024-08-22', 300.00, 'w trasie'),
('Truck 3', 'Heavy Duty', 180000, '2024-09-05', 700.00, 'serwis');

INSERT INTO drivers (first_name, last_name, birth_date, city, street, house_number, employment_date, status) VALUES 
('Jan', 'Kowalski', '1980-05-15', 'Warsaw', 'Marszałkowska', '10', '2020-01-01', 'wolny'),
('Marek', 'Nowak', '1985-11-20', 'Krakow', 'Floriańska', '15a', '2021-03-10', 'w trasie'),
('Anna', 'Wisniewska', '1990-07-08', 'Gdansk', 'Długa', '5', '2019-11-05', 'L4');

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar_url TEXT DEFAULT NULL
);

INSERT INTO users (name, surname, email, password, avatar_url) VALUES 
('Admin', 'User', 'admin@example.com', '$2y$10$7j.Qj.Qj.Qj.Qj.Qj.Qj.Qe.Qj.Qj.Qj.Qj.Qj.Qj.Qj.Qj.Qj.Qj', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBlhEWZ184-VSOg-zDjeb9KI3eiMMc0_bFY3TZbkTUodvFPWhRKzQykX-PfVQjUYL2N6--bSFQJhLl8T1iC5k8cMBMs7P_wOw-JraVmxhBCKvjOJpqCblLOdotlAfiOKOOTGPKqZHrLS8sAgAyPmy-3rcCSwHqgJhB3YVWrgPxeUyFqfmJwYK5ZTiHGqX6kjYd3ZJkte98_4is-PaifRmOa1n0l9XfFXH67MeOXk8zF3Y4MTVWa_p7x5kcb_hEdMuazrH-iN_XY8IN2');
