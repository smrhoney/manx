<?php
	require_once 'IManxDatabase.php';

	class FakeManxDatabase implements IManxDatabase
	{
		public function __construct()
		{
			$this->getSiteListCalled = false;
			$this->getCompanyListCalled = false;
			$this->getDocumentCountCalled = false;
			$this->getOnlineDocumentCountCalled = false;
			$this->getSiteCountCalled = false;
			$this->getDisplayLanguageCalled = false;
			$this->getDisplayLanguageLastLanguage = array();
			$this->getDisplayLanguageFakeResult = array();
			$this->getOSTagsForPubCalled = false;
			$this->getOSTagsForPubFakeResult = array();
			$this->getAmendmentsForPubCalled = false;
			$this->getAmendmentsForPubFakeResult = array();
			$this->getLongDescriptionForPubCalled = false;
			$this->getLongDescriptionForPubFakeResult = array();
			$this->getCitationsForPubCalled = false;
			$this->getCitationsForPubFakeResult = array();
			$this->getTableOfContentsForPubCalled = false;
			$this->getTableOfContentsForPubFakeResult = array();
			$this->getMirrorsForCopyCalled = false;
			$this->getMirrorsForCopyFakeResult = array();
		}

		public $getDocumentCountCalled, $getDocumentCountFakeResult;
		public function getDocumentCount()
		{
			$this->getDocumentCountCalled = true;
			return $this->getDocumentCountFakeResult;
		}

		public $getOnlineDocumentCountCalled, $getOnlineDocumentCountFakeResult;
		public function getOnlineDocumentCount()
		{
			$this->getOnlineDocumentCountCalled = true;
			return $this->getOnlineDocumentCountFakeResult;
		}

		public $getSiteCountCalled, $getSiteCountFakeResult;
		public function getSiteCount()
		{
			$this->getSiteCountCalled = true;
			return $this->getSiteCountFakeResult;
		}

		public $getSiteListCalled, $getSiteListFakeResult;
		public function getSiteList()
		{
			$this->getSiteListCalled = true;
			return $this->getSiteListFakeResult;
		}

		public $getCompanyListCalled, $getCompanyListFakeResult;
		public function getCompanyList()
		{
			$this->getCompanyListCalled = true;
			return $this->getCompanyListFakeResult;
		}

		public $getDisplayLanguageCalled, $getDisplayLanguageLastLanguageCode, $getDisplayLanguageFakeResult;
		public function getDisplayLanguage($languageCode)
		{
			$this->getDisplayLanguageCalled = true;
			$this->getDisplayLanguageLastLanguageCode[$languageCode] = true;
			return $this->getDisplayLanguageFakeResult[$languageCode];
		}
		
		public $getOSTagsForPubCalled, $getOSTagsForPubLastPubId, $getOSTagsForPubFakeResult;
		public function getOSTagsForPub($pubId)
		{
			$this->getOSTagsForPubCalled = true;
			$this->getOSTagsForPubLastPubId = $pubId;
			return $this->getOSTagsForPubFakeResult;
		}
		
		public $getAmendmentsForPubCalled, $getAmendmentsForPubLastPubId, $getAmendmentsForPubFakeResult;
		public function getAmendmentsForPub($pubId)
		{
			$this->getAmendmentsForPubCalled = true;
			$this->getAmendmentsForPubLastPubId = $pubId;
			return $this->getAmendmentsForPubFakeResult;
		}
		
		public $getLongDescriptionForPubCalled, $getLongDescriptionForPubLastPubId, $getLongDescriptionForPubFakeResult;
		public function getLongDescriptionForPub($pubId)
		{
			$this->getLongDescriptionForPubCalled = true;
			$this->getLongDescriptionForPubLastPubId = $pubId;
			return $this->getLongDescriptionForPubFakeResult;
		}
		
		public $getCitationsForPubCalled, $getCitationsForPubLastPubId, $getCitationsForPubFakeResult;
		public function getCitationsForPub($pubId)
		{
			$this->getCitationsForPubCalled = true;
			$this->getCitationsForPubLastPubId = $pubId;
			return $this->getCitationsForPubFakeResult;
		}
		
		public $getTableOfContentsForPubCalled, $getTableOfContentsForPubLastPubId,
			$getTableOfContentsForPubLastFullContents, $getTableOfContentsForPubFakeResult;
		public function getTableOfContentsForPub($pubId, $fullContents)
		{
			$this->getTableOfContentsForPubCalled = true;
			$this->getTableOfContentsForPubLastPubId = $pubId;
			$this->getTableOfContentsForPubLastFullContents = $fullContents;
			return $this->getTableOfContentsForPubFakeResult;
		}
		
		public $getMirrorsForCopyCalled, $getMirrorsForCopyLastCopyId, $getMirrorsForCopyFakeResult;
		public function getMirrorsForCopy($copyId)
		{
			$this->getMirrorsForCopyCalled = true;
			$this->getMirrorsForCopyLastCopyId = $copyId;
			return $this->getMirrorsForCopyFakeResult[$copyId];
		}
	}
?>
