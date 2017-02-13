INSERT INTO COUNTRY(name, created_at, updated_at, active) VALUES("México", NOW(), NOW(), 1);

INSERT INTO state(name, idCountry, created_at, updated_at, active) VALUES("Nuevo León", 1, NOW(), NOW(), 1);

INSERT INTO city(name, idState, created_at, updated_at, active) VALUES("Monterrey", 1, NOW(), NOW(), 1);