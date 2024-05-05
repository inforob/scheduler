# Proyecto Symfony con Cron

Este proyecto Symfony incluye la configuración de un cron de Unix para ejecutar tareas programadas, junto con los componentes Messenger y Scheduler de Symfony.

## Configuración del Cron

Para configurar el cron en un entorno Unix, sigue estos pasos:

1. Abre el archivo crontab ejecutando `crontab -e` en tu terminal.
2. Agrega la siguiente línea al final del archivo crontab para ejecutar el comando Symfony Console Scheduler:

   ```bash
   * * * * * cd /ruta/a/tu/proyecto && php bin/console schedule:run >> /dev/null 2>&1



# Messenger

composer require symfony/messenger

```
// Ejemplo de envío de mensaje en un controlador
$bus->dispatch(new MiMensaje());

// Ejemplo de manejo de mensaje en un servicio
public function __invoke(MiMensaje $message)
{
    // Manejar el mensaje aquí
}
```