<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\Services {

    use App\User\Application\Services\WelcomeEmailService;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPUnit\Framework\MockObject\MockObject;
    use PHPUnit\Framework\TestCase;
    use Psr\Log\LoggerInterface;
    use ReflectionMethod;
    use ReflectionProperty;

    final class WelcomeEmailServiceTest extends TestCase
    {
        private MockObject|PHPMailer $mailerMock;
        private MockObject|LoggerInterface $loggerMock;
        private WelcomeEmailService $welcomeEmailService;

        protected function setUp(): void
        {
            $this->mailerMock = $this->createMock(PHPMailer::class);
            $this->loggerMock = $this->createMock(LoggerInterface::class);
            $this->welcomeEmailService = new WelcomeEmailService($this->loggerMock);
            $reflection = new ReflectionProperty(WelcomeEmailService::class, 'mailer');
            $reflection->setAccessible(true);
            $reflection->setValue($this->welcomeEmailService, $this->mailerMock);
        }

        public function testSendWelcomeEmailSuccessfullySendsEmail(): void
        {
            $toEmail = 'test@example.com';
            $name = 'Test User';

            $this->mailerMock->expects(self::once())
                ->method('addAddress')
                ->with($toEmail);
            $this->mailerMock->expects(self::once())
                ->method('send')
                ->willReturn(true);
            $this->loggerMock->expects(self::once())
                ->method('info')
                ->with("Correo de bienvenida enviado a {$toEmail}");

            $result = $this->welcomeEmailService->sendWelcomeEmail($toEmail, $name);

            $this->assertTrue($result);
        }

        public function testSendWelcomeEmailFailsAndLogsErrorSimplified(): void
        {
            $toEmail = 'error@example.com';
            $name = 'Error User';
            $errorInfo = 'SMTP error: Simulated error.';

            $this->mailerMock->expects(self::once())
                ->method('addAddress')
                ->with($toEmail);
            $this->mailerMock->expects(self::once())
                ->method('send')
                ->willThrowException(new Exception($errorInfo));

            $this->mailerMock->ErrorInfo = $errorInfo;

            $this->loggerMock->expects(self::once())
                ->method('error')
                ->with("Error al enviar correo a {$toEmail}: " . $errorInfo);

            $result = $this->welcomeEmailService->sendWelcomeEmail($toEmail, $name);

            $this->assertFalse($result);
        }

        public function testGenerateWelcomeEmailCreatesCorrectEmailBody(): void
        {
            $name = 'Test Name for Email Body';
            $expectedBody = "
            <h1>¡Bienvenido, {$name}! 🎉</h1>
            <p>Gracias por unirte a nuestra plataforma. Esperamos que disfrutes de tu experiencia.</p>
            <p>Si tienes alguna duda, no dudes en contactarnos.</p>
            <br>
            <p>Saludos,</p>
            <p><strong>El equipo de Domain Driven Design LLC.</strong></p>
        ";

            $reflection = new ReflectionMethod(WelcomeEmailService::class, 'generateWelcomeEmail');
            $reflection->setAccessible(true);
            $actualBody = $reflection->invoke($this->welcomeEmailService, $name);

            $this->assertSame($expectedBody, $actualBody);
        }

        public function testSetupMailerConfiguresPHPMailerFromEnvVariables(): void
        {
            $_ENV['SMTP_HOST'] = 'test.smtp.host';
            $_ENV['SMTP_USER'] = 'testuser';
            $_ENV['SMTP_PASS'] = 'testpass';

            $service = new WelcomeEmailService($this->loggerMock);

            $reflection = new ReflectionProperty(WelcomeEmailService::class, 'mailer');
            $reflection->setAccessible(true);
            $mailerFromService = $reflection->getValue($service);

            $this->assertSame('smtp', $mailerFromService->Mailer);
            $this->assertSame('test.smtp.host', $mailerFromService->Host);
            $this->assertTrue($mailerFromService->SMTPAuth);
            $this->assertSame('testuser', $mailerFromService->Username);
            $this->assertSame('testpass', $mailerFromService->Password);
            $this->assertSame(PHPMailer::ENCRYPTION_STARTTLS, $mailerFromService->SMTPSecure);
            $this->assertSame(587, $mailerFromService->Port);
            $this->assertSame('ddd@gmail.com', $mailerFromService->From);
            $this->assertSame('Domain Driven Design LLC', $mailerFromService->FromName);
        }
    }
}