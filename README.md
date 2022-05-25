# Registro de horas trabajadas mediante RFID con Arduino

## Material

* Arduino Uno WiFi Rev 2
* Lector RFID MRC522
* Xampp (MySQL y Apache)

## Clonar repositorio

Para clonar este repositorio y usarlo como plantilla puedes clonarlo con:

```
git clone https://github.com/Ivanx89/empleados Empleados
```

## Estructura

* Arduino
  * programav2.ino
* acceso
  * páginas de inicio de sesión de la web.
* administrador
  * páginas de borrado/creación de la base de datos.
* comunes
  * biblioteca, css y archivos de configuración.
* db
  * tabla-empleados
    * Ficheros de inserción, borrado, modificación de registros.
  * tabla-usuarios
    * Ficheros de inserción, borrado, modificación de registros.
  * tabla-logs
    * Ficheros de inserción, borrado, modificación de registros.
* img
  * Iconos de orden de registros en las páginas de listar.
* getdata.php
  * Página PHP para recoger la información de Arduino y enviarla a la base de datos.
* index.php
  * Página inicial de la plataforma.
