<?php

namespace Drupal\module_hero\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
* This is custom display hero
*/

class HeroController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */

  public function content() {

    $herolist = [
      ['name' => 'Atticus Finch'],
      ['name' => 'Indiana Jones'],
      ['name' => 'James Bond'],
      ['name' => 'Rick Blaine'],
      ['name' => 'Will Kane'],
      ['name' => 'Rocky Balboa'],
      ['name' => 'Ellen Ripley'],
    ];
/*
    $ListWrapper = '';
    foreach ($herolist as $hero) {

      $ListWrapper .= '<li>' . $hero['name'] . '</li>';
    }
*/
    return [
      '#theme' => 'hero_list',
      '#title' => $this->t('This is our hero lists And Heroin lists And'),
      '#items' => $herolist,
    ];
    //return $build;
  }
}
