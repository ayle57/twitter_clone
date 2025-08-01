<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'regenerate-app-secret',
    description: 'Generate a new app secret for the application',
    aliases: ['app:regenerate-secret'],
)]
class RegenerateAppSecretCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $a = "01232456789abcdef";
        $secret = "";

        for($i = 0; $i < 32; $i++) {
            $secret .= $a[rand(0,15)];
        }

        $r = shell_exec('sed -i -E "s/^APP_SECRET=.{32}$/APP_SECRET=' . $secret . '/" .env');
        $io->success('App secret regenerated successfully: ' . $secret);
        return Command::SUCCESS;
    }
}
