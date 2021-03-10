@extends('layouts.website')

@section('title','Condiciones de servicio al usuario')

@section('styles')
    <style>
        body {
            background-color: white;
        }
        .header .navbar-nav li a {
            color: #444;
        }
        .navbar.navbar {
            border-bottom: solid .5px lightgrey;
        }
        .text-red {
            color: red;
        }
        .ident-100 {
            text-indent: 100px;
        }
        .ident-50 {
            text-indent: 50px;
        }
        .font-12 {
            font-size: 12px;
        }
        .container.legal {
            background-color: white;
        }
        .nav_bottom .my-2 {
            color: #444 !important;
        }
    </style>
@endsection

@section('navbar')
    <nav class="header navbar navbar-expand-md navbar-light container">
        <a class="navbar-brand" href="/">
            <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100px" src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_blue.png">
            <img class="my-0 mr-md-auto font-weight-normal stuck" width="100px" src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_blue.png">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03"
                aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav form-inline mr-auto">
            </ul>
        </div>
    </nav>
@endSection

@section('body')
    <div class="container legal">
        <div class="row">
            <div class="col-12 text-center mt-3">
                <h2>
                    ACUERDO DE SERVICIO
                </h2>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 text-justify">
                <p class="ident-50">
                    Los siguientes términos de servicio ("<b>Términos</b>" o "<b>Acuerdo</b>") constituyen
                    un acuerdo
                    vinculante entre el Proveedor de contenido, ya sea como persona física, o como
                    representante legal de una entidad, compañía o empresa ("<b>Proveedor de contenido</b>"
                    o "<b>tú</b>")
                    y Geekstadium, S. A. P. I. de C. V. ("<b>Apithy</b>"), los cuales rigen el uso de los
                    servicios. Se puede acceder a ellos: (a) como un servicio gratuito para el usuario que
                    tiene las especificaciones descritas en el Plan respectivo (la "<b>Versión gratuita</b>"),
                    y;
                    (b) como un servicio pagado que tiene las especificaciones descritas en el Plan
                    Tarifario, para el cual el <b>Proveedor de contenido</b> paga una tarifa de suscripción
                    mensual
                    o anual (la "<b>Versión de pago</b>"). El uso de los Servicios por parte del Proveedor
                    de
                    contenido está sujeto a: (a) los términos y condiciones establecidos a continuación, y;
                    (b) la política de privacidad de Apithy.
                </p>
                <p class="ident-50">
                    Los Servicios están disponibles solo para personas mayores de 18 años. Si el Proveedor
                    de contenido es una persona física, el Proveedor de contenido declara y garantiza que
                    tiene al menos 18 años de edad. 
                </p>
                <p class="ident-50">
                    El Proveedor de contenido reconoce que la Versión gratuita se proporciona sin cargo y,
                    por lo tanto, los términos que rigen el uso de dicha versión son diferentes de los
                    términos que rigen el uso de la Versión de pago. 
                </p>
                <p class="ident-50">
                    Al utilizar los Servicios en cualquiera de las modalidades, aceptas los términos aquí
                    especificados, ya sea como persona física, o bien si estás accediendo a los servicios en
                    nombre de tu empleador o de otra entidad que representas, por lo que garantizas que
                    tienes las facultades legales debidas para aceptar estos términos en representación. Si
                    no cuentas con tal facultad, o si no estás de acuerdo con estos términos y condiciones,
                    no puedes utilizar los servicios.
                </p>
                <ol>
                    <li>
                        <p class="font-18">DEFINICIONES:</p>
                        <ol type="a">
                            <li>
                                "Información confidencial" significa todos los secretos comerciales,
                                conocimientos
                                técnicos, invenciones, desarrollos, software y otra información financiera,
                                comercial o técnica divulgada por o para una parte en relación con este
                                Acuerdo,
                                pero sin incluir ninguna información que la parte receptora pueda demostrar
                                que: (
                                a) ya la conoce legítimamente y sin restricciones, (b) le ha sido otorgada
                                legítimamente por un tercero sin restricciones y sin incumplimiento de
                                ninguna
                                obligación para con la parte reveladora, (c) generalmente está disponible al
                                público
                                sin incumplimiento de este Acuerdo o (d ) ha sido desarrollada de forma
                                independiente por él sin depender de la Información Confidencial de la parte
                                reveladora. Toda la información de precios es información confidencial de
                                Apithy.
                            </li>
                            <li>
                                “Contenido digital” significa todo texto, software, guiones, gráficos,
                                fotos,
                                sonidos, música, videos, combinaciones audiovisuales, funciones interactivas
                                y otros
                                materiales que se pueden ver, acceder o contribuir a los Servicios.
                            </li>
                            <li>
                                "Contenido del Proveedor de contenido" significa Contenido contribuido a los
                                Servicios por el Proveedor de contenido.
                            </li>
                            <li>
                                "Datos del proveedor de contenido" hace referencia a toda la información de
                                registro
                                del Proveedor de contenido y otros datos de transacción recopilados,
                                procesados ​​y
                                retenidos por Apithy en relación con la prestación de los Servicios.
                            </li>
                            <li>
                                "Plan" se refiere a los planes gratuitos o de pago de Apithy, según
                                corresponda y
                                como se describen en los planes tarifarios.
                            </li>
                            <li>
                                "Servicios" significa los servicios alojados por Apithy y proporcionados al
                                Proveedor de contenido en virtud de este Acuerdo.
                            </li>
                            <li>
                                “Sistemas” significa módems, servidores, software, equipos de redes y
                                comunicaciones
                                y servicios auxiliares que son propiedad, controlados o adquiridos por el
                                Proveedor
                                de contenido.
                            </li>
                            <li>
                                "Actualizaciones" significa cualquier parche, revisión o actualización de
                                los
                                Servicios entregados por Apithy.
                            </li>
                        </ol>
                    </li>
                    <br>
                    <li>
                        SERVICIOS. Sujeto a todos los términos y condiciones de este Acuerdo, Apithy
                        proporciona los servicios de alojamiento de contenido digital, ya sea a través de la
                        plataforma, o bien, utilizando proveedores de servicios de terceros.
                    </li>
                    <br>
                    <li>
                        MEDIDAS DE SEGURIDAD. El Proveedor de contenido puede acceder a los Servicios según
                        lo indique Apithy a través de una combinación de uno o más nombres de usuario y
                        contraseñas.
                    </li>
                    <br>
                    <li>
                        El Proveedor de contenido asumirá toda la responsabilidad de la seguridad de cada
                        uno de sus nombres de usuario y contraseñas, y será el único responsable del uso de
                        los Servicios a través de dichos nombres de usuario o contraseñas. El Proveedor de
                        contenido acepta notificar de inmediato a Apithy sobre cualquier uso no autorizado
                        de los Servicios o cualquier otra violación de seguridad conocida por el Proveedor
                        de contenido.
                    </li>
                    <br>
                    <li>
                        <p>
                            USOS NO PERMITIDOS CON LA CONTRATACIÓN DEL SERVICIO. Como condición para el uso
                            de los Servicios, el Proveedor de contenido se obliga a no usar los Servicios
                            para cualquier propósito que esté prohibido por estos Términos.
                        </p>
                        <p>
                            A modo de ejemplo, y no como limitación, no debes cargar, enviar, distribuir,
                            facilitar ninguno de los anteriores, ni utilizar los Servicios de una manera
                            que:
                        </p>
                        <ol type="a">
                            <li>
                                infringa o viole los derechos de propiedad intelectual o cualquier otro
                                derecho de cualquier otra persona o entidad (incluida Apithy);
                            </li>
                            <li>
                                viole cualquier ley o reglamento;
                            </li>
                            <li>
                                sea dañino, fraudulento, engañoso, amenazante, acosador, difamatorio,
                                obsceno, pornográfico, contiene o muestra desnudos, o de cualquier otra
                                forma objetable, según lo determine Apithy a su entera discreción;
                            </li>
                            <li>
                                ponga en peligro la seguridad de su cuenta de Apithy o la de cualquier otra
                                persona (como permitir que otra persona inicie sesión en los Servicios como
                                usted)
                            </li>
                            <li>
                                intente, de cualquier manera, obtener la contraseña, cuenta u otra
                                información de seguridad de cualquier otro usuario;
                            </li>
                            <li>
                                viole la seguridad de cualquier red de computadoras, o descifra contraseñas
                                o códigos de encriptación de seguridad;
                            </li>
                            <li>
                                copie o almacene cualquier porción significativa del Contenido;
                            </li>
                            <li>
                                descompile, haga ingeniería inversa, o intente obtener el código fuente o
                                ideas subyacentes o información de, o relacionada con los Servicios.
                            </li>
                        </ol>
                        <p>
                            Además, el Proveedor de contenido no deberá (directa o indirectamente), o
                            permitir que terceros: (a) utilicen cualquier Información Confidencial de Apithy
                            para crear cualquier software, documentación o servicios que sean similares o
                            cualquier documentación provista en conexión con ellos; (b) modificar, traducir
                            o crear obras derivadas de cualquier parte de los Servicios, (c) copiar,
                            licenciar, sublicenciar, vender, revender, gravar, alquilar, arrendar,
                            compartir, distribuir, transferir o de otra manera usar o explotar o poner a
                            disposición los Servicios en beneficio de terceros sin el consentimiento previo
                            por escrito de Apithy y/o de su titular. Deberás cumplir con todas las leyes y
                            regulaciones locales, estatales, nacionales e internacionales aplicables.
                            Finalmente, debes ser un humano. No se permite el acceso a los Servicios por
                            "bots" u otros métodos automatizados.
                        </p>
                    </li>
                    <br>
                    <li>
                        CAMBIOS EN LOS SERVICIOS. Apithy se reserva el derecho de modificar o interrumpir
                        cualquier Servicio o Plan (en su totalidad o en parte) en cualquier momento.
                    </li>
                    <br>
                    <li>
                        CAMBIOS EN LOS TÉRMINOS. Nos reservamos el derecho de cambiar los Términos en
                        cualquier momento, pero si lo hacemos, te lo comunicaremos mediante un aviso en el
                        sitio web de la plataforma, o bien, enviándote un correo electrónico. Si no está de
                        acuerdo con los nuevos Términos, puede rechazarlos; desafortunadamente, eso
                        significa que ya no podrás utilizar los Servicios. Sin embargo, si utilizas los
                        Servicios de alguna manera después de que un cambio en los Términos sea efectivo,
                        significa que acepta todos los cambios. 
                    </li>
                    <br>
                    <li>
                        LIMITACIONES. Apithy no será responsable por cualquier falla en los Servicios que
                        resulte de o sea atribuible a: (a) los Sistemas del Proveedor de contenido, (b)
                        fallas en los servicios de telecomunicaciones u otros servicios o equipos fuera de
                        las instalaciones de Apithy, (c) los productos del Proveedor de contenido o de
                        terceros, servicios, negligencia, actos u omisiones, (d) cualquier fuerza mayor o
                        causa más allá del control razonable de Apithy, (e) mantenimiento programado, o;(f)
                        acceso no autorizado, violación de cortafuegos u otro pirateo por parte de terceros.
                    </li>
                    <br>
                    <li>
                        SISTEMAS. El Proveedor de contenido deberá obtener y operar todos los Sistemas
                        necesarios para conectarse, acceder o utilizar los Servicios, y proporcionar todos
                        los servicios de respaldo, recuperación y mantenimiento correspondientes. El
                        proveedor de contenido se asegurará de que todos los sistemas sean compatibles con
                        los servicios. El proveedor de contenido deberá mantener la integridad y seguridad
                        de sus sistemas (físicos, electrónicos y de otro tipo).
                    </li>
                    <br>
                    <li>
                        DERECHOS DE PROPIEDAD INTELECTUAL. Excepto por el Contenido del Proveedor de
                        contenido, Apithy (y sus otorgantes de licencias) posee todos los derechos y títulos
                        inherentes a los Servicios y todas las modificaciones, mejoras y actualizaciones a
                        los mismos. Apithy se reserva todos los derechos no otorgados expresamente en este
                        documento. 
                    </li>
                    <br>
                    <li>
                        <p>TITULARIDAD DEL CONTENIDO Y LICENCIA.</p>
                        <p>
                            El Proveedor de contenido es el único titular de todos los derechos de propiedad
                            intelectual inherentes a su contenido. Sin embargo, por el simple hecho de
                            contratar los servicios, otorga a Apithy y sus afiliados una licencia limitada,
                            mundial y no exclusiva para copiar, transmitir, distribuir, realizar
                            públicamente y mostrar (a través de todos los medios ahora conocidos o creados
                            posteriormente), y/o realizar trabajos derivados de su contenido, con el
                            propósito de: (i) mostrar el contenido dentro del Servicio de Apithy; (ii)
                            mostrar el contenido en sitios web y aplicaciones de terceros a través de una
                            incrustación del mismo sujeto a sus opciones de privacidad; (iii) permitir que
                            otros usuarios reproduzcan, pero sujeto a sus opciones de privacidad, el
                            contenido; (iv) promover el Servicio de Apithy, siempre que haya puesto el
                            contenido a disposición del público, y; (v) archivar o conservar el contenido
                            para disputas, procedimientos legales o investigaciones.
                        </p>
                        <p>
                            Por lo anterior, desde el momento de la contratación, el Proveedor de contenido
                            garantiza que es el único titular de todos los derechos relacionados con el
                            contenido respecto del cual solicitas la contratación de los servicios.
                        </p>
                        <p>
                            En caso de cualquier reclamo por parte de terceros relacionado con el contenido,
                            ya sea de forma total o parcial, Apithy podra reclamar al Proveedor de contenido
                            los daños y perjuicios que le ocasione.
                        </p>
                        <p>
                            La licencia que el Proveedor de contenido otorga a Apithy respecto de los
                            contenidos, estará vigente por un periodo de tiempo de 5 años, contados estos a
                            partir de que el contenido es distribuido y divulgado en la plataforma, ya sea
                            en su versión original, o bien mediante el trabajo derivado que se realizó para
                            la inclusión del mismo en la plataforma.
                        </p>
                    </li>
                    <br>
                    <li>
                        INFORMACIÓN CONFIDENCIAL. Las Partes convienen que, no utilizarán ni divulgarán
                        ninguna información que esté catalogada como confidencial y que pertenezca a la otra
                        parte, a menos que se conceda el respectivo consentimiento para ell; en este caso,
                        se deberá usar el cuidado razonable para proteger la Información Confidencial de la
                        parte que corresponda.
                    </li>
                    <br>
                    <li>
                        <p>
                            CONTRAPRESTACIÓN. El Proveedor de contenido acepta pagar a Apithy las tarifas
                            y/o importes especificados en el Plan seleccionado.
                        </p>
                        <p>
                            Para ello, el Proveedor de contenido debe proporcionar a Apithy la información
                            de facturación correcta y completa, incluido el nombre legal, la dirección, el
                            número de teléfono y una tarjeta de crédito válida. La tarjeta del proveedor de
                            contenido nunca será cargada sin su autorización. Al enviar dicha información de
                            la tarjeta de crédito, el Proveedor de contenido otorga a Apithy permiso para
                            realizar todos los cargos incurridos a través de su cuenta a la tarjeta de
                            crédito designada. 
                        </p>
                        <p>
                            Los Servicios se facturan por adelantado en forma mensual o anual, según el plan
                            de pago elegido por el Proveedor de contenido. Apithy no proporcionará
                            reembolsos o créditos en el caso de cancelaciones, rebajas o cuando haya partes
                            no utilizadas de los Servicios en una cuenta abierta. Todos los futuros cargos
                            recurrentes por los Servicios seguirán el ciclo de facturación mensual o anual
                            (según lo elija el Proveedor de contenido).
                        </p>
                        <p>
                            En caso de falta de pago, los servicios serán rescindidos de manera inmediata.
                        </p>
                    </li>
                    <br>
                    <li>
                        LÍMITE DE RESPONSABILIDAD. Con excepción de lo especificado aquí, los servicios se
                        proporcionan "<i>tal cual</i>" sin garantía de ningún tipo. Apithy no garantiza que los
                        servicios cumplen los requisitos del proveedor de contenido o que su operación sea
                        ininterrumpida o libre de errores.
                    </li>
                    <br>
                    <li>
                        <p>
                            VIGENCIA. Este Acuerdo comenzará a partir de la fecha en que sea aceptado. Con
                            respecto a un usuario de la Versión de pago, este Acuerdo continuará en vigor
                            durante el plazo inicial especificado en el Plan (o si no se especifica dicho
                            término, durante el ciclo de facturación), y se renovará de manera automática
                            con base en el plan seleccionado. Sin embargo, cualquiera de las partes puede
                            optar por no renovar este Acuerdo mediante notificación por escrito de dicha
                            elección, con por lo menos un mes antes en que ocurra dihca renovación. 
                        </p>
                        <p>
                            En el caso del Proveedor de contenido, éste será el único responsable de
                            notificar correctamente a Apithy sobre su elección de no renovar
                            automáticamente, siguiendo las instrucciones de cancelación disponibles en la
                            cuenta de Apithy del Proveedor de contenido. Con respecto a un usuario de la
                            Versión gratuita, este Acuerdo continuará vigente hasta que cualquiera de las
                            partes finalice este Acuerdo con al menos 5 días hábiles de notificación por
                            escrito a la otra parte.
                        </p>
                        <p>
                            Igualmente, el Proveedor de contenido acepta desde ahora que Apithy tendrá la
                            facultad para retirar de la plataforma el contenido que le fue proveído, en caso
                            de que él mismo no tenga la recurrencia necesaria de explotación (según el
                            criterio de Apithy), o bien cuando el tema que aborda el propio contenido no es
                            de actualidad.
                        </p>
                    </li>
                    <br>
                    <li>
                        <p>
                            LINEAMIENTOS EN MATERIA DE DERECHOS DE AUTOR (Copyright). Es política de Apithy,
                            en materia de derechos de autor: (a) bloquear el acceso o eliminar Contenido que
                            cree de buena fe, es material protegido por derechos de autor y que ha sido
                            copiado y distribuido ilegalmente por cualquiera de sus afiliados, usuarios y/o
                            proveedores de contenido, y; (b) eliminar e interrumpir el servicio a
                            reincidentes.
                        </p>
                        <p>
                            Si cualquier tercero considera que el Contenido que reside o es accesible a
                            través de la plataforma infringe un derecho de autor, podrá enviar un <i>aviso de
                                infracción de copyright</i> que contenga la siguiente información:
                        </p>
                        <ol type="a">
                            <li>
                                Una firma física o electrónica de una persona autorizada para actuar en
                                nombre del propietario de los derechos de autor que presuntamente se ha
                                infringido;
                            </li>
                            <li>
                                Identificación de obras o materiales objeto de infracción;
                            </li>
                            <li>
                                Identificación del Contenido que se alega que infringe, incluida la
                                información sobre la ubicación del Contenido que el propietario de los
                                derechos de autor desea eliminar, con detalles suficientes para que Apithy
                                sea capaz de encontrar y verificar su existencia;
                            </li>
                            <li>
                                Información de contacto sobre el notificador, incluida la dirección, el
                                número de teléfono y, si está disponible, la dirección de correo
                                electrónico;
                            </li>
                            <li>
                                Una declaración de que el notificador cree de buena fe que el Contenido no
                                está autorizado por el propietario de los derechos de autor, su agente o la
                                ley, y;
                            </li>
                            <li>
                                Una declaración bajo protesta de decir verdad, de que la información
                                proporcionada es correcta y la parte notificante está autorizada para
                                presentar la queja en nombre del propietario de los derechos de autor.
                            </li>
                        </ol>
                        <p>
                            Una vez que Apithy reciba dicha notificación de infracción, procederá conforme a
                            lo siguiente:
                        </p>
                        <ol type="a">
                            <li>
                                Eliminará y deshabilitará el accesos al contenido del infractor;
                            </li>
                            <li>
                                Notificará a la persona que proveyo dicho Contenido, y/o al usuario que
                                recurrentemente lo usa.
                            </li>
                        </ol>
                    </li>
                    <br>
                    <li>
                        ACUERDO TOTAL. Los términos y condciones aquí vertidos, junto con la política de
                        privacidad de Apithy y los Planes tarifarios aplicables, constituyen el acuerdo
                        total y reemplaza todas las negociaciones, entendimientos o acuerdos anteriores
                        (orales o escritos) entre las partes sobre los servicios. En caso de conflicto o
                        inconsistencia entre el Acuerdo y el Plan, los términos y condiciones de los Planes
                        tarifarios prevalecerán y serán dominantes.
                    </li>
                    <br>
                    <li>
                        JURISDICCIÓN. Este Acuerdo se regirá e interpretará de acuerdo con las leyes de los
                        Estados Unidos Mexicanos; específicamente, con las leyes y tribunales de la Ciudad
                        de México.
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection