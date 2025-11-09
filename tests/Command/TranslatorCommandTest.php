<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Atico\SpreadsheetTranslator\Core\SpreadsheetTranslator;
use App\Command\TranslatorCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Test translator that includes setFallbackLocales method.
 */
interface TestTranslatorInterface extends TranslatorInterface
{
    public function setFallbackLocales(array $locales): void;
}

#[CoversClass(TranslatorCommand::class)]
final class TranslatorCommandTest extends TestCase
{
    private SpreadsheetTranslator&MockObject $spreadsheetTranslator;

    private TestTranslatorInterface&MockObject $translator;

    private TranslatorCommand $command;

    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->spreadsheetTranslator = $this->createMock(SpreadsheetTranslator::class);

        // Create a mock that supports setFallbackLocales
        $this->translator = $this->createMock(TestTranslatorInterface::class);

        $this->command = new TranslatorCommand(
            $this->spreadsheetTranslator,
            $this->translator
        );

        $application = new Application();
        $application->add($this->command);

        $command = $application->find('atico:demo:translator');
        $this->commandTester = new CommandTester($command);
    }

    #[Test]
    public function commandHasCorrectName(): void
    {
        self::assertSame('atico:demo:translator', $this->command->getName());
    }

    #[Test]
    public function commandHasCorrectDescription(): void
    {
        self::assertSame(
            'Translate From an Excel File to Symfony Translation format',
            $this->command->getDescription()
        );
    }

    #[Test]
    public function commandExecutesSuccessfullyWithSheetNameOption(): void
    {
        $this->spreadsheetTranslator
            ->expects(self::once())
            ->method('processSheet')
            ->with('common', '');

        $this->translator
            ->method('trans')
            ->willReturn('Translated Title');

        $this->commandTester->execute([
            '--sheet-name' => 'common',
        ]);

        self::assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        self::assertStringContainsString('Translation text for', $this->commandTester->getDisplay());
        self::assertStringContainsString('Translated Title', $this->commandTester->getDisplay());
    }

    #[Test]
    public function commandExecutesSuccessfullyWithSheetNameAndBookNameOptions(): void
    {
        $this->spreadsheetTranslator
            ->expects(self::once())
            ->method('processSheet')
            ->with('common', 'frontend');

        $this->translator
            ->method('trans')
            ->willReturn('Translated Title');

        $this->commandTester->execute([
            '--sheet-name' => 'common',
            '--book-name' => 'frontend',
        ]);

        self::assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        self::assertStringContainsString('Translation text for', $this->commandTester->getDisplay());
    }

    #[Test]
    public function commandExecutesSuccessfullyWithoutOptions(): void
    {
        $this->spreadsheetTranslator
            ->expects(self::once())
            ->method('processSheet')
            ->with('', '');

        $this->translator
            ->method('trans')
            ->willReturn('Translated Title');

        $this->commandTester->execute([]);

        self::assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function commandDisplaysTranslatedFragment(): void
    {
        $this->spreadsheetTranslator
            ->expects(self::once())
            ->method('processSheet');

        $this->translator
            ->method('trans')
            ->willReturn('Spreadsheet translator');

        $this->commandTester->execute(['--sheet-name' => 'common']);

        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Translation text for "homepage_title"', $output);
        self::assertStringContainsString('in "es_ES"', $output);
        self::assertStringContainsString('Spreadsheet translator', $output);
    }
}
