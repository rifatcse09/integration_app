<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www..w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www..w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html charset=utf-8" />
    <meta http-equiv="X-UA-Compatibe" content="IE=edge" />
    <title>Bit Integrations Email</title>


    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans&family=Manrope&display=swap');

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            line-height: 1.5rem;
        }

        table {
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }
    </style>
</head>

<body>
    <div style="width: 100%; table-layout: fixed;  background-color: #F4FAFA; min-height: 100vh; border-spacing: 0;">
        <table width="100%">
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <table style=" background-repeat: no-repeat;width: 100%; min-height: 8rem;background-size: cover;">
                                    <tr>
                                        <td align="center">
                                            <span>
                                                @include("emails.brand-logo")
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table align="center" style="border-radius: 10px; background-color:#FFFFFF; width: 600px; min-width: 400px; ">
                                    <tr>
                                        <td align="center" style="padding: 20px;">
                                            <h1 style='font-size: 26px;font-style: normal; line-height: 2.625rem; letter-spacing: 0.005em; --tw-text-opacity: 1; color: rgb(32 32 32 / var(--tw-text-opacity));'>Dear {{$shop->title}},</h1>
                                            <p style='font-size:14px; font-weight: 400; font-style: normal;letter-spacing: 0.01em; --tw-text-opacity: 1; color: rgb(117 117 117 / var(--tw-text-opacity))'>Thank you for installing Bit Integrations! We're thrilled to have you on board.<br>Bit Integrations is designed to be the ultimate sales toolkit, helping you convert visitors into customers with ease. With our Sales Popup and Social Proof features, you’ll have everything you need to enhance your online store’s performance and drive sales. If you need help, you can reply to this email or contact us, we will get back to you.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
