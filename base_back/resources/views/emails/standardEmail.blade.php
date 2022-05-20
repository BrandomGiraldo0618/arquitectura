<style type="text/css">  
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');
    p, h3, h5, ol, li, ul, a {  font-family: 'Poppins', sans-serif;  } 
</style>

<div style="width:100%; background-color:#FFFFFF; height:auto; overflow:hidden; margin:0 auto;font-family: 'Poppins', sans-serif;">
    <table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width: 752px;width: 90%;">
        <tr>
            <td colspan="6" align="center">
                <img style="width:100%;" src="{{asset('img/mailing/mail_top.png')}}" />
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <br><br><br>
            </td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="4" style="color: #23de6e;">
                <h5 style="font-size: 24px; margin: 0;">Telemedicina</h5>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="5%">&nbsp;</td>
            <td colspan="4" width="90%" style="vertical-align: baseline;">
                <h3 style="color: #AEAEAE; font-weight: 100;">Sr(a).<br>{{$name}}</h3>
                
                <p style="color: #AEAEAE;">
                    {!! $html_message !!}
                </p>

                <br />
                <br />
                <div style="text-align: center;">
                    <a style="background: transparent linear-gradient(60deg, #23de6e 0%, #ffdf17 100%) 0% 0% no-repeat padding-box;    border-radius: 35px;border: 0;height: 30px;padding: 6px 25px;color: #fff;font-size: 15px; text-decoration: none;" href="{{$url_site}}">
                        {{$button_name}}
                    </a>
                </div>
                <br />
                <br />
            </td>
            <td width="5%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" align="center">
                <img style="width:100%;" src="{{asset('img/mailing/mail_footer.png')}}" />
            </td>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
    </table>
</div>