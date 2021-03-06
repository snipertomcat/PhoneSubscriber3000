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
                    Los siguientes t??rminos de servicio ("<b>T??rminos</b>" o "<b>Acuerdo</b>") constituyen
                    un acuerdo
                    vinculante entre el Proveedor de contenido, ya sea como persona f??sica, o como
                    representante legal de una entidad, compa????a o empresa ("<b>Proveedor de contenido</b>"
                    o "<b>t??</b>")
                    y Geekstadium, S. A. P. I. de C. V. ("<b>Apithy</b>"), los cuales rigen el uso de los
                    servicios. Se puede acceder a ellos: (a) como un servicio gratuito para el usuario que
                    tiene las especificaciones descritas en el Plan respectivo (la "<b>Versi??n gratuita</b>"),
                    y;
                    (b) como un servicio pagado que tiene las especificaciones descritas en el Plan
                    Tarifario, para el cual el <b>Proveedor de contenido</b> paga una tarifa de suscripci??n
                    mensual
                    o anual (la "<b>Versi??n de pago</b>").??El uso de los Servicios por parte del Proveedor
                    de
                    contenido est?? sujeto a: (a) los t??rminos y condiciones establecidos a continuaci??n, y;
                    (b) la pol??tica de privacidad de Apithy.
                </p>
                <p class="ident-50">
                    Los Servicios est??n disponibles solo para personas mayores de 18 a??os.??Si el Proveedor
                    de contenido es una persona f??sica, el Proveedor de contenido declara y garantiza que
                    tiene al menos 18 a??os de edad.??
                </p>
                <p class="ident-50">
                    El Proveedor de contenido reconoce que la Versi??n gratuita se proporciona sin cargo y,
                    por lo tanto, los t??rminos que rigen el uso de dicha versi??n son diferentes de los
                    t??rminos que rigen el uso de la Versi??n de pago.??
                </p>
                <p class="ident-50">
                    Al utilizar los Servicios en cualquiera de las modalidades, aceptas los t??rminos aqu??
                    especificados, ya sea como persona f??sica, o bien??si est??s accediendo a los servicios en
                    nombre de tu empleador o de otra entidad que representas, por lo que garantizas que
                    tienes las facultades legales debidas para aceptar estos t??rminos en representaci??n.??Si
                    no cuentas con tal facultad, o si no est??s de acuerdo con estos t??rminos y condiciones,
                    no puedes utilizar los servicios.
                </p>
                <ol>
                    <li>
                        <p class="font-18">DEFINICIONES:</p>
                        <ol type="a">
                            <li>
                                "Informaci??n confidencial"??significa todos los secretos comerciales,
                                conocimientos
                                t??cnicos, invenciones, desarrollos, software y otra informaci??n financiera,
                                comercial o t??cnica divulgada por o para una parte en relaci??n con este
                                Acuerdo,
                                pero sin incluir ninguna informaci??n que la parte receptora pueda demostrar
                                que: (
                                a) ya la conoce leg??timamente y sin restricciones, (b) le ha sido otorgada
                                leg??timamente por un tercero sin restricciones y sin incumplimiento de
                                ninguna
                                obligaci??n para con la parte reveladora, (c) generalmente est?? disponible al
                                p??blico
                                sin incumplimiento de este Acuerdo o (d ) ha sido desarrollada de forma
                                independiente por ??l sin depender de la Informaci??n Confidencial de la parte
                                reveladora.??Toda la informaci??n de precios es informaci??n confidencial de
                                Apithy.
                            </li>
                            <li>
                                ???Contenido digital?????significa todo texto, software, guiones, gr??ficos,
                                fotos,
                                sonidos, m??sica, videos, combinaciones audiovisuales, funciones interactivas
                                y otros
                                materiales que se pueden ver, acceder o contribuir a los Servicios.
                            </li>
                            <li>
                                "Contenido del Proveedor de contenido"??significa Contenido contribuido a los
                                Servicios por el Proveedor de contenido.
                            </li>
                            <li>
                                "Datos del proveedor de contenido"??hace referencia a toda la informaci??n de
                                registro
                                del Proveedor de contenido y otros datos de transacci??n recopilados,
                                procesados ??????y
                                retenidos por Apithy en relaci??n con la prestaci??n de los Servicios.
                            </li>
                            <li>
                                "Plan" se??refiere a los planes gratuitos o de pago de Apithy, seg??n
                                corresponda y
                                como se describen en los planes tarifarios.
                            </li>
                            <li>
                                "Servicios"??significa los servicios alojados por Apithy y proporcionados al
                                Proveedor de contenido en virtud de este Acuerdo.
                            </li>
                            <li>
                                ???Sistemas?????significa m??dems, servidores, software, equipos de redes y
                                comunicaciones
                                y servicios auxiliares que son propiedad, controlados o adquiridos por el
                                Proveedor
                                de contenido.
                            </li>
                            <li>
                                "Actualizaciones"??significa cualquier parche, revisi??n o actualizaci??n de
                                los
                                Servicios entregados por Apithy.
                            </li>
                        </ol>
                    </li>
                    <br>
                    <li>
                        SERVICIOS. Sujeto a todos los t??rminos y condiciones de este Acuerdo, Apithy
                        proporciona los servicios de alojamiento de contenido digital, ya sea a trav??s de la
                        plataforma, o bien, utilizando proveedores de servicios de terceros.
                    </li>
                    <br>
                    <li>
                        MEDIDAS DE SEGURIDAD. El Proveedor de contenido puede acceder a los Servicios seg??n
                        lo indique Apithy a trav??s de una combinaci??n de uno o m??s nombres de usuario y
                        contrase??as.
                    </li>
                    <br>
                    <li>
                        El Proveedor de contenido asumir?? toda la responsabilidad de la seguridad de cada
                        uno de sus nombres de usuario y contrase??as, y ser?? el ??nico responsable del uso de
                        los Servicios a trav??s de dichos nombres de usuario o contrase??as.??El Proveedor de
                        contenido acepta notificar de inmediato a Apithy sobre cualquier uso no autorizado
                        de los Servicios o cualquier otra violaci??n de seguridad conocida por el Proveedor
                        de contenido.
                    </li>
                    <br>
                    <li>
                        <p>
                            USOS NO PERMITIDOS CON LA CONTRATACI??N DEL SERVICIO. Como condici??n para el uso
                            de los Servicios, el Proveedor de contenido se obliga a no usar los Servicios
                            para cualquier prop??sito que est?? prohibido por estos T??rminos.
                        </p>
                        <p>
                            A modo de ejemplo, y no como limitaci??n, no debes cargar, enviar, distribuir,
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
                                sea da??ino, fraudulento, enga??oso, amenazante, acosador, difamatorio,
                                obsceno, pornogr??fico, contiene o muestra desnudos, o de cualquier otra
                                forma objetable, seg??n lo determine Apithy a su entera discreci??n;
                            </li>
                            <li>
                                ponga en peligro la seguridad de su cuenta de Apithy o la de cualquier otra
                                persona (como permitir que otra persona inicie sesi??n en los Servicios como
                                usted)
                            </li>
                            <li>
                                intente, de cualquier manera, obtener la contrase??a, cuenta u otra
                                informaci??n de seguridad de cualquier otro usuario;
                            </li>
                            <li>
                                viole la seguridad de cualquier red de computadoras, o descifra contrase??as
                                o c??digos de encriptaci??n de seguridad;
                            </li>
                            <li>
                                copie o almacene cualquier porci??n significativa del Contenido;
                            </li>
                            <li>
                                descompile, haga ingenier??a inversa, o intente obtener el c??digo fuente o
                                ideas subyacentes o informaci??n de, o relacionada con los Servicios.
                            </li>
                        </ol>
                        <p>
                            Adem??s, el Proveedor de contenido no deber?? (directa o indirectamente), o
                            permitir que terceros: (a) utilicen cualquier Informaci??n Confidencial de Apithy
                            para crear cualquier software, documentaci??n o servicios que sean similares o
                            cualquier documentaci??n provista en conexi??n con ellos;??(b) modificar, traducir
                            o crear obras derivadas de cualquier parte de los Servicios, (c) copiar,
                            licenciar, sublicenciar, vender, revender, gravar, alquilar, arrendar,
                            compartir, distribuir, transferir o de otra manera usar o explotar o poner a
                            disposici??n los Servicios en beneficio de terceros sin el consentimiento previo
                            por escrito de Apithy y/o de su titular.??Deber??s cumplir con todas las leyes y
                            regulaciones locales, estatales, nacionales e internacionales aplicables.
                            Finalmente, debes ser un humano.??No se permite el acceso a los Servicios por
                            "bots" u otros m??todos automatizados.
                        </p>
                    </li>
                    <br>
                    <li>
                        CAMBIOS EN LOS SERVICIOS. Apithy se reserva el derecho de modificar o interrumpir
                        cualquier Servicio o Plan (en su totalidad o en parte) en cualquier momento.
                    </li>
                    <br>
                    <li>
                        CAMBIOS EN LOS T??RMINOS. Nos reservamos el derecho de cambiar los T??rminos en
                        cualquier momento, pero si lo hacemos, te lo comunicaremos mediante un aviso en el
                        sitio web de la plataforma, o bien, envi??ndote un correo electr??nico.??Si no est?? de
                        acuerdo con los nuevos T??rminos, puede rechazarlos;??desafortunadamente, eso
                        significa que ya no podr??s utilizar los Servicios.??Sin embargo, si utilizas los
                        Servicios de alguna manera despu??s de que un cambio en los T??rminos sea efectivo,
                        significa que acepta todos los cambios.??
                    </li>
                    <br>
                    <li>
                        LIMITACIONES. Apithy no ser?? responsable por cualquier falla en los Servicios que
                        resulte de o sea atribuible a: (a) los Sistemas del Proveedor de contenido, (b)
                        fallas en los servicios de telecomunicaciones u otros servicios o equipos fuera de
                        las instalaciones de Apithy, (c) los productos del Proveedor de contenido o de
                        terceros, servicios, negligencia, actos u omisiones, (d) cualquier fuerza mayor o
                        causa m??s all?? del control razonable de Apithy, (e) mantenimiento programado, o;(f)
                        acceso no autorizado, violaci??n de cortafuegos u otro pirateo por parte de terceros.
                    </li>
                    <br>
                    <li>
                        SISTEMAS. El Proveedor de contenido deber?? obtener y operar todos los Sistemas
                        necesarios para conectarse, acceder o utilizar los Servicios, y proporcionar todos
                        los servicios de respaldo, recuperaci??n y mantenimiento correspondientes.??El
                        proveedor de contenido se asegurar?? de que todos los sistemas sean compatibles con
                        los servicios.??El proveedor de contenido deber?? mantener la integridad y seguridad
                        de sus sistemas (f??sicos, electr??nicos y de otro tipo).
                    </li>
                    <br>
                    <li>
                        DERECHOS DE PROPIEDAD INTELECTUAL. Excepto por el Contenido del Proveedor de
                        contenido, Apithy (y sus otorgantes de licencias) posee todos los derechos y t??tulos
                        inherentes a los Servicios y todas las modificaciones, mejoras y actualizaciones a
                        los mismos.??Apithy se reserva todos los derechos no otorgados expresamente en este
                        documento.??
                    </li>
                    <br>
                    <li>
                        <p>TITULARIDAD DEL CONTENIDO Y LICENCIA.</p>
                        <p>
                            El Proveedor de contenido es el ??nico titular de todos los derechos de propiedad
                            intelectual inherentes a su contenido. Sin embargo, por el simple hecho de
                            contratar los servicios, otorga a Apithy y sus afiliados una licencia limitada,
                            mundial y no exclusiva para copiar, transmitir, distribuir, realizar
                            p??blicamente y mostrar (a trav??s de todos los medios ahora conocidos o creados
                            posteriormente), y/o realizar trabajos derivados de su contenido, con el
                            prop??sito de: (i) mostrar el contenido dentro del Servicio de Apithy;??(ii)
                            mostrar el contenido en sitios web y aplicaciones de terceros a trav??s de una
                            incrustaci??n del mismo sujeto a sus opciones de privacidad;??(iii) permitir que
                            otros usuarios reproduzcan, pero sujeto a sus opciones de privacidad, el
                            contenido;??(iv) promover el Servicio de Apithy, siempre que haya puesto el
                            contenido a disposici??n del p??blico, y; (v) archivar o conservar el contenido
                            para disputas, procedimientos legales o investigaciones.
                        </p>
                        <p>
                            Por lo anterior, desde el momento de la contrataci??n, el Proveedor de contenido
                            garantiza que es el ??nico titular de todos los derechos relacionados con el
                            contenido respecto del cual solicitas la contrataci??n de los servicios.
                        </p>
                        <p>
                            En caso de cualquier reclamo por parte de terceros relacionado con el contenido,
                            ya sea de forma total o parcial, Apithy podra reclamar al Proveedor de contenido
                            los da??os y perjuicios que le ocasione.
                        </p>
                        <p>
                            La licencia que el Proveedor de contenido otorga a Apithy respecto de los
                            contenidos, estar?? vigente por un periodo de tiempo de 5 a??os, contados estos a
                            partir de que el contenido es distribuido y divulgado en la plataforma, ya sea
                            en su versi??n original, o bien mediante el trabajo derivado que se realiz?? para
                            la inclusi??n del mismo en la plataforma.
                        </p>
                    </li>
                    <br>
                    <li>
                        INFORMACI??N CONFIDENCIAL. Las Partes convienen que, no utilizar??n ni divulgar??n
                        ninguna informaci??n que est?? catalogada como confidencial y que pertenezca a la otra
                        parte, a menos que se conceda el respectivo consentimiento para ell; en este caso,
                        se deber?? usar el cuidado razonable para proteger la Informaci??n Confidencial de la
                        parte que corresponda.
                    </li>
                    <br>
                    <li>
                        <p>
                            CONTRAPRESTACI??N. El Proveedor de contenido acepta pagar a Apithy las tarifas
                            y/o importes especificados en el Plan seleccionado.
                        </p>
                        <p>
                            Para ello, el Proveedor de contenido debe proporcionar a Apithy la informaci??n
                            de facturaci??n correcta y completa, incluido el nombre legal, la direcci??n, el
                            n??mero de tel??fono y una tarjeta de cr??dito v??lida.??La tarjeta del proveedor de
                            contenido nunca ser?? cargada sin su autorizaci??n.??Al enviar dicha informaci??n de
                            la tarjeta de cr??dito, el Proveedor de contenido otorga a Apithy permiso para
                            realizar todos los cargos incurridos a trav??s de su cuenta a la tarjeta de
                            cr??dito designada.??
                        </p>
                        <p>
                            Los Servicios se facturan por adelantado en forma mensual o anual, seg??n el plan
                            de pago elegido por el Proveedor de contenido.??Apithy no proporcionar??
                            reembolsos o cr??ditos en el caso de cancelaciones, rebajas o cuando haya partes
                            no utilizadas de los Servicios en una cuenta abierta. Todos los futuros cargos
                            recurrentes por los Servicios seguir??n el ciclo de facturaci??n mensual o anual
                            (seg??n lo elija el Proveedor de contenido).
                        </p>
                        <p>
                            En caso de falta de pago, los servicios ser??n rescindidos de manera inmediata.
                        </p>
                    </li>
                    <br>
                    <li>
                        L??MITE DE RESPONSABILIDAD. Con excepci??n de lo especificado aqu??, los servicios se
                        proporcionan "<i>tal cual</i>" sin garant??a de ning??n tipo.??Apithy no garantiza que los
                        servicios cumplen los requisitos del proveedor de contenido o que su operaci??n sea
                        ininterrumpida o libre de errores.
                    </li>
                    <br>
                    <li>
                        <p>
                            VIGENCIA. Este Acuerdo comenzar?? a partir de la fecha en que sea aceptado.??Con
                            respecto a un usuario de la Versi??n de pago, este Acuerdo continuar?? en vigor
                            durante el plazo inicial especificado en el Plan (o si no se especifica dicho
                            t??rmino, durante el ciclo de facturaci??n), y se renovar?? de manera autom??tica
                            con base en el plan seleccionado. Sin embargo, cualquiera de las partes puede
                            optar por no renovar este Acuerdo mediante notificaci??n por escrito de dicha
                            elecci??n, con por lo menos un mes antes en que ocurra dihca renovaci??n.??
                        </p>
                        <p>
                            En el caso del Proveedor de contenido, ??ste ser?? el ??nico responsable de
                            notificar correctamente a Apithy sobre su elecci??n de no renovar
                            autom??ticamente, siguiendo las instrucciones de cancelaci??n disponibles en la
                            cuenta de Apithy del Proveedor de contenido.??Con respecto a un usuario de la
                            Versi??n gratuita, este Acuerdo continuar?? vigente hasta que cualquiera de las
                            partes finalice este Acuerdo con al menos 5 d??as h??biles de notificaci??n por
                            escrito a la otra parte.
                        </p>
                        <p>
                            Igualmente, el Proveedor de contenido acepta desde ahora que Apithy tendr?? la
                            facultad para retirar de la plataforma el contenido que le fue prove??do, en caso
                            de que ??l mismo no tenga la recurrencia necesaria de explotaci??n (seg??n el
                            criterio de Apithy), o bien cuando el tema que aborda el propio contenido no es
                            de actualidad.
                        </p>
                    </li>
                    <br>
                    <li>
                        <p>
                            LINEAMIENTOS EN MATERIA DE DERECHOS DE AUTOR (Copyright). Es pol??tica de Apithy,
                            en materia de derechos de autor: (a) bloquear el acceso o eliminar Contenido que
                            cree de buena fe, es material protegido por derechos de autor y que ha sido
                            copiado y distribuido ilegalmente por cualquiera de sus afiliados, usuarios y/o
                            proveedores de contenido, y; (b) eliminar e interrumpir el servicio a
                            reincidentes.
                        </p>
                        <p>
                            Si cualquier tercero considera que el Contenido que reside o es accesible a
                            trav??s de la plataforma infringe un derecho de autor, podr?? enviar un <i>aviso de
                                infracci??n de copyright</i> que contenga la siguiente informaci??n:
                        </p>
                        <ol type="a">
                            <li>
                                Una firma f??sica o electr??nica de una persona autorizada para actuar en
                                nombre del propietario de los derechos de autor que presuntamente se ha
                                infringido;
                            </li>
                            <li>
                                Identificaci??n de obras o materiales objeto de infracci??n;
                            </li>
                            <li>
                                Identificaci??n del Contenido que se alega que infringe, incluida la
                                informaci??n sobre la ubicaci??n del Contenido que el propietario de los
                                derechos de autor desea eliminar, con detalles suficientes para que Apithy
                                sea capaz de encontrar y verificar su existencia;
                            </li>
                            <li>
                                Informaci??n de contacto sobre el notificador, incluida la direcci??n, el
                                n??mero de tel??fono y, si est?? disponible, la direcci??n de correo
                                electr??nico;
                            </li>
                            <li>
                                Una declaraci??n de que el notificador cree de buena fe que el Contenido no
                                est?? autorizado por el propietario de los derechos de autor, su agente o la
                                ley,??y;
                            </li>
                            <li>
                                Una declaraci??n bajo protesta de decir verdad, de que la informaci??n
                                proporcionada es correcta y la parte notificante est?? autorizada para
                                presentar la queja en nombre del propietario de los derechos de autor.
                            </li>
                        </ol>
                        <p>
                            Una vez que Apithy reciba dicha notificaci??n de infracci??n, proceder?? conforme a
                            lo siguiente:
                        </p>
                        <ol type="a">
                            <li>
                                Eliminar?? y deshabilitar?? el accesos al contenido del infractor;
                            </li>
                            <li>
                                Notificar?? a la persona que proveyo dicho Contenido, y/o al usuario que
                                recurrentemente lo usa.
                            </li>
                        </ol>
                    </li>
                    <br>
                    <li>
                        ACUERDO TOTAL. Los t??rminos y condciones aqu?? vertidos, junto con la pol??tica de
                        privacidad de Apithy y los Planes tarifarios aplicables, constituyen el acuerdo
                        total y reemplaza todas las negociaciones, entendimientos o acuerdos anteriores
                        (orales o escritos) entre las partes sobre los servicios.??En caso de conflicto o
                        inconsistencia entre el Acuerdo y el Plan, los t??rminos y condiciones de los Planes
                        tarifarios prevalecer??n y ser??n dominantes.
                    </li>
                    <br>
                    <li>
                        JURISDICCI??N. Este Acuerdo se regir?? e interpretar?? de acuerdo con las leyes de los
                        Estados Unidos Mexicanos; espec??ficamente, con las leyes y tribunales de la Ciudad
                        de M??xico.
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection