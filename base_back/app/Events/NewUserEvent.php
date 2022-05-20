<?php

namespace App\Events;

use App\Models\User;
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user, $data)
    {
        $this->user = $user;

        $data = [
            'name_user' => $data['name'],
            'email' => $data['email'],
            'subject' => 'Usuario Registrado'
        ];

        $this->sendEmail($data);
    }

    function sendEmail($data)
    {
        try {
            $message = $this->getMessageUserCreated($data['name_user'], $data['email']);

            $from = new \SendGrid\Mail\From('no-reply@manizalesdigital.co', 'Gestam');
            $to = new \SendGrid\Mail\To($data['email']);
            $subject = new \SendGrid\Mail\Subject($data['subject']);
            $htmlContent = new \SendGrid\Mail\HtmlContent($message);
            $email = new \SendGrid\Mail\Mail($from, $to, $subject, null, $htmlContent);
            $sendgrid = new \SendGrid(config('app.sendgrid_api_key'));
            $response = $sendgrid->send($email);

            $response = ['success' => true, 'message' => 'Tu mensaje ha sido enviado al correo de contacto!.'];
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], 500);
        }

        return response()->json($response, 200);
    }

    private function getMessageUserCreated($name_user, $email)
    {
        return '<body style="width: 100%; font-size: 62.5%; text-align: center;">
                    <header>
                        <img style="width: 40%; height: 40%;" src="https://gestam.com.co/wp-content/uploads/2019/06/Logotipo-GESTAM.png" alt="">
                    </header>
                    <main>
                        <section>
                            <h1 style="color: #004465; font-size: 2.8rem;">Usuario registrado con exito</h1>
                            <div style="display: inline-block; width: 80%; text-align: center;">
                                <p style="font-size: 1.6rem;"><b>Nombre de Usuario:</b> '.$name_user.' <strong><img style="width: 3%; height: 3%;" src="http://assets.stickpng.com/images/585e4beacb11b227491c3399.png" alt=""></strong></p>
                                <p style="font-size: 1.6rem;"><b>Email:</b> '.$email.' <strong><img style="width: 3%; height: 3%;" src="http://assets.stickpng.com/images/58485698e0bb315b0f7675a8.png" alt=""></strong></p>
                            </div>
                        </section>
                    </main>
                    <footer style="display: inline-block; width: 80%; background-color: #004465; text-align: center;">
                        <p style="color: white; font-size: 1.2rem;">Mensaje generado automaticamente desde la plataforma</p>
                    </footer>
                </body>';
    }
}
