@extends('layouts.email')

@section('body')
    <table cellpadding="0" cellspacing="0" class="es-content" align="center"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
        <tr style="border-collapse:collapse;">
            <td align="center" style="padding:0;Margin:0;">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0"
                       cellspacing="0" width="600"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                    <tr style="border-collapse:collapse;">
                        <td align="left"
                            style="Margin:0;padding-left:10px;padding-right:15px;padding-top:20px;padding-bottom:20px;background-position:center top;background-color:transparent;border:solid 1px lightgray;"
                            bgcolor="transparent">
                            <table cellpadding="0" cellspacing="0" width="100%"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tr style="border-collapse:collapse;">
                                    <td width="575" align="center" valign="top" style="padding:0;Margin:0;">
                                        <table cellpadding="0" cellspacing="0" width="100%"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td align="left"
                                                    style="padding:0;Margin:0;padding-bottom:15px;"><h2
                                                            style="Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;">
                                                        {{ $title }}</h2></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;"><p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        {!!html_entity_decode($body)!!}
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <br></p></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" class="es-m-txt-c"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                <span class="es-button-border"
                                                                      style="border-style:solid;border-color:#2CB543;background:#E69138;border-width:0px 0px 2px 0px;display:inline-block;border-radius:6px;width:auto;border-bottom-color:#EFEFEF;"> <a
                                                                            href="{{ $confirm_url }}" class="es-button"
                                                                            target="_blank"
                                                                            style="mso-style-priority:100 !important;text-decoration:none !important;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#E69138;border-width:10px 20px 10px 20px;display:inline-block;background:#E69138;border-radius:6px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">{{$button_label}}</a> </span>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;"><p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        {!!html_entity_decode($body_bottom)!!}
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <br></p></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;"><p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <a href="{{$confirm_url}}">{{$confirm_url}}</a>
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <br></p></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;"><p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        {!!html_entity_decode($body_closure)!!}
                                                    </p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        <br></p></td>
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
@endSection