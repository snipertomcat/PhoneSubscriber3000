@extends('layouts.website')

@section('title','Política de cookies')

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
                <h2>Política de cookies</h2>
                Fecha efectiva: 1 de noviembre de 2018.
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 text-justify">
                <p class="ident-50">
                    En <b>Apithy</b>, creemos que debemos ser transparentes sobre cómo recopilamos y utilizamos datos. Esta
                    política brinda información sobre cómo y cuándo utilizamos <i>cookies</i> para estos fines. Los términos
                    en
                    mayúsculas utilizados en esta política que no estén definidos, cuentan con su significado establecido en
                    nuestra <b>Política de privacidad</b>, que también incluye detalles adicionales sobre la recopilación y
                    el uso
                    de información en <b>Apithy</b>.
                </p>
                <p><b>¿Qué es una cookie?</b></p>
                <p class="ident-50">
                    Las cookies son pequeños archivos de texto que enviamos a tu computadora o dispositivo móvil que
                    habilitan prestaciones y funcionalidades de los <b>Servicios</b> de la <b>Plataforma</b>. Son únicas
                    para tu cuenta o
                    navegador. Las <i>cookies</i> de sesión solo duran mientras tu navegador está abierto y se borran
                    automáticamente al cerrar el navegador. Las <i>cookies</i> persistentes permanecen hasta que tú o tu
                    navegador
                    las borren o hasta que caduquen.
                </p>
                <p><b>¿Apithy utiliza cookies?</b></p>
                <p class="ident-50">
                    Sí. Apithy utiliza <i>cookies</i> de sesión y cookies persistentes. Establecemos y obtenemos acceso a
                    nuestras
                    cookies en el dominio operado por nosotros. Además, en algunas ocasiones, utilizaremos cookies de
                    terceros; actualmente, solo la de google analytics.
                </p>
                <p><b>¿Cómo utiliza Apithy las cookies?</b></p>
                <p class="ident-50">
                    Algunas cookies se asocian a tu cuenta e información personal para recordar que estás registrado y
                    cuáles son las experiencias a las que estás vinculado. Otras <i>cookies</i> no están vinculadas a tu
                    cuenta,
                    pero son únicas y nos permiten llevar a cabo un análisis de sitio y personalizaciones, entre otras
                    cosas.
                </p>
                <p>
                    Las <i>cookies</i> se pueden utilizar para reconocer cuándo visitas un sitio o utilizas nuestros <b>Servicios</b>,
                    recordar tus preferencias y darte una experiencia personalizada que sea consistente con tus ajustes. Las
                    <i>cookies</i> también hacen que tus interacciones sean más rápidas y seguras. Estas son las categorías
                    de uso
                    que aplicamos:
                </p>
                <div class="row">
                    <div class="offset-2 col-8">
                        <div class="text-center">
                            <table border="1">
                                <tr>
                                    <th>
                                        <b>Categorías de uso</b>
                                    </th>
                                    <th>
                                        <b>Descripción</b>
                                    </th>
                                </tr>
                                <tr>
                                    <td><b>Autenticación</b></td>
                                    <td>
                                        <p>
                                            Si estás registrado en nuestros <b>Servicios</b> las <i>cookies</i> nos ayudan a
                                            mostrarte la información correcta y a personalizar tu experiencia.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Seguridad</b>
                                    </td>
                                    <td>
                                        <p>
                                            Utilizamos <i>cookies</i> para habilitar y respaldar nuestras funciones de
                                            seguridad y
                                            para que nos ayuden a detectar actividad maliciosa.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Preferencias, funciones y servicios</b>
                                    </td>
                                    <td>
                                        <p>
                                            Las <i>cookies</i> pueden indicarnos qué idioma prefieres y cuáles son tus
                                            preferencias
                                            de comunicación. Pueden ayudarte a llenar formularios en la <b>Plataforma</b>
                                            más
                                            fácilmente. Además, te brindan funciones, perspectivas y contenido
                                            personalizado.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Marketing</b>
                                    </td>
                                    <td>
                                        <p>
                                            Podemos usar <i>cookies</i> que nos ayuden a ofrecer campañas de marketing y
                                            realizar
                                            un seguimiento de tu desempeño. Del mismo modo, nuestros socios comerciales
                                            pueden usar <i>cookies</i> para brindarnos información sobre tus interacciones
                                            con sus
                                            servicios, pero el uso de las <i>cookies</i> de terceros estaría sujeto a las
                                            políticas
                                            del proveedor de servicios.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Rendimiento, análisis e investigación</b>
                                    </td>
                                    <td>
                                        <p>
                                            Las <i>cookies</i> nos ayudan a conocer qué tan bien funcionan nuestra <b>Plataforma</b>
                                            y los
                                            <b>Servicios</b>. También utilizamos <i>cookies</i> para comprender, mejorar e
                                            investigar
                                            productos y funciones, para crear registros y grabar cuándo accedes a nuestra
                                            <b>Plataforma</b> desde distintos dispositivos.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <p>
                    <b>¿Qué puedes hacer si no quieres que se establezcan las <i>cookies</i>, si quieres eliminarlas o si
                        quieres que te excluyan de la publicidad basada en intereses?</b>
                </p>
                <p class="ident-50">
                    Algunas personas prefieren no aceptar <i>cookies</i>, razón por la cual la mayoría de los navegadores
                    tienen la
                    posibilidad de gestionarlas a su medida. En algunos navegadores, puedes configurar y gestionar las
                    opciones de <i>cookies</i> lo que te da más control sobre tu privacidad. Esto significa que puedes
                    rechazarlas
                    de todos los sitios, salvo de aquellos en los que confías.
                </p>
                <p class="ident-50">
                    Sin embargo, si limitas la habilidad de los sitios web y aplicaciones de establecer <i>cookies</i>,
                    puedes
                    dificultar tu experiencia general como <b>Usuario</b> o perder la habilidad de acceder a los <b>Servicios</b>,
                    ya que
                    no estará personalizado para ti. Además, esta opción puede no permitirte guardar ajustes, como la
                    información de inicio de sesión.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="font-12">
                    <b>Apithy</b>. Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México. <b>Todos los
                        derechos reservados.</b>
                </p>
            </div>
        </div>
    </div>
@endsection