<?php

namespace App\Command;

use App\Client\FacebookClient;
use App\Client\KirbyClient;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\NewsModel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCronJob('hourly')]
#[AsCommand(name: 'ffwenns:import:posts', description: 'Import posts')]
class ImportPostsCommand extends Command
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly KirbyClient     $kirby,
        private readonly FacebookClient  $facebook)
    {
        $this->framework->initialize();

        parent::__construct();
    }

    public function __invoke(): void
    {
        $this->importPostsFromFacebook();
    }

    protected function configure(): void
    {
        $this->addOption(
            'source',
            null,
            InputOption::VALUE_REQUIRED,
            'source to import posts from',
            'facebook');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        match ($input->getOption('source')) {
            'kirby' => $this->importPostsFromKirby(),
            default => $this->importPostsFromFacebook()
        };

        return Command::SUCCESS;
    }

    protected function importPostsFromFacebook(): void
    {
        $posts = $this->facebook->posts();

        foreach ($posts as $index => $post) {
            NewsModel::findByAlias($post['sid'])?->delete();

            printf("Create post #%d: %d\n", $index + 1, $post['sid']);

            $newPost = new NewsModel();
            $newPost->alias = $post['sid'];
            $newPost->pid = match ($post['category'] ?? '') {
                'Einsätze' => 2,
                'Übungen' => 3,
                'Tätigkeiten' => 4,
                default => 1
            };
            $newPost->author = 1;
            $newPost->headline = $post['title'];
            $newPost->date = $post['date'];
            $newPost->time = $post['date'];
            $newPost->teaser = $post['text'];

            if (strlen($post['sid']) > 4) {
                $newPost->canonicalLink = "https://facebook.com/ffwenns/posts/" . $post['sid'];
            }

            if ($post['images']) {
                $newPost->addImage = true;
                $newPost->singleSRC = $post['images'][0];
                $newPost->addEnclosure = true;
                $newPost->enclosure = $post['images'];
            }

            $newPost->published = 1;
            $newPost->tstamp = time();
            $newPost->save();
        }
    }

    protected function importPostsFromKirby(): void
    {
        $posts = $this->kirby->posts();

        foreach ($posts as $index => $post) {
            if (NewsModel::findByAlias($post['sid'])) {
                continue;
            }

            printf("Create post #%d: %s\n", $index + 1, $post['sid']);

            $newPost = new NewsModel();
            $newPost->alias = $post['sid'];
            $newPost->pid = match ($post['category'] ?? '') {
                'Einsätze' => 2,
                'Übungen' => 3,
                'Tätigkeiten' => 4,
                default => 1
            };
            $newPost->author = 1;
            $newPost->headline = $post['title'];
            $newPost->date = strtotime($post['date']);
            $newPost->time = strtotime($post['date']);
            $newPost->teaser = $post['text'];

            if (strlen($post['sid']) > 4) {
                $newPost->canonicalLink = "https://facebook.com/ffwenns/posts/" . $post['sid'];
            }

            if ($post['images']) {
                $newPost->addImage = true;
                $newPost->singleSRC = $post['images'][0];
                $newPost->addEnclosure = true;
                $newPost->enclosure = $post['images'];
            }

            $newPost->published = 1;
            $newPost->tstamp = time();
            $newPost->save();
        }

    }

}