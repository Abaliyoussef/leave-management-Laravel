<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
 
  <style>
    table, td, div, h1, p {font-family: Arial, sans-serif;}
  </style>
</head>
<body style="margin:0;padding:0;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
      <td align="center" style="padding:0;">
        <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
          <tr>
            <td align="center" style="padding:40px 0 30px 0;background:#ffffff;">
            <a href="https://www.tasmimweb.com/">
            <img src="{{$message->embed('Image/LOGO.png')}}" alt="" width="300" style="height:auto;display:block;" data-auto-embed="attachment" />
            </a>
            </td>
          </tr>
          <tr>
            <td style="padding:36px 30px 42px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 36px 0;color:#153643;">
                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Cher(e) <b>{{ $userName }}</b>,<br>

                        Nous avons le plaisir de vous informer que votre  congé de <b> {{ $conge->duree }} jour(s) </b> a été acceptée. Vos dates de congé demandées, du <b> {{ $conge->date_debut }}  au {{ $conge->date_fin }}</b>, ont été approuvées.<br>

                        Nous vous souhaitons un congé agréable et reposant, et nous nous réjouissons de vous retrouver à votre retour.<br>

                        Si vous avez des questions supplémentaires ou avez besoin d'assistance, n'hésitez pas à contacter le service des ressources humaines.<br>

                        Cordialement,
                    </p>
                  </td>
                </tr>
                
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:15px;background:#ffffff;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                <tr>
                  <td style="padding:0;width:50%;" align="left">
                    <h1 style="margin:0;font-size:1h2x;line-height:16h3x;font-family:Arial,sans-serif;color:#000000;">
                       Tasmim web</h1><br/>
                       <h2  style="color:#000000;text-decoration:underline;text-size:7px">+212 6 66 67 16 07​</h2>
                       <h2  style="color:#000000;text-decoration:underline;">contact@tasmimweb.com​​</h2>
                    
                  </td>
                  
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>