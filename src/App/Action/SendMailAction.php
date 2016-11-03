<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 03.10.16
 * Time: 10:39
 */

namespace App\Action;


use PHPMailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SendMailAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = json_decode($response->getReasonPhrase(), true);
        $data = $data["mail"];
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'ssl://smtp.googlemail.com';              // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = 'relaypimails@gmail.com';             // SMTP username
        $mail->Password = 'iekupwvgqumtfuwt';                   // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                      // TCP port to connect to

        $mail->setFrom('relaypimails@gmail.com', $data['sender']);
        $mail->addAddress($data['receiver']);                      // Add a recipient
        $mail->isHTML(true);                                    // Set email format to HTML


        $mail->Subject = $data['subject'];
        $mail->Body = $data['contentHtml'];
        $mail->AltBody = $data['content'];
        
        if (!$mail->send()) {

            return new JsonResponse(
                [
                    'data' => false,
                    'error' => "Message could not be sent.\n" . $mail->ErrorInfo,
                ]
            );
        }
        return $next ($request, $response);
    }
}