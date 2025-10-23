# Hidden Clue

**Proyecto web de reservas de salas de escape room**

Hidden Clue es una pagina web que permite a los usuarios reservar salas.  

## Características

- Registro y login de usuarios.
- Reservas de salas.
- Resumen de relleno de datos del formulario de reserva
- Confirmación de reservas con PDF.
- Envío de correos electrónicos de confirmación.
- Cambio de datos de usuario.
- Demostración de reservas que ha hecho el usuario
- Contacto con el administrador de Hidden Clue
- Una sección de FAQ

## Tecnologías

- PHP
- MySQL
- JavaScript
- CSS
- PHPMailer
- domPDF
- vendor (para dependencias)

## Estructura de carpetas

- conexion/ → Archivos de conexión a la base de datos y PHPMailer.
- config/ → Configuración general del proyecto.
- css/ → Estilos CSS.
- js/ → Archivos JavaScript.
- ImagenesDeSalas/ → Imágenes de las salas.
- includes/ → Scripts PHP de funciones específicas.
- vendor/ → Dependencias de Composer (no se sube, se genera con composer install).

## Explicación de archivos
  ### - conexion/ → Contiene los archivos para la conexión a la base de datos y la gestión de correos electrónicos con PHPMailer.
  ####   · conexion.php → Conexión principal a la base de datos.
  ####   · Conexion_Gmail.php → Configuración y envío de correos vía Gmail.
  ####   · src/ → Archivos de PHPMailer: PHPMailer.php, SMTP.php, POP3.php, OAuth.php, OAuthTokenProvider.php, Exception.php.
  ### - config/ → Configuración general del proyecto.
  ####   · config.php → Parámetros globales de configuración (base de datos, rutas, etc.).
  ### - css/ → Hojas de estilo del proyecto. Cada archivo corresponde a una página o sección: contacto.css, faq.css, index.css, login.css, perfil.css, registro.css, reservar.css, salas.css.
  ### - js/ → Scripts de JavaScript para interactividad y animaciones: contacto.js, faq.js, index.js, login.js, perfil.js, registro.js, reservar.js, salas.js.
  ### - ImagenesDeSalas/ → Imágenes de las salas de escape room.
  ### - img/ → Logo de Hidden Clue: Logo_Hidden_Clue.png.
  ### - includes/ → Scripts PHP con funciones específicas del proyecto:
  ####   · confirmar_reserva.php → Confirma la reserva y genera PDF.
  ####   · filtracion_ordenacion_salas.php → Filtros y ordenación de salas.
  ####   · generar_pdf.php → Genera PDFs con los datos de reserva.
  ####   · horas_disponibles.php → Calcula horas disponibles de cada sala.
  ####   · logout.php → Cierra sesión del usuario.
  ####   · procesar_cambio_de_datos_de_perfil.php → Actualiza los datos del usuario.
  ####   · procesar_contacto.php → Envía los mensajes de contacto al administrador.
  ####   · procesar_login.php → Valida el login del usuario.
  ####   · procesar_registro.php → Valida y registra usuarios.
  ####   · relleno_formulario_de_datos.php → Guarda los datos introducidos en el formulario de reserva.
  ### - vendor/ → Dependencias de Composer (PHPMailer, domPDF, etc.). No se sube al repositorio; se genera con composer install.
  ### .env → Variables de entorno (credenciales de la base de datos y Gmail).
  ### composer.json y composer.lock → Gestión de dependencias de Composer.
  ### Páginas principales del proyecto:
  #### contacto.php, faq.php, index.php, login.php, perfil.php, registro.php, reserva_exitosa.php, reservar.php, ResumenDeReserva.php, salas.php.

### Autor
Ismael Astillero García Muñoz
