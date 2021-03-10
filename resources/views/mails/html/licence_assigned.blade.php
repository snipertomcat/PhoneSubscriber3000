@extends('layouts.email')

@section('body')
    <table cellpadding="0" cellspacing="0" class="es-content" align="center"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
        <tr style="border-collapse:collapse;">
            <td align="center" style="padding:0;Margin:0;">
                <table bgcolor="transparent" class="es-content-body" align="center" cellpadding="0" cellspacing="0"
                       width="600"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
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
                                                <td align="left" style="padding:0;Margin:0;padding-bottom:15px;">
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        En <strong>{{ $user->company[0]->name }}</strong> creemos en ti.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;">
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        Te hemos asignado nuevas experiencias para continuar con tu formación. Encuéntralas en  tu perfil de apithy aNGLE.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-collapse:collapse;">
                        <td align="left" bgcolor="#ffffff"
                            style="padding:0;Margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;background-color:#FFFFFF;border-left:1px solid lightgray;border-right:1px solid lightgray;border-bottom:1px solid lightgray;">
                            <table cellpadding="0" cellspacing="0" width="100%"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tr style="border-collapse:collapse;">
                                    <td class="es-m-p20b" width="560" align="left"
                                        style="padding:0;Margin:0;padding-left:30px;padding-right:30px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" bgcolor="transparent"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-left:1px solid lightgray;border-right:1px solid lightgray;border-top:1px solid lightgray;border-bottom:1px solid lightgray;background-color:transparent;border-radius:10px;">
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;">
                                                    <img class="adapt-img"
                                                         src="{{ $experience->full_path_cover }}"
                                                         alt=""
                                                         style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                         width="496">
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left"
                                                    style="padding:0;Margin:0;padding-top:15px;padding-left:15px;padding-right:15px;">
                                                    <h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333;">
                                                        {{ $experience->title }}
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;padding-bottom:15px;">
                                                    <br><br>
                                                    <span class="es-button-border"
                                                          style="border-style:solid;border-color:#2CB543;background:#E69138;border-width:0px;display:inline-block;border-radius:5px;width:auto;">
                                                        <a href="{{ $url}}"
                                                           class="es-button"
                                                           target="_blank"
                                                           style="mso-style-priority:100 !important;text-decoration:none !important;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#E69138;border-width:10px 20px 10px 20px;display:inline-block;background:#E69138;border-radius:5px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">
                                                            Comenzar
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;padding-bottom:15px;">
                                                    <br>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        ¿No funciona el botón? Copia y pega el siguiente link en tu navegador:
                                                    </p>
                                                    <a href="{{ $url }}" target="_blank">
                                                        {{ $url }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="padding:0;Margin:0;padding-bottom:15px;">
                                        <br><br>
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
@endSection