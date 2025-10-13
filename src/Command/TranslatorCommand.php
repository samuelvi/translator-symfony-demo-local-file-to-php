<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use Atico\SpreadsheetTranslator\Core\SpreadsheetTranslator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;

/**
 * Reference command:
 * bin/console samuelvi:demo:translator --sheet-name=common --book-name=frontend --env=dev
 */
#[AsCommand(
    name: 'samuelvi:demo:translator',
    description: 'Translate From an Excel File to Symfony Translation format',
)]
class TranslatorCommand extends Command
{
    public function __construct(
        private readonly SpreadsheetTranslator $processor,
        private readonly Translator $translator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('sheet-name', null, InputOption::VALUE_OPTIONAL, 'Single Sheet To Translate')
            ->addOption('book-name', null, InputOption::VALUE_OPTIONAL, 'Book name To Translate (Domain)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = $this->buildParamsFromInput($input);
        $this->doExecute($output, $params);
        return Command::SUCCESS;
    }

    protected function buildParamsFromInput(InputInterface $input): array
    {
        $sheetName = ($input->hasOption('sheet-name')) ? $input->getOption('sheet-name') : '';
        $bookName = ($input->hasOption('book-name')) ? $input->getOption('book-name') : '';
        return ['sheet_name' => $sheetName, 'book_name' => $bookName];
    }

    private function doExecute(OutputInterface $output, array $params): void
    {
        $this->processor->processSheet($params['sheet_name'], $params['book_name']);

        $this->showTranslatedFragment($output);
    }

    private function showTranslatedFragment(OutputInterface $output): void
    {
        $locale = 'es_ES';
        $sectionSubsection = 'homepage.title';
        $translationDomain = 'demo_frontend';

        $this->translator->setFallbackLocales(['en', $locale]);
        $output->writeln(
            sprintf(
                'Translation text for "%s" in "%s": "%s"',
                $sectionSubsection,
                $locale,
                $this->translator->trans($sectionSubsection, [], $translationDomain))
        );
    }
}
