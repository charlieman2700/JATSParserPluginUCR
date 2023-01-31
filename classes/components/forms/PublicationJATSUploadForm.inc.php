<?php

use PKP\components\forms\FieldHTML;
use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldOptions;
use \PKP\components\forms\FieldText;

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
    /**
     * @var $submissionFile SubmissionFile
     */
    $this->action = $action;
    $this->successMessage = __('plugins.generic.jatsParser.publication.jats.fulltext.success');
    $this->locales = $locales;

    $options = [];
    $pdfOptions = [];
    $wantsToAddImageOptions = [
      ['value' => 'yes', 'label' => 'Yes'],
    ];
    $imagesFormOptions = [
      ['value' => 'logo', 'label' => 'Logo'],
      ['value' => 'journalThumbnail', 'label' => 'Journal Thumbnail'],
      ['value' => 'none', 'label' => 'None'],
    ];



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

          ->addField(new FieldOptions('jatsParser::fullTextFileId', [
            'label' => __('plugins.generic.jatsParser.publication.jats.label'),
            'description' => $msg,
            'isMultilingual' => true,
            'type' => 'radio',
            'options' => $options,
            'value' => $values,
            'groupId' => 'standardOptions'
          ]))
          ->addField(new FieldOptions('jatsParser::pdfGalley', [
            'label' => __('plugins.generic.jatsParser.publication.jats.pdf.label'),
            'type' => 'checkbox',
            'isMultilingual' => true,
            'options' => $pdfOptions,
            'groupId' => 'standardOptions'
          ]))

          ->addField(new FieldOptions('imageOnFirstPage', [
            'label' => 'Select journal image on first page',
            'type' => 'radio',
            'options' => $imagesFormOptions,
            'isRequired' => true,
            'groupId' => 'standardOptions'
          ]))

          ->addField(new FieldOptions('isChangingImageOptions', [
            'label' => 'Do you want to add advanced image options?',
            'type' => 'checkbox',
            'options' => $wantsToAddImageOptions,
            'groupId' => 'standardOptions',
          ]));

        $this->addGroup([
          'id' => 'advancedImageOptions',
          'label' => 'Advanced Image Options',
          'showWhen' => 'isChangingImageOptions'
        ])
          ->addField(new FieldText('customWidth', [
            'label' => ('Width in mm (default is 40mm)'),
            'inputType' => 'number',
            'groupId' => 'advancedImageOptions'
          ]));
      }
    } else {
      $this->addField(new FieldHTML("addProductionReadyFiles", array(
        'description' => $msg
      )));
    }
  }
}
