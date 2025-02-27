<?php
declare(strict_types=1);

namespace App\User\Application\Services {

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Psr\Log\LoggerInterface;

    class WelcomeEmailService
    {
        private PHPMailer $mailer;
        private LoggerInterface $logger;

        public function __construct(LoggerInterface $logger)
        {
            $this->mailer = new PHPMailer(true);
            $this->logger = $logger;
            $this->setupMailer();
        }

        private function setupMailer(): void
        {
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['SMTP_HOST'] ?? 'smtp.example.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['SMTP_USER'] ?? 'tu-usuario';
            $this->mailer->Password = $_ENV['SMTP_PASS'] ?? 'tu-contraseña';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = 587;
            $this->mailer->setFrom('ddd@gmail.com', 'Domain Driven Design LLC');
        }

        public function sendWelcomeEmail(string $to, string $name): bool
        {
            try {
                $subject = "Bienvenido, $name!";
                $body = $this->generateWelcomeEmail($name);

                $this->mailer->addAddress($to);
                $this->mailer->Subject = $subject;
                $this->mailer->Body = $body;
                $this->mailer->isHTML(true);

                $this->mailer->send();

                $this->logger->info("Correo de bienvenida enviado a {$to}");
                return true;
            } catch (Exception $e) {
                $this->logger->error("Error al enviar correo a {$to}: " . $this->mailer->ErrorInfo);
                return false;
            }
        }

        private function generateWelcomeEmail(string $name): string
        {
            return "
            <h1>¡Bienvenido, $name! 🎉</h1>
            <p>Gracias por unirte a nuestra plataforma. Esperamos que disfrutes de tu experiencia.</p>
            <p>Si tienes alguna duda, no dudes en contactarnos.</p>
            <br>
            <p>Saludos,</p>
            <p><strong>El equipo de Domain Driven Design LLC.</strong></p>
        ";
        }
    }
}
