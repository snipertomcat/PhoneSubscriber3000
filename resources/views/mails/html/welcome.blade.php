@extends('layouts.email')

@section('body')
<table cellpadding="0" cellspacing="0" class="es-content" align="center"
       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table bgcolor="transparent" class="es-content-body" align="center" cellpadding="0"
                   cellspacing="0" width="600"
                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
                <tr style="border-collapse:collapse;">
                    <td align="left" bgcolor="transparent"
                        style="Margin:0;padding-bottom:15px;padding-top:20px;padding-left:20px;padding-right:20px;background-color:transparent;">
                        <table cellpadding="0" cellspacing="0" width="100%"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td width="560" align="center" valign="top" style="padding:0;Margin:0;">
                                    <table cellpadding="0" cellspacing="0" width="100%"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;">
                                                <img
                                                    src="{{asset('images/mails/40521545882649573.png')}}"
                                                    alt=""
                                                    style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                    width="200">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="border-collapse:collapse;">
                    <td align="left"
                        style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-position:center top;background-color:#FFFFFF;border-left:1px solid lightgray;border-right:1px solid lightgray;border-top:1px solid lightgray;"
                        bgcolor="#ffffff">
                        <table cellpadding="0" cellspacing="0" width="100%"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td width="560" align="center" valign="top" style="padding:0;Margin:0;">
                                    <table cellpadding="0" cellspacing="0" width="100%"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td align="left"
                                                style="padding:0;Margin:0;padding-bottom:15px;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                <p>{{$full_name}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                    Eres una parte importante en el éxito de {{$company_name}}.
                                                </p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    <br>
                                                </p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                    Es por ello que te damos la bienvenida a apithy aNGLE, donde podrás crecer de una manera divertida y a tu ritmo.
                                                </p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                    <br></p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                    Aquí encontrarás los retos, experiencias y planes de desarrollo que te darán el conocimiento y dominio en un área de interés.
                                                </p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                    <br>
                                                </p>
                                            </td>
                                        </tr>
                                        @if($login_link)
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;">
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;text-align: left">
                                                        Establece tu contraseña y prepárate para vivir algo diferente.
                                                    </p>
                                                    <br>
                                                    <span class="es-button-border"
                                                          style="border-style:solid;border-color:#FCB662;background:#FCB662;border-width:0px;display:inline-block;border-radius:5px;width:auto;">
                                                        <a href="{{$login_link}}" class="es-button" target="_blank"
                                                           style="mso-style-priority:100 !important;text-decoration:none !important;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#FCB662;border-width:10px 20px 10px 20px;display:inline-block;background:#FCB662;border-radius:5px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">Establecer contraseña</a>
                                                    </span>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <br>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;">
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                        ¿No funciona el botón? Copia y pega el siguiente link en tu navegador:
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <a href="{{$login_link}}">{{$login_link}}</a>
                                                    </p>
                                                </td>
                                            </tr>
                                        @else
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;">
                                                    <a href="{{$url}}">
                                                    <img
                                                        class="adapt-img"
                                                        src="{{asset('images/mails/69241545883360455.png')}}" alt=""
                                                        style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                        width="250">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;">
                                                    <br>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;">
                                                        ¿No funciona el botón? Copia y pega el siguiente link en tu navegador:
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <a href="{{$url}}">{{$url}}</a>
                                                    </p>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    <br>
                                                </p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    ¡Te esperamos! <br>
                                                    Team aNGLE <br>
                                                    <strong>a</strong>pithy <strong>N</strong>ext <strong>G</strong>eneration <strong>L</strong>earning <strong>E</strong>nvironment
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
