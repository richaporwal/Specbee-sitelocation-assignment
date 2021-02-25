<?php

namespace Drupal\custom_location\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;


/**
 * Class CurrentLocationTimeService.
 */
class CurrentLocationTimeService implements CurrentLocationTimeServiceInterface {

  /**
   * Config Factory service object.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Current time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $currentTime;

  /**
   * Date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a CurrentTime object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory.
   * @param \Drupal\Component\Datetime\TimeInterface $current_time
   *   Current time service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   Date formatter service.
   */
  public function __construct(ConfigFactory $configFactory,
                              TimeInterface $current_time,
                              DateFormatterInterface $date_formatter) {
    $this->configFactory = $configFactory;
    $this->currentTime = $current_time;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('datetime.time'),
      $container->get('date.formatter')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getRegionTime() {
    $timezone = $this->configFactory->get('custom_location.site_location_settings')->get('timezone');
    $location_time = $this->dateFormatter->format($this->currentTime->getRequestTime(), 'custom', 'jS M Y - g:i A', $timezone);
    return $location_time;
  }
}
