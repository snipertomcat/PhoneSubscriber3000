<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una url válida.',
    'after'                => 'El campo :attribute tiene que ser una fecha después de :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute solo puede contener caracteres.',
    'alpha_dash'           => 'El campo :attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => 'El campo :attribute solo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser un arreglo.',
    'before'               => 'El campo :attribute tiene que ser una fecha antes de :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo :attribute tiene que estar entre :min y :max.',
        'file'    => 'El campo :attribute tiene que estar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute tiene que estar entre :min y :max caracteres.',
        'array'   => 'El campo :attribute tiene que estar entre :min y :max artículos.',
    ],
    'boolean'              => 'El campo :attribute tiene que ser verdadero o falso.',
    'confirmed'            => 'El campo :attribute y la confirmación no coinciden.',
    'date'                 => 'El campo :attribute no es una fecha válida.',
    'date_format'          => 'El campo :attribute no coincide con el formato :format.',
    'different'            => 'El campo :attribute y :other tienen que ser diferentes.',
    'digits'               => 'El campo :attribute tiene que ser de :digits digitos.',
    'digits_between'       => 'El campo :attribute tiene que estar entre :min y :max digitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => 'El campo :attribute tiene que ser un correo electrónico válido.',
    'exists'               => 'El campo :attribute seleccionado es no válido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es requerido.',
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El campo :attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El campo :attribute tiene que ser un entero.',
    'ip'                   => 'El campo :attribute tiene que ser una dirección de IP válida.',
    'ipv4'                 => 'El campo :attribute tiene que ser una dirección de IPv4 válida.',
    'ipv6'                 => 'El campo :attribute tiene que ser una dirección de IPv6 válida.',
    'json'                 => 'El campo :attribute tiene que ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'string'  => 'El campo :attribute no debe ser mayor que :max caracteres.',
        'array'   => 'El campo :attribute no debe tener más de :max artículos.',
    ],
    'mimes'                => 'El campo :attribute tiene que ser un archivo del tipo: :values.',
    'mimetypes'            => 'El campo :attribute tiene que ser un archivo del tipo: :values.',
    'min'                  => [
        'numeric' => 'El campo :attribute debe ser de al menos :min.',
        'file'    => 'El campo :attribute debe ser de al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe ser de al menos :min caracteres.',
        'array'   => 'El campo :attribute tiene que tener al menos :min artículos.',
    ],
    'not_in'               => 'El campo :attribute seleccionado es inválido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El formato del campo :attribute es inválido.',
    'required'             => 'El campo :attribute es requerido.',
    'required_if'          => 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es requerido a menos que :other este entre :values.',
    'required_with'        => 'El campo :attribute es requerido cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es requerido cuando :values está presente.',
    'required_without'     => 'El campo :attribute es requerido cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es requerido cuando ninguno de los :values están presentes.',
    'same'                 => 'El campo :attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El campo :attribute debe ser :size kilobytes.',
        'string'  => 'El campo :attribute debe ser :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size artículos.',
    ],
    'string'               => 'El campo :attribute debe ser una cadena.',
    'timezone'             => 'El campo :attribute debe ser una zona válida.',
    'unique'               => 'El campo :attribute ya ha sido utilizado.',
    'uploaded'             => 'El campo :attribute fallo al ser subido.',
    'url'                  => 'El campo :attribute formato es inválido.',

    'rfc' => 'El campo :attribute tiene un formato no válido',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'title' => 'titulo',
        'phone' => 'teléfono'
    ],

    /*
    |--------------------------------------------------------------------------
    | VeeValidate Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify vee-validate custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines.
    |
    */

    'vee_validate' => [
        'attributes' => [
            'name' => 'nombre',
            'surname' => 'apellido',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'billing_email' => 'correo electrónico de facturación',
            'address' => 'dirección',
            'legal_address' => 'dirección fiscal',
            'city' => 'ciudad',
            'state' => 'estado',
            'country' => 'país',
            'rfc' => 'RFC',
            'legal_name' => 'razón social',
            'r_social' => 'razón social',
            'short_name' => 'nombre corto',
            'description' => 'descripción',
            'init_balance' => 'monto del presupuesto',
            'type' => 'tipo',
            'domain' => 'dominio',
            'valid_domain' => 'dominios_validos',
            'sector' => 'sector',
            'size' => 'tamaño',
            'website' => 'sitio web',
            'contact_email' => 'correo elctrónico de contacto',
            'contact_phone' => 'teléfono de contacto',
            'postal_code' => 'código postal',
            'licences_number' => 'número de licencias',
            'number' => 'número',
            'cvc' => 'código de seguridad',
            'exp_month' => 'mes de expiración',
            'exp_year' => 'año de expiración',
            'summary' => 'resumen',
            'company_objetives' => 'objetivos de compañía',
            'area_objetives' => 'objetivos de area',
            'visibility' => 'visibilidad',
        ],
        'custom' => [
            'billing_email' => [
                'required' => 'El correo electrónico para facturación es obligatorio.',
                'email' => 'No es una dirección de correo electrónico válida.',
            ],
            'email' => [
                'required' => 'La dirección de correo electrónico es obligatoria.',
                'email' => 'No es una dirección de correo electrónico válida.',
                'regex' => 'No es una dirección de correo electrónico válida.',
                'verify_email_exist' => 'La dirección de correo electrónico ya se ha utilizado.',
            ],
            'password' => [
                'required' => 'La contraseña es obligatoria.',
                'min' => 'La longitud mínima de la contraseña es de 6 caracteres.',
            ],
            'name' => [
                'required' => 'El nombre es obligatorio.',
                'alpha_spaces' => 'El nombre sólo puede contener letras y espacios.',
                'min' => 'La longitud mínima del nombre es de 2 caracteres.',
                'max' => 'La longitud máxima del nombre es de 50 caracteres.',
            ],
            'surname' => [
                'required' => 'El apellido es obligatorio.',
                'alpha_spaces' => 'El apellido sólo puede contener letras y espacios.',
                'min' => 'La longitud mínima del apellido es de 2 caracteres.',
                'max' => 'La longitud máxima del apellido es de 50 caracteres.',
            ],
            'short_name' => [
                'required' => 'El nombre corto es obligatorio.',
                'min' => 'La longitud mínima del nombre corto es de 2 caracteres.',
                'max' => 'La longitud máxima del nombre corto es de 10 caracteres.',
            ],
            'legal_address' => [
                'required' => 'La dirección fiscal es obligatoria.',
            ],
            'legal_name' => [
                'required' => 'La razón social es obligatoria.',
            ],
            'r_social' => [
                'required' => 'La razón social es obligatoria.',
            ],
            'rfc' => [
                'required' => 'El RFC es obligatorio.',
                'alpha_num' => 'El RFC sólo puede contener letras y números.',
                'min' => 'El RFC debe tener 12 o 13 carácteres de longitud.',
                'max' => 'El RFC debe tener 12 o 13 carácteres de longitud.',
                'regex' => 'El RFC no cuenta con un formato válido.'
            ],
            'postal_code' => [
                'numeric' => 'El código postal sólo puede contener números.',
                'digits' => 'El código postal debe tener una longitud de 5 dígitos.'
            ],
            'role_id' => [
                'required' => 'Debe seleccionar un rol.',
                'min_value' => 'El rol que intenta seleccionar es inválido.',
            ],
            'phone' => [
                'required' => 'El número de teléfono es obligatorio.',
                'digits' => 'El número de teléfono sólo debe contener 10 dígitos.',
                'numeric' => 'El número de teléfono sólo debe contener números.',
            ],
            'website' => [
                'url' => 'El formato de la dirección web es inválido.'
            ],
            'init_balance' => [
                'required' => 'El monto del presupuesto es obligatorio.',
                'max_value' => 'El monto no puede exceder 100,000,000 por operación.',
                'min_value' => 'El monto no puede ser menór a 5.00 por operación.',
            ],
            'licences_number' => [
                'required' => 'El número de licencias es obligatorio.',
                'numeric' => 'El número de licencias sólo debe contener números enteros.',
                'min_value' => 'El número de licencias debe ser al menos 1.',
            ],
            'number' => [
                'required' => 'El número de tarjeta es obligatorio.',
                'max' => 'La longitud máxima del número es de 16 caracteres.',
            ],
            'cvc' => [
                'required' => 'El código de seguridad es obligatorio.',
                'min' => 'La longitud mínima del código de seguridad es de 3 dígitos.',
            ],
            'exp_month' => [
                'required' => 'El mes de expiración es obligatorio.',
            ],
            'exp_year' => [
                'required' => 'El año de expiración es obligatorio.',
            ],
            'summary' => [
                'required' => 'El resumen es obligatorio.'
            ],
            'company_objetives' => [
                'required' => 'Los objetivos de compañía son obligatorios.'
            ],
            'area_objetives' => [
                'required' => 'Los objetivos de area son obligatorios.'
            ],
            'visibility' => [
                'required' => 'La visibilidad es obligatoria.'
            ],
            'access' => [
                'no_whitespace' => 'El correo electrónico no debe contener espacios',
                // 'company_phone' => 'El número de teléfono sólo debe contener 10 dígitos.',
                'custom_email' => 'No es una dirección de correo electrónico válida.',
                'required' => 'La dirección de correo electrónico o el número de teléfono son obligatorios.'
            ]
        ]
    ],

];