@extends('layouts.website')

@section('title','Aviso de privacidad')

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
                <h2>Apithy: PLATAFORMA DE SERVICIOS DE GESTIÓN DE APRENDIZAJE</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <h4>Aviso de privacidad</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 text-justify">
                <p class="ident-50">
                    Tu privacidad y confianza son muy importantes para nosotros. Por ello, y en cumplimiento con lo
                    establecido por la Ley Federal de Protección de Datos Personales en Posesión de Particulares,
                    <b>Geekstadium, S. A. P. I. de C. V.</b> (en lo sucesivo <b>“Apithy”</b>) hace de tu conocimiento la
                    normativa
                    de privacidad y uso de datos personales que rige en su organización, misma que en todo momento impone
                    que el tratamiento de los mismos sea legítimo, seguro, controlado e informado, a efecto de garantizar la
                    privacidad de los mismos, cumpliendo para ello con los principios rectores de protección de datos
                    personales.
                </p>
                <p class="ident-50">
                    En consecuencia, <b>Apithy</b> tiene la calidad de <i><b>Responsable</b></i> de tus datos personales
                    (según se define este
                    concepto en la citada ley), quien tiene su domicilio legal en Mayorazgo de Orduña No. 35, Colonia Xoco,
                    C. P. 03330, Ciudad de México, por lo que los datos personales que actualmente o en el futuro obren en
                    nuestras bases de datos, y que proporciones a <b>Apithy</b>, serán tratados y utilizados única y
                    exclusivamente
                    por ella, y por aquellos terceros que, por la naturaleza de sus trabajos o funciones tengan la necesidad
                    de tratar y/o utilizar para los fines que en forma enunciativa pero no limitativa se describen a
                    continuación: para identificarte, ubicarte, comunicarte, contactarte, enviarte información, etc.; por
                    cualquier medio, incluyendo el electrónico y/o a través de la red pública mundial conocida como
                    internet. <b>Apithy</b> podrá transferir los datos personales que obren en sus bases de datos única y
                    exclusivamente a terceros que tengan la calidad de instructores o socios comerciales, salvo que tú, en
                    calidad de <i><b>Titular</b></i>, manifiestes expresamente tu oposición, en términos de los dispuesto
                    por la Ley
                    Federal de Protección de Datos Personales en Posesión de los Particulares. En todo momento, el uso de
                    los datos personales tendrá relación con el tipo de interacción jurídica que tienes con <b>Apithy</b>:
                    ya sea
                    comercial, civil, mercantil o de cualquier otra naturaleza.
                </p>
                <p class="ident-100">
                    La temporalidad del manejo de los datos personales será indefinida, a partir de la fecha en que tú nos
                    la proporcionaste.
                </p>
                <p class="ident-50">
                    Podrás ejercer los derechos de acceso, rectificación, cancelación u oposición (Derechos A. R. C. O.) que
                    le confiere la Ley, en cualquier momento, dirigiendo tu solicitud en atención al área de Privacidad
                    mediante: (i) correo electrónico a la dirección <a href="mailto:legal@apithy.com">legal@apithy.com</a>.,
                    o bien; (ii) en el siguiente
                    domicilio: Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México, en días hábiles de
                    las 9:00 a las 16:00 horas. Para lo anterior, deberás hacernos saber fehacientemente mediante documento
                    escrito físico y/o electrónico según corresponda, los datos personales que deseas sean rectificados,
                    cancelados o revisados, así como el propósito para el cual los aportaste, cumpliendo con los siguientes
                    requisitos:
                </p>
                <ol type="a">
                    <li>
                        <p>
                            Incluir tu nombre y firma autógrafa como <i><b>Titular</b></i>, así como un domicilio y/o
                            dirección de correo electrónico para comunicarte la respuesta a tu solicitud.
                        </p>
                    </li>
                    <li>
                        <p>
                            Acompañar los documentos oficiales que acrediten tu identidad.
                        </p>
                    </li>
                    <li>
                        <p>
                            Incluir una descripción clara y precisa de los datos personales respecto de los cuales ejercitas
                            los derechos que la ley te confiere.
                        </p>
                    </li>
                    <li>
                        <p>
                            Incluir cualquier elemento o documento que facilite la localización de los datos personales de
                            que se traten.
                        </p>
                    </li>
                </ol>
                <p class="ident-50">
                    <i><b>Apithy</b></i> se reserva el derecho de cambiar, modificar, complementar, alterar o ampliar el
                    presente aviso de
                    privacidad en cualquier tiempo, y lo mantendrá siempre a disposición a través de los medios que
                    establece la legislación en la materia.
                </p>
            </div>
            <div class="col-12">
                <p class="font-12">
                    <i><b>Apithy</b></i>. Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México.
                    <i><b>Todos los derechos reservados.</b></i>
                </p>
            </div>
        </div>
    </div>
@endsection
