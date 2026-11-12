<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | Bearer token required to access the validation API endpoint.
    | This value should match the VALIDATOR_API_TOKEN in your .env file.
    |
    */
    'api_token' => env('VALIDATOR_API_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Connection
    |--------------------------------------------------------------------------
    |
    | The default database connection name to use for Oracle validations
    | when no connection is specified in the validation request.
    |
    */
    'default_connection' => env('VALIDATOR_DEFAULT_CONNECTION', 'oracle'),

    /*
    |--------------------------------------------------------------------------
    | Connection Schema Mapping
    |--------------------------------------------------------------------------
    |
    | Maps each database connection name to the Oracle schema (database user)
    | that should be validated when that connection is used. The schema is
    | typically the DB_USERNAME of each connection defined in your .env file.
    |
    | Example .env:
    |
    |   #ASIGNACION
    |   DB_CONNECTION=oracle
    |   DB_USERNAME=ASIGNACION
    |
    |   #EXPORT
    |   DB_EXPORT_CONNECTION=oracle
    |   DB_EXPORT_USERNAME=ASIGNACION_EXPORT
    |
    | Config:
    |
    |   'connections' => array(
    |       'oracle'        => env('DB_USERNAME', ''),
    |       'oracle_export' => env('DB_EXPORT_USERNAME', ''),
    |   ),
    |
    | If a connection has no entry here and no schemas are provided in the
    | request, the validator will use the schema of the current connected user.
    |
    */
    'connections' => array(
        'oracle'        => env('DB_USERNAME', ''),
        'oracle_export' => env('DB_EXPORT_USERNAME', ''),
    ),

    /*
    |--------------------------------------------------------------------------
    | Validators
    |--------------------------------------------------------------------------
    |
    | List of enabled validator classes. If empty, all validators implementing
    | ValidatorInterface under the Validators namespace will be auto-discovered.
    |
    */
    'validators' => array(),

    /*
    |--------------------------------------------------------------------------
    | Database Validation Settings
    |--------------------------------------------------------------------------
    |
    | Everything that must be reviewed in the database is declared here; it is
    | no longer accepted from the API request. Object names are relative to the
    | schema key (fully qualified names are accepted too).
    |
    */
    'database' => array(

        /*
        | Connections (config/database.php) to validate. Empty = only
        | "default_connection". Each connection is mapped to its schema through
        | the "connections" key at the root of this file.
        */
        'connections' => array(),

        /*
        | Expected database structure, nested by schema. Supported keys:
        | tables (with columns/primary_keys/indexes/foreign_keys/constraints),
        | columns, column_types, column_lengths, nullable, defaults, functions,
        | procedures, sequences, views, triggers, packages, synonyms, indexes,
        | primary_keys, foreign_keys and constraints.
        |
        | Example:
        |
        | 'ASIGNACION' => array(
        |     'tables' => array(
        |         'PERSONAS' => array(
        |             'columns' => array(
        |                 'RUT' => array('type' => 'VARCHAR2', 'length' => 10, 'nullable' => false),
        |             ),
        |             'primary_keys' => true,
        |             'indexes' => array('IDX_PERSONAS_RUT'),
        |         ),
        |     ),
        |     'functions' => array('GET_PERSONA'),
        | ),
        */
        'expected_schemas' => array(),

        /*
        | Optional flat checklist (merged with expected_schemas), with fully
        | qualified names:
        |
        | 'checklist' => array(
        |     'schemas'       => array('ASIGNACION'),
        |     'tables'        => array('ASIGNACION.PERSONAS'),
        |     'columns'       => array('ASIGNACION.PERSONAS.RUT'),
        |     'column_types'  => array('ASIGNACION.PERSONAS.RUT' => 'VARCHAR2'),
        |     'column_lengths'=> array('ASIGNACION.PERSONAS.RUT' => 10),
        |     'nullable'      => array('ASIGNACION.PERSONAS.RUT' => false),
        |     'defaults'      => array('ASIGNACION.PERSONAS.RUT' => null),
        |     'functions'     => array('ASIGNACION.GET_PERSONA'),
        |     'procedures'    => array('ASIGNACION.SP_PERSONA'),
        |     'sequences'     => array('ASIGNACION.SEQ_PERSONA'),
        |     'views'         => array('ASIGNACION.V_PERSONAS'),
        |     'triggers'      => array('ASIGNACION.TRG_PERSONAS'),
        |     'packages'      => array('ASIGNACION.PKG_PERSONA'),
        |     'synonyms'      => array('ASIGNACION.SIN_PERSONA'),
        |     'indexes'       => array('ASIGNACION.PERSONAS.IDX_PERSONAS_RUT'),
        |     'primary_keys'  => array('ASIGNACION.PERSONAS'),
        |     'foreign_keys'  => array('ASIGNACION.PERSONAS.FK_PERSONAS_GRUPO'),
        |     'constraints'   => array('ASIGNACION.PERSONAS.CK_PERSONAS_ESTADO'),
        | ),
        */
        'checklist' => array(),

    ),

    /*
    |--------------------------------------------------------------------------
    | Environment Validation Rules
    |--------------------------------------------------------------------------
    |
    | Define validation rules for environment variables. The required list is
    | checked by default against the project's connection variables. Variables
    | whose name contains PASSWORD, TOKEN, SECRET or KEY are protected: their
    | value is never shown, but their presence and their value rules are
    | validated (values are masked as "***" in messages).
    |
    */
    'env' => array(

        'required' => array(
            'APP_ENV',
            'DB_USERNAME',
            'DB_CHARSET',
            'DB_EXPORT_CONNECTION',
            'DB_EXPORT_USERNAME',
            'DB_EXPORT_CHARSET',
            'API_REST_HOST_MUNICIPAL',
            'API_REST_BASE_URL_MUNICIPAL',
            'CONSUMIR_WS_RUNIFICADOR',
            'wsrunificador',
            'RUNIFICADOR_REST_CONSUMIR_WS',
            'RUNIFICADOR_REST_URL',
            'RUNIFICADOR_REST_URL_METODO',
            'RUNIFICADOR_REST_TOKEN_URL',
            'RUNIFICADOR_REST_CLIENTID',
            'RUNIFICADOR_REST_CLIENTSECRET',
            'RUNIFICADOR_REST_API_TOKEN',
            'RUNIFICADOR_REST_CACHE_TIEMPO',
            'RUNIFICADOR_REST_CACHE_KEY_PRE',
            'COW_REST',
            'URL_COW',
            'API_DATOS_GLOBALES_CONSUMIR',
            'API_DATOS_GLOBALES_URL',
            'API_DATOS_GLOBALES_TOKEN',
            'APIPROCESSBID_RSH_URL',
            'APIPROCESSBID_RSH_AUTH_TYPE',
            'APIPROCESSBID_RSH_HEADER_TOKEN',
            'APIPROCESSBID_RSH_OAUTH_TOKEN_URL',
            'APIPROCESSBID_RSH_OAUTH_CLIENT_ID',
            'APIPROCESSBID_RSH_OAUTH_CLIENT_SECRET',
            'VALIDATOR_API_TOKEN'
        ),
        'optional' => array(),

        /*
        | Path to the .env file used as source of truth. When set and the file
        | exists, the validator compares the loaded values against it and reports
        | variables as 'stale' or 'not_loaded' when they differ or are missing
        | from the process (e.g. stale config cache). Leave null to use
        | base_path('.env') automatically in Laravel.
        */
        'env_file' => null,

        /*
        | When true (and env_file is set and readable), every variable defined
        | in the .env file is validated automatically against the loaded values,
        | even if it is not listed in required/optional/rules. By default it is
        | false: only the variables explicitly requested (config or request) are
        | validated. Sensitive variables (PASSWORD/TOKEN/SECRET/KEY) are always
        | skipped without exposing their values.
        */
        'auto_discover' => false,

        'show_values' => array(
            'API_REST_HOST_MUNICIPAL',
            'API_REST_BASE_URL_MUNICIPAL',
            'CONSUMIR_WS_RUNIFICADOR',
            'wsrunificador',
            'RUNIFICADOR_REST_CONSUMIR_WS',
            'RUNIFICADOR_REST_URL',
            'RUNIFICADOR_REST_URL_METODO',
            'RUNIFICADOR_REST_TOKEN_URL',
            'RUNIFICADOR_REST_CACHE_TIEMPO',
            'RUNIFICADOR_REST_CACHE_KEY_PRE',
            'COW_REST',
            'URL_COW',
            'API_DATOS_GLOBALES_CONSUMIR',
            'API_DATOS_GLOBALES_URL',
            'APIPROCESSBID_RSH_URL',
            'APIPROCESSBID_RSH_AUTH_TYPE',
            'APIPROCESSBID_RSH_OAUTH_TOKEN_URL',
        ),

        'rules'    => array(
            /*
            | Example: SERVICIOS_MIGRADOS='duplicidad-domicilio,fdfa,Data'
            | Validates that every required CSV item is present, in any order.
            | Set 'ignore_case' => true to compare case-insensitively.
            |
            'SERVICIOS_MIGRADOS' => array(
                'csv' => array(
                    'contains' => array('duplicidad-domicilio', 'fdfa', 'Data'),
                    'ignore_case' => true,
                ),
            ),
            */

        )

    ),

    /*
    |--------------------------------------------------------------------------
    | Git Validation Settings
    |--------------------------------------------------------------------------
    |
    | Configure git validation requirements for the project.
    |
    */
    'git' => array(

        'require_clean'   => true,
        'require_branch'  => '',
        'require_commit'  => '',
        'require_tag'     => '',

    ),

);
