<?php

namespace App\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class MakeTrait.
 *
 * Represents the command to create a new trait.
 */
final class MakeTraitCommand extends AbstractMaker
{
    private string $service;
    private string $property;

    public static function getCommandName(): string
    {
        return 'make:trait';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new trait';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('name', InputArgument::OPTIONAL, sprintf('The name of the trait (e.g <fg=yellow>%s</>', Str::asClassName(Str::getRandomTerm())))
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeTrait.txt'))
        ;

        $inputConfig->setArgumentAsNonInteractive('name');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (null === $input->getArgument('name')) {
            $argument = $command->getDefinition()->getArgument('name');

            $value = $io->askQuestion(new Question($argument->getDescription()));
            $input->setArgument('name', $value);
        }

        $defaultTraitName = $input->getArgument('name');

        $this->service = $io->ask(
            sprintf('Choose a service/class/interface to inject into your trait, please provide the namespace (e.g. <fg=yellow>%s</>)', $defaultTraitName),
            $defaultTraitName,
        );

        $this->property = $io->ask(
            sprintf('Choose a property name (e.g. <fg=yellow>%s</>)', Str::asLowerCamelCase($defaultTraitName)),
            Str::asLowerCamelCase($defaultTraitName),
        );

        Validator::validatePropertyName($this->property);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): int
    {
        $traitName = trim($input->getArgument('name'));

        $traitNameDetails = $generator->createClassNameDetails(
            $traitName,
            namespacePrefix: 'DependencyInjection\\',
            suffix: 'AwareTrait',
            validationErrorMessage: sprintf('The "%s" trait is not valid.', $traitName)
        );

        $trait = $generator->generateClass(
            $traitNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/trait/Trait.tpl.php',
            [
                'trait_name' => $traitNameDetails->getShortName(),
                'use_statements' => $this->service,
                'dependency' => Str::getShortClassName($this->service),
                'property_name' => $this->property,
            ]
        );

        if (trait_exists($trait)) {
            $io->error(sprintf('The trait "%s" already exists !', $traitName));

            return Command::INVALID;
        }

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        return Command::SUCCESS;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(
            AbstractMaker::class,
            'symfony/maker-bundle',
        );
    }
}
