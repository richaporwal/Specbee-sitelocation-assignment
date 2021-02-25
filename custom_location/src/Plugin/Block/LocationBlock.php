<?php

namespace Drupal\custom_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_location\Services\CurrentLocationTimeService;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'LocationBlock' block.
 *
 * @Block(
 *  id = "location_block",
 *  admin_label = @Translation("Location block"),
 * )
 */
class LocationBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Current location time service.
   *
   * @var \Drupal\custom_location\Services\CurrentLocationTimeService
   */
  protected $currentLocationTimeService;

  /**
   * Constructs a LocationBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\custom_location\Services\CurrentLocationTimeService $current_location_time_service
   *   Get current region time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, CurrentLocationTimeService $current_location_time_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->currentLocationTimeService = $current_location_time_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('custom_location.site_location_time'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $country = $this->configFactory->get('custom_location.site_location_settings')->get('country');
    $city = $this->configFactory->get('custom_location.site_location_settings')->get('city');
    $region = $this->configFactory->get('custom_location.site_location_settings')->get('timezone');
    $current_time = $this->currentLocationTimeService->getRegionTime();

    return [
      '#theme' => 'location_template',
      '#items' => [
        'city' => $city,
        'country' => $country,
        'region' => $region,
        'current_time' => $current_time,
      ],
      '#cache' => [
        'tags' => $this->getCacheTags(),
      ],

    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $myconfig = $this->configFactory->get('custom_location.site_location_settings');
    return Cache::mergeTags(parent::getCacheTags(), $myconfig->getCacheTags());
  }

}
