<?php

use PKP\components\forms\FieldHTML;
use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldOptions;
use \PKP\components\forms\FieldText;

use fValues\FormFields;


define("FORM_PUBLICATION_JATS_FULLTEXT", "jatsUpload");

class PublicationJATSUploadForm extends FormComponent
{
  /** @copydoc FormComponent::$id */
  public $id = FORM_PUBLICATION_JATS_FULLTEXT;

  /** @copydoc FormComponent::$method */
  public $method = 'PUT';

  /**
   * Constructor
   *
   * @param $action string URL to submit the form to
   * @param $locales array Supported locales
   * @param $publication \Publication publication to change settings for
   * @param $submissionFiles array of SubmissionFile with xml type
   * @param $msg string field description
   */
  public function __construct($action, $locales, $publication, $submissionFiles, $msg)
  {

    // Required to initialize FormFields store standardize form values accross the project
    FormFields::init();
    /**
     * @var $submissionFile SubmissionFile
     */
    $this->action = $action;
    $this->successMessage = __('plugins.generic.jatsParser.publication.jats.fulltext.success');
    $this->locales = $locales;


    $options = [];
    $pdfOptions = [];


    foreach ($locales as $value) {
      $locale = $value['key'];
      $lang = [];
      if (empty($submissionFiles)) break;
      foreach ($submissionFiles as $submissionFile) {
        $subName = $submissionFile->getData('name', $locale);
        if (empty($subName)) {
          $subName = $submissionFile->getLocalizedData('name');
        }
        $lang[] = array(
          'value' => $submissionFile->getId(),
          'label' => $subName
        );
      }

      $options[$locale] = $lang;

      $pdfOptions[$locale][] = array(
        'value' => true,
        'label' => __('common.yes')
      );
    }

    // Update the values so the proper option is selected on thr form initiation if full-text isn't selected for the specific locale
    $values = $publication->getData('jatsParser::fullTextFileId');
    $emptyValues = array_fill_keys(array_keys($options), null);
    empty($values) ? $values = $emptyValues : $values = array_merge($emptyValues, $values);

    $plugin = PluginRegistry::getPlugin('generic', 'jatsparserplugin'); /* @var $plugin JATSParserPlugin */
    $context = Application::get()->getRequest()->getContext();
    $convertToPdf = $plugin->getSetting($context->getId(), 'convertToPdf');


    if (!empty($options)) {

      if ($convertToPdf) {
        $this->addGroup([
          'id' => 'standardOptions',
          'label' => '',
        ])

          ->addField(new FieldOptions(FormFields::$fullTextFileId->id, [
            'label' => __(FormFields::$fullTextFileId->label),
            'description' => $msg,
            'isMultilingual' => true,
            'type' => 'radio',
            'options' => $options,
            'value' => $values,
            'groupId' => 'standardOptions'
          ]))
          ->addField(new FieldOptions(FormFields::$pdfGalley->id, [
            'label' => __(FormFields::$pdfGalley->label),
            'type' => 'checkbox',
            'isMultilingual' => true,
            'options' => $pdfOptions,
            'groupId' => 'standardOptions'
          ]))

          ->addField(new FieldOptions(FormFields::$imageOnFirstPage->id, [
            'label' => FormFields::$imageOnFirstPage->label,
            'type' => 'radio',
            'options' => FormFields::$imageOnFirstPage->options,
            'isRequired' => true,
            'groupId' => 'standardOptions'
          ]));


        $this->addGroup([
          'id' => 'advancedOptions',
          'label' => 'Advanced Options',
        ])

          ->addField(new FieldOptions(FormFields::$advancedImageOptions->id, [
            'label' => FormFields::$advancedImageOptions->label,
            'type' => 'checkbox',
            'options' => FormFields::$advancedImageOptions->options,
            'groupId' => 'advancedOptions'
          ]))

          ->addField(new FieldText(FormFields::$customLogoWidth->id, [
            'label' => FormFields::$customLogoWidth->label,
            'inputType' => 'number',
            'groupId' => 'advancedOptions',
            'showWhen' => FormFields::$advancedImageOptions->id,
          ]))

          ->addField(new FieldOptions(FormFields::$wantsCustomTitleStyle->id, [
            'label' => FormFields::$wantsCustomTitleStyle->label,
            'type' => 'checkbox',
            'options' => FormFields::$wantsCustomTitleStyle->options,
            'groupId' => 'advancedOptions'
          ]))

          ->addField(new FieldText(FormFields::$customTitle->id, [
            'label' => FormFields::$customTitle->label,
            'inputType' => 'text',
            'groupId' => 'advancedOptions',
            'showWhen' => FormFields::$wantsCustomTitleStyle->id,
          ]));
      }
    } else {
      $this->addField(new FieldHTML("addProductionReadyFiles", array(
        'description' => $msg
      )));
    }
  }
}
