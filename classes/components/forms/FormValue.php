<?php

namespace fValues;

class FormValue
{
  public string $label;
  public string $id;
  public $options;

  public function __construct(string $label, string $id, $options = null)
  {
    $this->label = $label;
    $this->id = $id;
    $this->options = $options;
  }
}


class FormFields
{
  public static FormValue $pdfGalley;
  public static FormValue $advancedImageOptions;
  public static FormValue $customLogoWidth;
  public static FormValue $imageOnFirstPage;
  public static FormValue $fullTextFileId;
  public static FormValue $wantsCustomTitleStyle;
  public static FormValue $customSpanishTitle;
  public static FormValue $customEnglishTitle;
  public static function init()

  {
    self::$advancedImageOptions = new FormValue(
      'Do you want to add advanced image options?',
      'isChangingImageOptions',
      [
        ['value' => 'yes', 'label' => 'Yes'],
      ]
    );

    self::$customLogoWidth = new FormValue(
      'Width in mm (default is 40mm)',
      'customWidth',
    );

    self::$imageOnFirstPage = new FormValue(
      'Select journal image on first page',
      'imageOnFirstPage',
      [
        ['value' => 'logo', 'label' => 'Logo'],
        ['value' => 'journalThumbnail', 'label' => 'Journal Thumbnail'],
        ['value' => 'none', 'label' => 'None'],
      ]
    );

    self::$pdfGalley = new FormValue(
      'plugins.generic.jatsParser.publication.jats.pdf.label',
      'jatsParser::pdfGalley',
    );

    self::$fullTextFileId = new FormValue(
      'plugins.generic.jatsParser.publication.jats.label',
      'jatsParser::fullTextFileId',
    );

    self::$wantsCustomTitleStyle = new FormValue(
      'Do you want to change the title style?',
      'wantsCustomTitleStyle',
      [
        ['value' => 'yes', 'label' => 'Yes'],
      ]
    );

    self::$customSpanishTitle = new FormValue(
      'Insert style for Spanish title with html syntax (e.g. <b>My title</b> <i>Lorem Ipsum</i>)',
      'customSpanishTitle',
    );

    self::$customEnglishTitle = new FormValue(
      'Insert style for English title with html syntax (e.g. <b>My title</b> <i>Lorem Ipsum</i>)',
      'customEnglishTitle',
    );
  }
}
