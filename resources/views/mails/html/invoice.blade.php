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
                                                <td align="left" style="padding:0;Margin:0;padding-bottom:15px;"><h2
                                                            style="Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;">
                                                        Hola {{ $transaction->user->name }}</h2></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;Margin:0;">
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                        ¡Gracias por elegir comprar en Apithy! Pronto estarás aprendiendo y divirtiéndote.
                                                    </p>
                                                    <br>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @foreach($transaction->details as $detail)
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
                                                                                                    src="{{ $detail->experience->full_path_cover }}"
                                                                                                    alt=""
                                                                                                    style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                                                                    width="496"></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="left"
                                                    style="padding:0;Margin:0;padding-top:15px;padding-left:15px;padding-right:15px;">
                                                    <h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:bold;color:#333333;">
                                                        {{ $detail->experience->title }}
                                                    </h3>
                                                    <span style="padding-top: 10px;display: block">
                                                        {{ $detail->quantity }} {{ ($detail->quantity >1) ? 'licencias':'licencia' }} {{ $detail->personal_use? 'personal':'' }} <strong>{{ \Akaunting\Money\Money::USD($detail->price, true) }}(MXN)</strong>
                                                    </span>
                                                    @if($transaction->paymentSource()->first())
                                                        <span style="padding-top: 10px;display: block">Pagado v&iacute;a tarjeta <strong>{{ $transaction->paymentSource()->first()->card_type }}</strong> terminaci&oacute;n <strong>{{ $transaction->paymentSource()->first()->card_last_four }}</strong></span><br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <!--
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;padding-bottom:15px;"><br><br><span
                                                            class="es-button-border"
                                                            style="border-style:solid;border-color:#2CB543;background:#E69138;border-width:0px;display:inline-block;border-radius:5px;width:auto;"><a
                                                                href="{{ route('experience.preview',[$detail->experience]) }}" class="es-button" target="_blank"
                                                                style="mso-style-priority:100 !important;text-decoration:none !important;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#E69138;border-width:10px 20px 10px 20px;display:inline-block;background:#E69138;border-radius:5px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">Comenzar</a></span>
                                                </td>
                                            </tr>
                                            -->
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
@endSection