<?php

namespace Drupal\custom_location\Services;

/**
 * Implement Custom location service interface.
 */
interface CurrentLocationTimeServiceInterface {

  /**
   * Get region time.
   */
  public function getRegionTime();

}
