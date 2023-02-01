<?php
// This class is used to store the information of an article
// The information is stored in the following variables
// _title: title of the article 
// _doi: doi of the article
// _volume: volume of the article
// _issue: issue of the article
// _fpage: first page of the article in the print journal
// _lpage: last page of the article in the print journal
// _enTitle: english title of the article
// _category: category of the article
// _journalId: journal id of the article
// _issn: issn of the article
// _publisher: publisher of the article
// _abbreviatedTitle: abbreviated title of the article
// _license: license of the article
// _keywords: keywords of the article (array of KeywordGroup objects) 

use JATSParser\Body\KeywordGroup;
class ArticleInfo
{
  private $_title = "";
  private $_doi = "";
  private $_volume = "";
  private $_issue = "";
  private $_fpage = "";
  private $_lpage = "";
  private $_enTitle = "";
  private $_category = "";
  private $_journalId = "";
  private $_issn = "";
  private $_publisher = "";
  private $_abbreviatedTitle = "";
  private $_keywords = array();

  public function __construct(DOMXPath $xpath, $context)
  {
    $this->_extractInfoFromContext($context);
    $this->_extractInfoFromXML($xpath);
  }

  private function _extractInfoFromContext($context)
  {
    $this->_journalId = $context->getLocalizedSetting('acronym');
    $this->_issn = $context->getSetting('printIssn');
    $this->_publisher = $context->getSetting('publisherInstitution');
    $this->_abbreviatedTitle = $context->getLocalizedSetting('abbreviation');
  }

  private function _extractInfoFromXML(DOMXPath $xpath)
  {
    foreach ($xpath->evaluate("/article/front/article-meta/kwd-group") as $kwdGroupNode) {
      $kwGroupFound = new KeywordGroup($kwdGroupNode, $xpath);
      $this->_keywords[] = $kwGroupFound;
    }

    foreach ($xpath->evaluate("/article/front/article-meta/title-group/article-title") as $node) {
      $this->_title = $node->nodeValue;
    }

    foreach ($xpath->evaluate("/article/front/article-meta/article-categories/subj-group/subject") as $node) {
      $this->_category = $node->nodeValue;
    }

    foreach ($xpath->evaluate("/article/front/article-meta/title-group/trans-title-group/trans-title") as $node) {
      $this->_enTitle = $node->nodeValue;
    }


    foreach ($xpath->evaluate("//article-id") as $node) {
      $this->_doi = $node->nodeValue;
    }

    foreach ($xpath->evaluate("//volume") as $key => $node) {
      if ($key == 0) {
        $this->_volume = $node->nodeValue;
      }
    }

    foreach ($xpath->evaluate("//issue") as $key => $node) {
      if ($key == 0) {
        $this->_issue = $node->nodeValue;
      }
    }

    foreach ($xpath->evaluate("//fpage") as $key => $node) {
      if ($key == 0) {
        $this->_fpage = $node->nodeValue;
      }
    }

    foreach ($xpath->evaluate("//lpage") as $key => $node) {
      if ($key == 0) {
        $this->_lpage = $node->nodeValue;
      }
    }
  }

  //Getters
  public function getTitle()
  {
    return $this->_title;
  }

  public function getDoi()
  {
    return $this->_doi;
  }

  public function getVolume()
  {
    return $this->_volume;
  }

  public function getIssue()
  {
    return $this->_issue;
  }

  public function getFpage()
  {
    return $this->_fpage;
  }

  public function getLpage()
  {
    return $this->_lpage;
  }

  public function getEnTitle()
  {
    return $this->_enTitle;
  }

  public function getCategory()
  {
    return $this->_category;
  }

  public function getJournalId()
  {
    return $this->_journalId;
  }

  public function getIssn()
  {
    return $this->_issn;
  }

  public function getPublisher()
  {
    return $this->_publisher;
  }

  public function getAbbreviatedTitle()
  {
    return $this->_abbreviatedTitle;
  }

  public function getKeywords() 
  {
    return $this->_keywords;
  }
}
