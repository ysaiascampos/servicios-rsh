# Servicios RSH - Capa de Servicios Laravel 7

Capa de servicios para el Registro Social de Hogares, desarrollada en Laravel 7 como migración de la lógica de negocio de las aplicaciones legacy (municipal y ciudadano). Implementa **Pipeline Pattern** para validaciones complejas y **Jobs/Queues** para procesamiento asíncrono.

## 🎯 Propósito

Extraer y centralizar la lógica de negocio distribuida en las aplicaciones Laravel 4.2 (municipal y ciudadano) en una capa de servicios moderna, mantenible y reutilizable.

## 🏗️ Arquitectura

```
┌─────────────────────────────────────────────────────────┐
│              Aplicaciones Legacy (Laravel 4.2)           │
│         rsh-municipal      rsh-ciudadano-ux            │
└─────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────┐
│              Servicios RSH (Laravel 7)                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Parentesco   │  │ Nacionalidad │  │ QR Instituc. │  │
│  │ Service      │  │ Service      │  │ Service      │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
│                                                          │
│  ┌─────────────────────────────────────────────────┐    │
│  │  Pipeline: ReglasCuidadores (20+ reglas)        │    │
│  │  Jobs: GeneracionExcelComuna                    │    │
│  │  Commands: EjecucionExcel                       │    │
│  └─────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────┘
                           │
                           ▼
                    ┌──────────────┐
                    │  Oracle DB   │
                    │ (yajra/oci8) │
                    └──────────────┘
```

## 🛠️ Stack Tecnológico

- **PHP**: 7.2.5+
- **Laravel**: 7.24
- **Oracle**: yajra/laravel-oci8 ^7.0
- **PDF**: barryvdh/laravel-snappy + wkhtmltopdf
- **Excel**: maatwebsite/excel ^3.1
- **HTTP**: guzzlehttp/guzzle ^7.0
- **CORS**: fruitcake/laravel-cors ^2.0
- **QR**: simplesoftwareio/simple-qrcode ^4.1
- **Build**: Laravel Mix 5 (Webpack)
- **Testing**: PHPUnit ^8.5, Mockery ^1.3.1

## 🚀 Instalación

```bash
# Clonar repositorio
git clone <repo-url>
cd servicios-rsh

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar Oracle en .env
DB_CONNECTION=oracle
DB_HOST=your-host
DB_PORT=1521
DB_SERVICE=your-service
DB_USERNAME=your-user
DB_PASSWORD=your-password

# Migraciones
php artisan migrate

# Compilar assets
npm run dev
```

## 🎨 Patrones de Diseño Implementados

### Pipeline Pattern

Validaciones complejas con cadenas de reglas independientes:

```php
// app/Clases/ReglasCuidadores/ClasePipelineCuidadores.php
class ClasePipelineCuidadores {
    private $reglas = [];
    
    public function addRegla(ReglaInterface $regla) {
        $this->reglas[] = $regla;
        return $this;
    }
    
    public function execute($data) {
        foreach ($this->reglas as $regla) {
            $data = $regla->handle($data);
        }
        return $data;
    }
}

// Reglas individuales
class TieneRsh implements ReglaInterface {
    public function handle($data) {
        if (!$this->tieneRsh($data['run'])) {
            throw new ExcepcionReglaNegocio('No posee RSH');
        }
        return $data;
    }
}

class SiesCuidador implements ReglaInterface { }
class SiesDependiente implements ReglaInterface { }
class ValidarCuidadorRsh implements ReglaInterface { }
class ValidarDependienteRsh implements ReglaInterface { }
class ValidarPeriodoRsh implements ReglaInterface { }
class NormalizacionCuidadoresRsh implements ReglaInterface { }
// ... 20+ reglas más
```

### Service Layer Pattern

```php
class ParentescoService {
    private $repository;
    
    public function __construct(ParentescoRepository $repository) {
        $this->repository = $repository;
    }
    
    public function obtenerParentescos() {
        return $this->repository->all();
    }
    
    public function validarParentesco($id) {
        // Lógica de negocio
    }
}
```

### Job/Queue Pattern

```php
// app/Jobs/GeneracionExcelComuna.php
class GeneracionExcelComuna implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $comunaId;
    
    public function __construct($comunaId) {
        $this->comunaId = $comunaId;
    }
    
    public function handle() {
        $datos = DB::table('personas')
            ->where('comuna_id', $this->comunaId)
            ->get();
        
        Excel::store(new PersonasExport($datos), "comuna_{$this->comunaId}.xlsx");
    }
}

// app/Console/Commands/EjecucionExcel.php
class EjecucionExcel extends Command {
    protected $signature = 'excelcom:cron';
    
    public function handle() {
        $comunas = DB::table('comunas_ine')->pluck('COM_DESCRIPCION', 'COM_ID');
        
        foreach ($comunas as $id => $nombre) {
            GeneracionExcelComuna::dispatch($id);
            $this->info("Dispatched: {$nombre}");
        }
    }
}
```

