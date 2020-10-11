<?php

namespace Drupal\siteinfo\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure site information settings for this site.
 */
class ExtendedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration.
    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending.
    $form = parent::buildForm($form, $form_state);
    // Add a textarea to the site information section of the form for sitekey.
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
         // The default value is the new value we added to our configuration.
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => t('An API Key to access site pages in JSON format'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Now we need to save the new siteapikey to the
    // system.site.siteapikey configuration.
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('siteapikey'))
      // Make sure to save the configuration.
      ->save();

    // Pass the remaining values off to the original form that we have extended,
    // so that they are also saved.
    parent::submitForm($form, $form_state);
  }

}
