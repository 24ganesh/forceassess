<?php

/**
 * @file
 * Contains \Drupal\module_hero\Form\AddHeroForm.
 */

namespace Drupal\module_hero\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\date_popup\DatePopup;
use Drupal\date_popup\DatetimePopup;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 *
 */
class AddHeroForm extends FormBase {
  /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'add_hero_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      //$configs = $this->config('general.credit_persons_form');
      $num_person = $form_state->get('num_person');

      if ($num_person === null)
      {
        $persons = $form_state->set('num_person', 1);
        $num_person = 1;
      }

      $form['#tree'] = true;
      $form['site_credits'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Site managers'),
        '#prefix' => '<div id="siteCreditsWrapper">',
        '#suffix' => '</div>',
      );



      for($i = 0; $i < $num_person; $i++) {
        $num = $i + 1;

        $form['site_credits']["persons-{$num}"] = array(
          '#type' => 'fieldset',
          '#title' => $this->t("Person #{$num}"),
          '#prefix' => "<div id=\"person-{$num}\">",
          '#suffix' => '</div>',
        );

        $form['site_credits']["persons-{$num}"]['manager_picture'] = array(
          '#type' => 'managed_file',
          '#title' => $this->t('Manager picture'),
          '#description'=> $this->t("Upload file. <br />2MB limit. <br/>Allowed types: png gif jpg jpeg."),
        );

        $form['site_credits']["persons-{$num}"]['first_name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('First name'),
        );

        $form['site_credits']["persons-{$num}"]['last_name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Last name'),
        );
        $form['site_credits']["persons-{$num}"]['date_time'] = array(
          '#type' => 'date',
          '#date_format' => 'd-m-Y',
          '#title' => t('Start date'),
          '#date_label_position' => 'none',
        );
        $form['site_credits']["persons-{$num}"]['position'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Position'),
        );

        $form['site_credits']["persons-{$num}"]['profile_url'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Link to Linkedin profile'),
        );

        $form['site_credits']["persons-{$num}"]['actions'] = array(
          '#type' => 'actions',
        );

        if ($num_person != 1)
        {
          $form['site_credits']["persons-{$num}"]['actions']['remove_person'] = array(
            '#type' => 'submit',
            '#value' => $this->t("Remove person"),
            '#name' => "persons-" . $num,
            '#submit' => ['::removeCallback'],
            '#ajax' => array(
              'callback' => '::addmoreCallback',
              'wrapper' => 'siteCreditsWrapper',
            ),
          );
        }
      }
      $form['site_credits']['actions'] = array(
        '#type' => 'actions',
      );

      $form['site_credits']['actions']['add_person' . ($num - 1)] = array(
        '#type' => 'submit',
        '#value' => $this->t('Add one more'),
        '#submit' => ['::addOne'],
        '#ajax' => array(
          'callback' => '::addmoreCallback',
          'wrapper' => 'siteCreditsWrapper',
        ),
        '#button_type' => 'default'
      );

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save'),
        '#button_type' => 'primary',
      );
      dpm($form);
    return $form;

    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      drupal_set_message('Hi Ganesh Lad');
    }


  /**
   *
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['site_credits'];
  }

  /**
   * {@inheritdoc}
   */
  public function addOne(array &$form, FormStateInterface $form_state)
  {
    $persons = $form_state->get('num_person');
    $add_person = $persons + 1;
    $form_state->set('num_person', $add_person);

    $form_state->setRebuild();
  }


  /**
   * {@inheritdoc}
   */
  public function removeCallback(array &$form, FormStateInterface $form_state)
  {
    $triggering_element = &$form_state->getTriggeringElement()['#name'];
    $triggering_element_explode = explode("-", $triggering_element);
    $del_person_num = end($triggering_element_explode);
    $inputs = &$form_state->getUserInput()['site_credits'];
    foreach ($inputs as $input_person_key => $input_person_value) {
      $persons_explode = explode('-', $input_person_key);
      $end_person_input = end($persons_explode);
      if (is_numeric($end_person_input) && $end_person_input > $del_person_num) {
         $last_person_key = $end_person_input - 1;
         $inputs[$persons_explode[0] . '-' . $last_person_key] = ['position' => $input_person_value['position']];
      }
    }

    $persons = $form_state->get('num_person');
    if ( $persons > 1 )
    {
      $remove_person = $persons - 1;
      $form_state->set('num_person', $remove_person);
    }

    $form_state->setRebuild();
  }
  }
  ?>