### Custom Exception Hierarchy

```php
abstract class ExcepcionEjecucion extends Exception { }

class ExcepcionConsistencia extends ExcepcionEjecucion { }
class ExcepcionValidacion extends ExcepcionEjecucion { }
class ExcepcionServicio extends ExcepcionEjecucion { }
class ExcepcionReglaNegocio extends ExcepcionEjecucion { }

class ErrorWrapper {
    public string $codigo;
    public string $mensaje;
    public array $detalles;
}

class Logger {
    public static function error(Exception $e, array $context = []) {
        Log::error($e->getMessage(), $context);
    }
}
```

## 📊 Servicios Disponibles

### Catálogo de Servicios (20+)

```
app/Clases/
├── ParentescoService.php
├── NacionalidadService.php
├── QRInstitucionesService.php
├── DatosGlobalesService.php
├── ValidacionIdentidadService.php
├── AdjuntarDocumentoService.php
└── ReglasCuidadores/
    ├── ClasePipelineCuidadores.php
    ├── TieneRsh.php
    ├── SiesCuidador.php
    ├── SiesDependiente.php
    ├── ValidarCuidadorRsh.php
    ├── ValidarDependienteRsh.php
    ├── ValidarPeriodoRsh.php
    ├── NormalizacionCuidadoresRsh.php
    ├── FiltrarSolicitudesAprobadas.php
    └── ... (20+ reglas más)
```

## 🗄️ Modelos Eloquent (60+)

```php
// Modelos principales
class Personas extends Model { }
class Solicitud extends Model { }
class Ficha extends Model { }
class GrupoFamiliar extends Model { }
class BeneficiarioGrupoFamiliar extends Model { }
class Vehiculo extends Model { }
class Usuario extends Model { }
class UsuarioComuna extends Model { }
class TramoQuintil extends Model { }
class TipoSolicitud extends Model { }
class VWPropiedad extends Model { }
class Visita extends Model { }
class ValorUf extends Model { }

// Modelos de supervisión
class SupervisionMensajes extends Model { }
class SupervisionVisitas extends Model { }

// ... 60+ modelos en total
```

## 🧪 Testing

```bash
# Ejecutar tests
vendor/bin/phpunit

# Feature tests
vendor/bin/phpunit tests/Feature

# Tests específicos
vendor/bin/phpunit --filter DatosGlobalesTest
```

## 📦 Generación de Reportes

### Excel (Jobs asíncronos)

```bash
# Ejecutar generación de reportes
php artisan excelcom:cron

# Esto dispara jobs en cola para cada comuna
```

### PDF

```php
use Barryvdh\Snappy\Facades\SnappyPdf;

$pdf = SnappyPdf::loadView('reportes.comuna', $data);
return $pdf->download("reporte_{$comunaId}.pdf");
```

## 🔧 Comandos Artisan Personalizados

```bash
# Generación masiva de reportes Excel
php artisan excelcom:cron

# Otros comandos disponibles
php artisan list | grep "rsh"
```

## 🌐 Integraciones

### SOAP (Legacy)

```php
// app/WSCertificadoNacimiento.php
class WSCertificadoNacimiento {
    public static function obtener($run) {
        $ws = new SoapClient(env('WS_CERTIFICADO_URL'));
        $result = $ws->obtenerCertificado(['rut' => $run]);
        return $result->certificado;
    }
}
```

### Guzzle HTTP Client

```php
use GuzzleHttp\Client;

class DatosGlobalesService {
    public function consultarPersona($run) {
        $client = new Client([
            'base_uri' => env('API_DATOS_GLOBALES_URL'),
            'timeout' => 30,
        ]);
        
        $response = $client->get("/api/v1/persona/{$run}", [
            'headers' => [
                'Api-Token' => env('API_TOKEN'),
            ],
        ]);
        
        return json_decode($response->getBody(), true);
    }
}
```

## 🐛 Debugging

### Log Viewer

```
/desarrollo/logs/{token}
```

### IDE Helper

```bash
# Generar helpers para IDE
php artisan ide-helper:generate
php artisan ide-helper:models
```

## 📝 Notas de Desarrollo

### Migración desde Laravel 4.2

**Cambios principales:**
- Classmap autoloading → PSR-4
- Filters → Middleware
- `app/routes.php` → `routes/web.php`
- `app/config/app.php` → `config/app.php`
- PHP 5.6 → PHP 7.2.5+

### Compatibilidad

Los servicios están diseñados para ser consumidos por:
- `rsh-municipal` (Laravel 4.2)
- `rsh-ciudadano-ux` (Laravel 4.2)
- Futuras aplicaciones Laravel 7+

## 👤 Autor

**Ysaías Alfonso Pérez Campos**  
Senior Backend Engineer  
Email: ysaiascampos@gmail.com  
LinkedIn: https://www.linkedin.com/in/ysaias-perez-campos-50626116a/

## 📄 Licencia

Proyecto desarrollado para el Gobierno de Chile - Registro Social de Hogares.
