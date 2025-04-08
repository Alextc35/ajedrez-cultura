# Ajedrez Cultura

**Ajedrez Cultura** es una aplicación web desarrollada en PHP puro para gestionar dos ligas de ajedrez amateur organizadas en la Casa de la Cultura de Mejorada del Campo: la **Liga Local** y la **Liga Infantil**.

La herramienta permite a los organizadores llevar el control de los jugadores, resultados y clasificaciones. Además, genera automáticamente un PDF con las clasificaciones actualizadas para que las familias puedan hacer un seguimiento del progreso de los alumnos.

---

## 🚀 Funcionalidades

- Gestión de jugadores y ligas
- Registro de partidas y resultados
- Cálculo automático de clasificaciones
- Generación de PDF con los rankings actualizados

---

## 🛠️ Tecnologías utilizadas

- **PHP** (puro, sin frameworks)
- **MySQL** (gestión de datos)
- **HTML/CSS**
- **TCPDF** (para la generación de PDFs)

---

## 📦 Instalación

1. Clona este repositorio:

   ```bash
   git clone https://github.com/Alextc35/ajedrez-cultura.git
   ```
2. Sube el contenido a tu servidor web (por ejemplo, Apache).
3. Crea una base de datos MySQL y ejecuta el script de creación de tablas con extensión `.sql`.
4. Configura la conexión a la base de datos en `config/config.php`:

    ```php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'tu_usuario');
    define('DB_PASSWORD', 'tu_contraseña');
    define('DB_NAME', 'nombre_de_tu_base_de_datos');
    ```
6. Asegúrate de tener habilitado PHP en tu servidor.

## 📄 Uso

1. Accede a la interfaz web desde tu navegador (`http://localhost/ajedrez-cultura`).
2. Añade jugadores a cada liga.
3. Enfréntalos y registra los resultados de las partidas.
4. Genera un PDF con las clasificaciones desde el botón correspondiente.

---

## 📍 Contexto

Este proyecto nació como una solución sencilla y funcional para apoyar las ligas de ajedrez organizadas por la Casa de la Cultura de Mejorada del Campo. Permite a los participantes y a sus familias estar informados y motivados a lo largo del torneo.

---

## 🤝 Contribuciones

¡Toda ayuda es bienvenida! Puedes proponer mejoras o abrir issues con nuevas ideas o errores encontrados.

---

## 📜 Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.
