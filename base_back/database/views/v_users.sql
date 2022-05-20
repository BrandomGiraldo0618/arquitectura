CREATE OR REPLACE
VIEW v_users AS
    SELECT 
        users.*, COALESCE(roles.id, 0) AS role_id, COALESCE(roles.name, '') AS role_name,
        (CASE WHEN users.active = true THEN 'Activo' ELSE 'Inactivo' END) AS status_name
    FROM users
        LEFT JOIN model_has_roles ON users.id = model_has_roles.model_id
        LEFT JOIN roles ON roles.id = model_has_roles.role_id;