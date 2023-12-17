
/*
	Tipos de datos de MariaDB
  https://www.anerbarrena.com/tipos-dato-mysql-5024/

*/

/*

-- Ejecutarlo desde una terminal de Mysql 
-- Cuando sea la PRIMERA VEZ que se esta generando la base de datos, se debe enttrar como root en la computadora y accesar a "mysql"
		mysql -u root -p

Para ejecutar el "script"
-- Ejecutarlo desde una terminal de Mysql 
-- Se debe accesar al directorio donde se encuentra el "script.sql" y ejecutar el comenado "mysql" desde una terminal


-- $ mysql -u nom-usr -p NombreBaseDatos < script.sql
-- Otra Forma : Es el usuario y contraseña que se definio cuando se creo el usuario (asignando permisos para crear, borrar tablas.)
--    mysql -u usuario -p NombreBaseDatos
--    source script.sql ó \. script.sql
*/


/*
-- Se debe accesar al directorio donde se encuentra el "script.sql" y ejecutar el comanado "mysql" desde una terminal
-- $ mysql -u nom-usr -p NombreBaseDatos < script.sql
-- Otra Forma 
--    mysql -u usuario -p NombreBaseDatos
--    source script.sql ó \. script.sql

			Borrar tabla: DROP TABLE <nombre Tabla>
			Borrar Base Datos : DROP DATABASE <nombre Base Datos>
			Borrar el contenido de la tabla :  truncate table nombre-tabla;
			Borrar un campo dela tabla. : ALTER TABLE t_Rol DROP status;
*/

/*
DROP DATABASE IF EXISTS bd_tienda_virtual;

CREATE DATABASE IF NOT EXISTS bd_tienda_virtual;
  SET time_zone = 'America/Tijuana';  

CREATE USER 'usuario_tienda'@'localhost' IDENTIFIED BY 'Tienda_2022';
GRANT ALL on bd_tienda_virtual.* to 'usuario_tienda'  IDENTIFIED BY 'Tienda_2022';

*/


/* 
Cambiar el nombre de un campo. 

ALTER TABLE `t_Disputes` CHANGE `id_transmitter_dispute` `id_user_dispute` INT UNSIGNED CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

*/

/*
ALTER TABLE t_Permisos DROP W;
ALTER TABLE t_Permisos ADD w TINYINT DEFAULT 0;
ALTER TABLE t_Permisos DROP U;
ALTER TABLE t_Permisos ADD u TINYINT DEFAULT 0;
*/


/*
ALTER TABLE t_Rol DROP status;
ALTER TABLE t_Rol ADD status TEXT DEFAULT 1;
*/

/* Para modificar la longuitud de un campo: 
	ALTER TABLE t_Rep_Finanzas MODIFY COLUMN nombre VARCHAR(50);

*/

/* 
Cambiar el nombre de un campo. 

ALTER TABLE `t_Personas` CHANGE `toke` `token` VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

*/

/*
 Para agregar una columna a la tabla t_Responsivas. 
	ALTER TABLE t_Responsivas ADD fecha_devolucion date NULL;
*/

/*
INSERT INTO t_Cintas (id_cintas,num_serial,fecha_inic,fecha_final,ubicacion,comentarios) VALUES
  (0,'URL30405','2021-01-20','2021-01-23','UBICACION XX','COMENTARIOS VARIOS');
*/

/* Para agregar una columna a la tabla t_Productos . 
	ALTER TABLE t_Productos ADD especificaciones TEXT NULL ;
*/

/*
	USE bd_marketplace;
	ALTER TABLE t_Users ADD token_exp_user TEXT NULL;
*/
