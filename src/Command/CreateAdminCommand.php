<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un nouvel utilisateur en base de données',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande permet de créer un nouvel utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Création d\'un nouvel utilisateur');

        // Demande des informations
        $email = $io->ask('Email de l\'utilisateur');
        $password = $io->askHidden('Mot de passe');
        $isAdmin = $io->confirm('Cet utilisateur est-il un admin ?', false);

        // Validation des entrées
        if (empty($email)) {
            $io->error('L\'email ne peut pas être vide');
            return Command::FAILURE;
        }

        if (strlen($password) < 6) {
            $io->error('Le mot de passe doit contenir au moins 6 caractères');
            return Command::FAILURE;
        }

        // Vérification si l'utilisateur existe déjà
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($existingUser) {
            $io->error('Un utilisateur avec cet email existe déjà');
            return Command::FAILURE;
        }

        // Création du nouvel utilisateur
        $user = new User();
        $user->setEmail($email);

        // Définition des rôles
        $roles = ['ROLE_USER'];
        if ($isAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }
        $user->setRoles($roles);

        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        // Enregistrement en base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf(
            'Utilisateur [%s] créé avec succès avec les rôles : %s',
            $email,
            implode(', ', $roles)
        ));

        return Command::SUCCESS;
    }
}