# 🎯 ProyectoEcuador

**ProyectoEcuador** es una plataforma web diseñada para la **gestión y promoción de actividades en Ecuador**, con una arquitectura moderna de **dos capas**:

* **Backend API RESTful**: Encargada de la lógica de negocio, operaciones CRUD y comunicación con la base de datos.
* **Frontend CMS Administrativo**: Panel web para la administración de contenido, sorteos y usuarios.

---

## 🏗️ Arquitectura del Sistema

### **Backend API**

* **Punto de entrada:** `api/index.php` para todas las peticiones HTTP.
* **Rutas dinámicas:** Soporte para `GET`, `POST`, `PUT`, `DELETE`.
* **Autenticación segura:** API keys, tokens JWT y validación de acceso.
* **Modelos de datos:** Operaciones CRUD con clases como `GetModel`, `PostModel`, `PutModel`, `DeleteModel`.

### **Frontend CMS**

* **Interfaz AJAX:** Gestión dinámica de tablas, formularios y archivos.
* **Comunicación con API:** Uso de `CurlController` para interactuar con el backend.
* **Panel administrativo:** Interfaces para gestionar sorteos, participantes y contenido.

---

## ⚙️ Tecnologías Principales

* **Backend:** PHP 8+, MySQL con conexiones PDO preparadas.
* **Frontend:** Bootstrap 5, jQuery y AJAX.
* **Autenticación:** `firebase/php-jwt` para tokens JWT.
* **Emailing:** `PHPMailer` para notificaciones y confirmaciones.
* **Variables de entorno:** `vlucas/phpdotenv`.

---

## 🔐 Características de Seguridad

* Autenticación por **API Key**.
* Tokens JWT con expiración de 24 horas.
* **Rate Limiting** para prevenir abuso de API.

---

## 📂 Estructura del Proyecto

```
ProyectoEcuador/  
├── api/                 # Backend API RESTful  
├── cms/                 # Panel administrativo  
├── web/                 # Sitio público del proyecto
```

---

## 🌟 Propósito

ProyectoEcuador está diseñado para facilitar la organización de **sorteos legales y transparentes**, ofreciendo:

* Administración de rifas y premios.
* Control de participantes.
* Comunicación segura con clientes y aplicaciones externas.
