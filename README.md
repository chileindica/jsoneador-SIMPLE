#jsoneador-SIMPLE

jsoneador-SIMPLE permite generar rápidamente una API de consulta que expone las tablas de una base de datos.

Los motores de bases de datos disponibles son:
- SQLite
- MySQL
- PostgreSQL

Provee una API REST que mapea directamente contra la estructura de la base de datos, con una configuración muy básica.

Imaginemos que tenemos el sistema montado en `http://api.ejemplo.com` y una tabla llamada `clientes`.

Para obtener la lista de clientes simplemente hacemos:

	GET http://api.ejemplo.com/clientes/

Siendo `clientes` el nombre de la tabla. La respuesta será una estructura JSON con un array de todos los clientes.

Si necesitas sólo obtener un cliente, puede acceder por el `id` desde la URL:

	GET http://api.ejemplo.com/clientes/123/

##Requisitos

- PHP 5.3+ & PDO
- SQLite / MySQL / PostgreSQL

##Instalación

Renombra el archivo config.sample.php como config.php y modifica la variable `$dsn`:

- SQLite: `$dsn = 'sqlite://./path/to/database.sqlite';`
- MySQL: `$dsn = 'mysql://[user[:pass]@]host[:port]/db/;`
- PostgreSQL: `$dsn = 'pgsql://[user[:pass]@]host[:port]/db/;`

Ejemplos:

- SQLite: `$dsn = 'sqlite://./usr/admin/database.sqlite';`
- MySQL: `$dsn = 'mysql://mysql://usuario:password@localhost/nombrebasededatos/;`
- PostgreSQL: `$dsn = 'pgsql://usuario:password@localhost/nombrebasededatos/;`

Se recomienda crear un usuario de la base de datos especial para este servicio y con acceso de sólo lectura a las tablas.

De usar Apache, se debe contar con la extensión `mod_rewrite` habilitada, dado que se utiliza en el archivo `.htaccess` :

```apache
<IfModule mod_rewrite.c>
	RewriteEngine	On
	RewriteCond		%{REQUEST_FILENAME}	!-d
	RewriteCond		%{REQUEST_FILENAME}	!-f
	RewriteRule		^(.*)$ index.php/$1	[L,QSA]
</IfModule>
```

***Nota bene:*** You must access the file directly, including it from another file won't work.

##Seguridad

Si quieres segurizar tu servicio tienes dos métodos disponibles. Ambos pueden ser utilizados por separado o convivir juntos (recomendado);

- Se puede restringir el acceso a IP's específicas completando la variable `$clients`

```php
$clients = array
(
	'127.0.0.1',
	'127.0.0.2',
	'127.0.0.3',
);
```

- Por otra parte, se puede generar un token de acceso que deberá ser enviado en cada request para ser validado:

```php
$token = '13aJlFuu5Sh4Kzm1Sr8uo5ChPr8obJLA';
```

##ENDPOINTS DE LA API
Con esta API se permite leer cualquier registro de cualquier tabla que exista en la base de datos configurada. Los endpoints son:

	(R)ead   > GET    /table[/id]
	(R)ead   > GET    /table[/column/content]

##PARÁMETROS
Para filtrar la búsqueda se pueden enviar ciertos parámetros vía GET:

- `by` (columna por la que se quiere ordenar)
  - `order` (dirección del orden: `ASC` or `DESC`)
- `limit` (`LIMIT x` SQL clause)
  - `offset` (`OFFSET x` SQL clause)

##EJEMPLOS

To put this into practice below are some example of how you would use the ArrestDB API:

	# Get all rows from the "customers" table
	GET http://api.example.com/customers/

	# Get a single row from the "customers" table (where "123" is the ID plus using token)
	GET http://api.example.com/customers/123/?token="blablabla"

	# Get all rows from the "customers" table where the "country" field matches "Australia" (`LIKE`)
	GET http://api.example.com/customers/country/Australia/

	# Get 50 rows from the "customers" table
	GET http://api.example.com/customers/?limit=50

	# Get 50 rows from the "customers" table ordered by the "date" field
	GET http://api.example.com/customers/?limit=50&by=date&order=desc

##Respuestas

Todas las respuestas son en formato JSON:

```json
[
    {
        "id": "114",
        "customerName": "Australian Collectors, Co.",
        "contactLastName": "Ferguson",
        "contactFirstName": "Peter",
        "phone": "123456",
        "addressLine1": "636 St Kilda Road",
        "addressLine2": "Level 3",
        "city": "Melbourne",
        "state": "Victoria",
        "postalCode": "3004",
        "country": "Australia",
        "salesRepEmployeeNumber": "1611",
        "creditLimit": "117300"
    },
    ...
]
```

Los errores tienen el siguiente formato:

```json
{
    "error": {
        "code": 400,
        "status": "Bad Request"
    }
}
```

Los mensajes de respuesta posibles son los siguientes:

* `200` OK
* `201` Created
* `204` No Content
* `400` Bad Request
* `403` Forbidden (por token inválido o por IP restringida)
* `404` Not Found
* `409` Conflict
* `503` Service Unavailable

#JSONP

Agregando el parámetro `callback` la respuesta válida para llamadas  [JSON-P response](http://en.wikipedia.org/wiki/JSONP):

```javascript
callback(JSON);
```

##Credits

jsoneador-SIMPLE es una porción de código modificada por basada en [ArrestDB](https://github.com/alixaxel/ArrestDB)

ArrestDB is a complete rewrite of [Arrest-MySQL](https://github.com/gilbitron/Arrest-MySQL) with several optimizations and additional features.

##License (MIT)


