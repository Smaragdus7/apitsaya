# API de Modelos Itsaya (PHP Slim Framework) #

Para empezar:

1. Crea una base de datos MySQL con el nombre "apitsaya".
2. Ejecuta apitsaya.sql para crear y llenar la tabla "modelos":

	mysql apitsaya -uroot < apitsaya.sql

3. Implementa la aplicación web incluida en este proyecto.
4. Abre api/index.php. En la función getConnection() en la parte inferior de la página, asegúrate de que los parámetros de conexión coincidan con la configuración de base de datos.
5. Abre main.js y comprueba que la variable RootURL coincide con la configuración de implementación.
6. Accede a la aplicación en tu navegador. Por ejemplo: http://localhost /apitsaya/.