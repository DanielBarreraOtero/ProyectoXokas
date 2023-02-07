<?php

namespace App\Command;

use App\Entity\Mesa;
use App\Repository\MesaRepository;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:mesa:nueva',
    description: 'Crea una nueva mesa y la añade a la base de datos',
)]
class MesaNuevaCommand extends Command
{
    private MesaRepository $mesaRepo;

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    public function __construct(MesaRepository $mesaRepo)
    {
        parent::__construct();

        $this->mesaRepo = $mesaRepo;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Nueva Mesa:');

        $ancho = $io->ask('Ancho (cm)');
        $alto = $io->ask('Alto (cm)');
        $sillas = $io->ask('Número de sillas');

        $mesa = new Mesa();

        $mesa->setAncho($ancho);
        $mesa->setAlto($alto);
        $mesa->setSillas($sillas);
        $mesa->setPosX(-1);
        $mesa->setPosY(-1);

        try {
            $this->mesaRepo->save($mesa, true);
        } catch (Exception $e) {
            throw $e;
        }

        $io->success('Mesa creada con exito');

        return Command::SUCCESS;
    }
}
