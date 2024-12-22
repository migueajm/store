# Modelo Entidad-Relación (ER) para la Gestión de una Tienda de Abarrotes

## 1. Requerimientos de la Base de Datos

### 1.1 Entidades Principales
1. **Usuarios**
2. **Productos**
3. **Categorías**
4. **Ventas**
5. **Proveedores**
6. **Clientes (Opcional)**
7. **VentasDetalles**
8. **Inventarios**

---

## 2. Estructura de las Tablas

### 2.1 Usuarios
Los usuarios incluyen tanto administradores como empleados.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del usuario.          |
| username       | VARCHAR(255)      | Nombre de usuario.                        |
| password_hash  | VARCHAR(255)      | Contraseña en formato hash.                |
| role           | ENUM('admin', 'empleado') | Define si es un administrador o empleado. |
| created_at     | DATETIME          | Fecha y hora de creación del usuario.     |
| updated_at     | DATETIME          | Fecha y hora de la última actualización.  |

---

### 2.2 Productos
Esta tabla almacena los productos disponibles en la tienda.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del producto.         |
| name           | VARCHAR(255)      | Nombre del producto.                       |
| description    | TEXT             | Descripción del producto.                  |
| price          | DECIMAL(10, 2)    | Precio del producto.                       |
| stock_quantity | INT              | Cantidad disponible en stock.              |
| category_id    | INT              | Relación con la categoría del producto.    |
| supplier_id    | INT              | Relación con el proveedor.                 |
| created_at     | DATETIME          | Fecha de creación del producto.            |
| updated_at     | DATETIME          | Fecha de la última actualización.         |

---

### 2.3 Categorías
Las categorías agrupan los productos en tipos (por ejemplo: bebidas, snacks, etc.).

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único de la categoría.      |
| name           | VARCHAR(255)      | Nombre de la categoría.                    |
| description    | TEXT             | Descripción de la categoría.               |
| created_at     | DATETIME          | Fecha de creación de la categoría.         |
| updated_at     | DATETIME          | Fecha de la última actualización.          |

---

### 2.4 Ventas
Esta tabla almacena las ventas realizadas en la tienda.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único de la venta.          |
| user_id        | INT              | Relación con el usuario que realizó la venta (empleado). |
| total_amount   | DECIMAL(10, 2)    | Monto total de la venta.                   |
| sale_date      | DATETIME          | Fecha y hora de la venta.                  |
| payment_method | VARCHAR(50)       | Método de pago utilizado (efectivo, tarjeta, etc.). |

---

### 2.5 Proveedores
Esta tabla almacena la información de los proveedores.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del proveedor.        |
| name           | VARCHAR(255)      | Nombre del proveedor.                      |
| contact_name   | VARCHAR(255)      | Nombre del contacto en el proveedor.       |
| phone_number   | VARCHAR(15)       | Número de teléfono del proveedor.          |
| email          | VARCHAR(255)      | Correo electrónico del proveedor.          |
| address        | TEXT             | Dirección del proveedor.                   |
| created_at     | DATETIME          | Fecha de creación del proveedor.           |
| updated_at     | DATETIME          | Fecha de la última actualización.          |

---

### 2.6 Clientes (Opcional)
Los clientes frecuentes, si es necesario, se pueden registrar en esta tabla.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del cliente.          |
| first_name     | VARCHAR(255)      | Nombre del cliente.                        |
| last_name      | VARCHAR(255)      | Apellido del cliente.                      |
| email          | VARCHAR(255)      | Correo electrónico del cliente.            |
| phone_number   | VARCHAR(15)       | Número de teléfono del cliente.            |
| created_at     | DATETIME          | Fecha de creación del cliente.             |
| updated_at     | DATETIME          | Fecha de la última actualización.          |

---

### 2.7 VentasDetalles
Cada venta puede tener varios productos, por lo que esta tabla relaciona productos con ventas específicas.

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del detalle.          |
| sale_id        | INT              | Relación con la venta.                     |
| product_id     | INT              | Relación con el producto vendido.          |
| quantity       | INT              | Cantidad vendida del producto.             |
| unit_price     | DECIMAL(10, 2)    | Precio unitario del producto en la venta.  |
| total_price    | DECIMAL(10, 2)    | Precio total para ese producto (cantidad * precio unitario). |

---

### 2.8 Inventarios
Esta tabla gestiona los cambios de inventario (entradas y salidas de productos).

| Columna        | Tipo de dato     | Descripción                                |
|----------------|------------------|--------------------------------------------|
| id             | INT AUTO_INCREMENT | Identificador único del movimiento de inventario. |
| product_id     | INT              | Relación con el producto.                  |
| quantity_change| INT              | Cambio en la cantidad (puede ser negativo si es una salida, positivo si es una entrada). |
| reason         | VARCHAR(255)      | Motivo del movimiento (compra, venta, ajuste, etc.). |
| created_at     | DATETIME          | Fecha y hora del movimiento de inventario. |

---

## 3. Relaciones entre Entidades

1. **Usuarios - Ventas**: Un **usuario (empleado)** puede realizar varias **ventas**.
2. **Ventas - VentasDetalles**: Una **venta** puede contener varios **detalles de venta**, con productos vendidos.
3. **Productos - VentasDetalles**: Un **producto** puede estar en muchas **ventas** a través de los **detalles de ventas**.
4. **Productos - Categorías**: Un **producto** pertenece a una **categoría**.
5. **Productos - Proveedores**: Un **producto** es suministrado por un **proveedor**.
6. **Productos - Inventarios**: Un **producto** tiene múltiples registros en **inventarios** debido a cambios en la cantidad.
7. **Clientes - Ventas (opcional)**: Un **cliente** puede realizar muchas **ventas**.

---

## 4. Diagrama de Entidad-Relación (ER)

Visualiza el diagrama de relaciones de la siguiente manera:
``
---

## 5. Consideraciones Adicionales

1. **Índices y Claves Foráneas**:
   - Usa **índices** en columnas que se consultan con frecuencia, como `product_id`, `user_id`, `sale_id`, etc.
   - Asegúrate de definir **claves foráneas** adecuadas entre las tablas para mantener la integridad referencial.

2. **Manejo de Inventarios**:
   - Cada vez que se realice una venta o se reciba un pedido de proveedor, se debe actualizar el inventario.

3. **Optimización**:
   - La base de datos debe estar optimizada para realizar consultas rápidas, especialmente en tablas como **Ventas**, **VentasDetalles** e **Inventarios**.

---

Este es el modelo entidad-relación para la base de datos de la tienda de abarrotes. Puedes adaptarlo según las necesidades de tu aplicación y agregar más detalles o tablas según sea necesario.