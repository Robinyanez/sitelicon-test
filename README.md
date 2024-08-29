# Sitelicon Test Api (PayPal)
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Levantamiento de proyecto

Se procede a trabajar con la aplicación **laragon** para desplegar las herramientas como **PHP** en su versión 8.2 en adelante, **Nginx** y **Mysql** en su versión 8 en adelante.

Nos ayuda y facilita el despliegue rapido para las aplicaciones **Laravel** asi como otros entornos de desarrollo.

Tambien nos proporciona certificados ssl y ayuda con la creación automatica de un **Host Name** para nuesta aplicación ejemplo: **sitelicon-test.test**.


## Clonación del proyecto

Para clonar el repositorio de **Sitelicon Test Api**, realizar un **git clone** de la rama de development ya que aqui se encuentra el test, se puede usar los siguientes comandos.

        -  git clone https://github.com/Robinyanez/sitelicon-test.git

## Instalacion de paquetes

Luego de clonar el repositorio de **Sitelicon Test Api** seguir lo siguientes paso para que todo funcione correctamente, todos los siguientes comando tienen que ser ejecutados por linea de comandos en la raiz del proyecto que se clono.

Ejecutar **composer install**, esto instalara las dependecias necesarias para que laravel funcione.

Ejecutar el comando **cp .env.example .env** para copiar el .env de ejemplo y posteriormente llenar los datos que correspondan (solicitar al encargado).

En caso de tener problemas para iniciar el proyecto debido a una key erronea ejecutar **php artisan key:generate** para obtener una nueva clave para el proyecto.

Crear una base de datos y configurar los parametros dentro del archivo .env para luego poder ejecutar **php artisan migrate**, con esto generaremos las tablas necesarias para el proyecto.

**Observaciones**

No hace falta crear la base de datos en **Laravel 11** ya que este con estar los parametros de conexion automatimante genera la base de datos y sus tablas.

Luego de esto tambien ejecutar **php artisan db:seed**, lo que nos ayudara a tener los datos de prueba como los usuarios y los productos.

Se necesita que se tenga una **Client ID** y **Secret ID** del sitio [Paypal Developer](https://developer.paypal.com/home/), ya que como es un test se utiliza el modo **SANDBOX** luego de obtener las credenciales copiamos estas variables en el .env con sus respentivos valores.

        - PAYPAL_MODE=
        - PAYPAL_SANDBOX_CLIENT_ID=
        - PAYPAL_SANDBOX_CLIENT_SECRET=

# Explicación de funcionamiento del proyecto.

Se explica de manera rapida el funcionamiento de las apis para lo solicitado en la prueba.

Para el manejor de token se utilizo **Sanctum** ya que este es apropiado para este test ya que facil de utilizar y ligero a su vez.

## Api Auth

Sirve para la obtención del token de autenticación para el resto de apis ya que todas estan protegidas con el **middleware auth:sanctum**.

        - Login
        - https://sitelicon-test.test/api/auth/login
        - POST

Usuarios creados con los **seeders** todos tienes el **Pass:** password:

Request:

        {
            "email": "fahey.lelia@example.net",
            "password": "password"
        }

Response:

        {
            "message": "success",
            "data": {
                "user_id": 1,
                "token_type": "Bearer Token",
                "access_token": "1|yupNhVACPgx5tossr8KHquvWqC6v4XOoFL6llOhy9b66c75d"
            },
            "status": 200
        }

**Observaciones**

El token valido viene hacer luego del **1|**

## Api Productos

### 1. List

Sirve para obtener los registro de **productos** con sus respectivos datos y status.

        - http://sitelicon-test.test/api/product/list
        - GET

## Api Ordenes

### 1. Create

Sirve para realizar el guardado de los datos desde la **Api** de **OpenWeatherMap** en nuestra base de datos.

La **Api** esta creada con el parametro **product_id** productos que fueron generados por los **seeders**.

El registro se guardar con un estado de **Pendiente** tanto para la orden como para el pago.

        - http://sitelicon-test.test/api/orden/create
        - POST

Request:

        {
            "product_id": 1
        }

Response:

        {
            "message": "success",
            "data": {
                "order_id": 1,
                "payment_id": 1
            },
            "status": 200
        }

### 2. Show

Sirve para obtener un registro con sus respectivos datos y status.

        - http://sitelicon-test.test/api/orden/show/{orderId}
        - GET

## Api Pagos

### 1. Confirmar

Sirve para confirmar si el pago es seguro y confiable.

Los campos **order_id** y **payment_id** son la respuesta de la api de crear ordenes.

        - http://sitelicon-test.test/api/orden/confirmation
        - POST

Request:

        {
            "order_id": 1,
            "payment_id": 1,
            "payment_source": {
                "card": {
                    "number": "4111111111111111",
                    "expiry": "2035-12"
                }
            }
        }

Response:

        {
            "message": "success",
            "data": "confirmed",
            "status": 200
        }

### 2. Pagar

Sirve para capturar el estado del pago y asi validar que el mismo sea correcto y confiable.

Los campos **order_id** y **payment_id** son la respuesta de la api de crear ordenes.

        - http://sitelicon-test.test/api/orden/payment
        - POST

Request:

        {
            "order_id": 1,
            "payment_id": 1
        }

Response:

        {
            "message": "success",
            "data": "paid",
            "status": 200
        }

# Observaciones

En caso de tener un error asi en el log **Route [login] not defined**. de debe agregar a los **headers** de la api en **Postman** el **Accept: application/json**.

La url base **http://sitelicon-test.test** puede variar segun el lugar y como se clone el proyecto.

Tambien se adjunta el archivo postman dentro del prouecto para las pruebas.
