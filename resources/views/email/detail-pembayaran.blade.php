<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="initial-scale=1.0" name="viewport">
    <meta content="telephone=no" name="format-detection">
    <title>Indofund.id - detail pembayaran</title>
    <style type="text/css">
        .socialLinks {font-size: 6px;}
        .socialLinks a {display: inline-block;}
        .socialIcon {display: inline-block;vertical-align: top;padding-bottom: 0px;border-radius: 100%;}
        table.vb-row, table.vb-content {border-collapse: separate;}
        table.vb-row {border-spacing: 9px;}
        table.vb-row.halfpad {border-spacing: 0;padding-left: 9px;padding-right: 9px;}
        table.vb-row.fullwidth {border-spacing: 0;padding: 0;}
        table.vb-container.fullwidth {padding-left: 0;padding-right: 0;}
    </style>
    <style type="text/css">
        /* yahoo, hotmail */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{ line-height: 100%; }
        .yshortcuts a{ border-bottom: none !important; }
        .vb-outer{ min-width: 0 !important; }
        .RMsgBdy, .ExternalClass{
            width: 100%;
            background-color: #3f3f3f;
            background-color: #3f3f3f}

        /* outlook */
        table{ mso-table-rspace: 0pt; mso-table-lspace: 0pt; }
        #outlook a{ padding: 0; }
        img{ outline: none; text-decoration: none; border: none; -ms-interpolation-mode: bicubic; }
        a img{ border: none; }

        @media screen and (max-device-width: 600px), screen and (max-width: 600px) {
            table.vb-container, table.vb-row{
                width: 95% !important;
            }

            .mobile-hide{ display: none !important; }
            .mobile-textcenter{ text-align: center !important; }

            .mobile-full{
                float: none !important;
                width: 100% !important;
                max-width: none !important;
                padding-right: 0 !important;
                padding-left: 0 !important;
            }
            img.mobile-full{
                width: 100% !important;
                max-width: none !important;
                height: auto !important;
            }
        }
    </style>
    <style type="text/css">
        #ko_textBlock_4 .links-color a:visited, #ko_textBlock_4 .links-color a:hover {color: #3f3f3f;color: #3f3f3f;text-decoration: underline;}
        #ko_footerBlock_2 .links-color a, #ko_footerBlock_2 .links-color a:link, #ko_footerBlock_2 .links-color a:visited, #ko_footerBlock_2 .links-color a:hover {color: #ccc;color: #ccc;text-decoration: underline;}
    </style>
</head>
<body alink="#CCCCCC" bgcolor="#3F3F3F" style="margin: 0;padding: 0;background-color: #3f3f3f;color: #919191;" text="#919191" vlink="#CCCCCC">
<center>
    <!-- HEADER -->
    @include('email.partial._header')

    <table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="vb-outer" id="ko_titleBlock_3" style="background-color: #fff;" width="100%">
        <tbody>
        <tr>
            <td align="center" bgcolor="#fff" class="vb-outer" style="padding-left: 9px;padding-right: 9px;background-color: #fff;" valign="top">
                <!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
                <div class="oldwebkit" style="max-width: 650px;">
                    <table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="9" class="vb-container halfpad" style="border-collapse: separate;border-spacing: 9px;padding-left: 9px;padding-right: 9px;width: 100%;max-width: 650px;background-color: #fff;" width="650">
                        <tbody>
                        <tr>
                            <td align="center" bgcolor="#FFFFFF" style="background-color: #ffffff; font-size: 16px; font-family: Verdana, Geneva, sans-serif; color: #3f3f3f; text-align: center; line-height: 1.6;">
                                <p>Kepada Yth : {{$user->first_name}} {{$user->last_name}}</p>
                                <p>Kami telah menerima order pendanaan dengan detail sebagai berikut :<br/>
                                    Nama Proyek : {{$product->name}}<br/>
                                    Masa Pinjaman : {{$product->tenor_loan}} bulan<br/>
                                    Sebesar Rp. {{$transaction->total_price}} <br/>
                                    pembayaran dapat dilakukan maksimal 6 jam dari {{ \Carbon\Carbon::parse($transaction->created_on)->format('j M Y G:i:s') }}
                                </p>
                                <p>
                                    Untuk membaca product disclosure statement, silahkan
                                    <span data-mce-style="color: #81b951;" style="color: rgb(129, 185, 81);"><strong><a data-mce-style="color: #81b951;" href="{{$product->prospectus_path}}" style="color: #81b951;text-decoration: underline;" title="{{$product->prospectus_path}}">klik disini</a></strong></span>
                                </p>
                            </td>
                        </tr>
                        @if($transaction->payment_method_id != 1)
                            <tr>
                                <td align="center" bgcolor="#FFFFFF" style="background-color: #ffffff; font-size: 14px; font-family: Verdana, Geneva, sans-serif; color: #3f3f3f; text-align: center; line-height: 1.6;"><span>Besaran pendanaan yang anda lakukan adalah Rp. <b>{{$transaction->total_price}}</b>  pada proyek ini.<br/>
                                    Sebesar Rp. {{$transaction->total_price}}  </span></td>
                            </tr>
                        @else
                            <tr>
                                <td align="center" bgcolor="#FFFFFF" style="background-color: #ffffff; font-size: 14px; font-family: Verdana, Geneva, sans-serif; color: #3f3f3f; text-align: center; line-height: 1.6;">
                                    <span>
                                        Besaran pendanaan yang anda lakukan adalah Rp. {{$transaction->total_price}}  pada proyek ini.<br/>
                                        Silahkan lakukan pembayaran ke : {{$user->va_acc}} atas nama indofund.id ({{$user->first_name}} {{$user->last_name}}), Bank Mandiri Virtual Account<br>
                                        Sebesar Rp. {{$transaction->total_price}}
                                    </span>
                                    <br>
                                </td>
                            </tr>

                            <!-- PAYMENT INSTRUCTION -->
                            @include('email.partial._payment-instruction')
                        @endif
                        </tbody>
                    </table>
                </div>
                <!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
            </td>
        </tr>
        </tbody>
    </table>

    <!-- FOOTER -->
    @include('email.partial._footer')
</center>
</body>
</html>