<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('File', 'Utility');
App::uses('Xml', 'Utility');

class S3Behavior extends ModelBehavior {
	public $s3Url = 's3.amazonaws.com';
	public $accessKey;
	public $secretKey;

	public $region = null;

	public $contentType;
	public $contentLength = 0;
	public $checkSum;
	public $body = null;
	public $signature;

	public $localFilePath;

	public function getCheckSum() {
		$file = new File($this->localFilePath);
		$this->checkSum = base64_encode($file->md5());
		if (!empty($this->checkSum)) {
			return true;
		} else {
			//error
			return false;
		}
	}

	public function getContentType() {
		$file = new File($this->localFilePath);
		$info = $file->info();
		if (!empty($info['mime'])) {
			$this->contentType = $info['mime'];
			return true;
		} else {
			// error
			return false;
		}
	}

	public function createBucket($name) {
		if (!empty($this->region)) {
			$bodyArray = array(
				'CreateBucketConfiguration' => array(
					'xmlns:' => 'http://s3.amazonaws.com/doc/2006-03-01/',
					'LocationConstraint' => $this->region,
				),
			);
			$xml = Xml::fromArray($test, array('encoding' => null));
			$body = preg_replace("/<\?.+\?>\n/", '', $xml->asXML());
			$this->contentLength = strlen($body);
		}
		$request = array(
			'method' => 'PUT',

		);
	}

}
