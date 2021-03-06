<?php

/**
 * @file
 * Contains \DennisDigital\Drupal\Console\Command\Site\Settings\DbCommand.
 *
 * Create Db configurations.
 */

namespace DennisDigital\Drupal\Console\Command\Site\Settings;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DennisDigital\Drupal\Console\Command\Exception\CommandException;
use DennisDigital\Drupal\Console\Command\Site\Shared\InstallArgumentsTrait;
use DennisDigital\Drupal\Console\Command\Site\AbstractCommand;

/**
 * Class DbCommand
 *
 * @package DennisDigital\Drupal\Console\Command
 */
class DbCommand extends AbstractCommand {
  use InstallArgumentsTrait;

  /**
   * The file name to generate.
   *
   * @var
   */
  protected $filename = 'settings.db.php';

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();

    $this->setName('site:settings:db')
      // @todo use: ->setDescription($this->trans('commands.site.settings.db.description'))
      ->setDescription('Generates settings.db.php for a given site.');

    // Re-use SiteInstall options and arguments.
    $this->getSiteInstallArguments();
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    parent::execute($input, $output);

    // Validation.
    if (!$this->fileExists($this->getSiteRoot() . 'settings.php')) {
      $message = sprintf('Could not find %s',
        $this->getSiteRoot() . 'settings.php'
      );
      throw new CommandException($message);
    }

    // Override default values for these options (if empty).
    $override = array(
      'db-name' => $this->config['db']['name'],
      'db-user' => $this->config['db']['user'],
      'db-host' => $this->config['db']['host'],
      'db-port' => $this->config['db']['port'],
      'db-type' => $this->config['db']['type'],
    );

    foreach ($this->getDefinition()->getOptions() as $option) {
      $name = $option->getName();
      if (array_key_exists($name, $override) && is_null($input->getOption($name))) {
        $input->setOption($name, $override[$name]);
      }
    }

    // Remove existing file.
    $file = $this->getSiteRoot() . $this->filename;
    if ($this->fileExists($file)) {
      $this->fileUnlink($file);
    }

    // Prepare content.
    $db_name = $input->getOption('db-name');
    $db_user = $input->getOption('db-user');
    $db_pass = $input->getOption('db-pass');
    $db_host = $input->getOption('db-host');
    $db_port = $input->getOption('db-port');
    $db_type = $input->getOption('db-type');
    $db_prefix = $input->getOption('db-prefix');
    $namespace = 'Drupal\\Core\\Database\\Driver\\' . $db_type;

    $content = <<<EOF
<?php
/**
 * DB Settings.
 */
\$databases['default']['default'] = array(
  'database' => '$db_name',
  'username' => '$db_user',
  'password' => '$db_pass',
  'host' => '$db_host',
  'port' => $db_port,
  'driver' => '$db_type',
  'prefix' => '$db_prefix',
  'namespace' => '$namespace',
);
EOF;

    $this->filePutContents($file, $content);

    // Check file.
    if ($this->fileExists($file)) {
      $this->io->success(sprintf('Generated %s',
          $file)
      );
    }
    else {
      throw new CommandException(sprintf('Error generating %s',
          $file
        )
      );
    }
  }
}
