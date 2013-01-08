<?php

App::uses('File', 'Utility');
App::import('Vendor', 'Autoload', array('file' => 'Aws' . DS . 'vendor' . DS . 'autoload.php'));

use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;

class S3Behavior extends ModelBehavior {
	public $s3Url = 's3.amazonaws.com';

	public $accessKey;
	public $secretKey;
	public $region = null;

	/*
	* public $actsAs = array(
	* 	'AmazonS3.S3' => array(
	*		'key' => '**********',
	* 		'secret' => '***********',
	*		'region' => 'ap-northeast-1'
	* 	),
	* );
	*/
	public function setup(Model $Model, $config = array()) {
		$this->accessKey = $config['key'];
		$this->secretKey = $config['secret'];
		if (isset($config['region'])) {
			$this->region = $config['region'];
		}
	}

	private	function factory() {
		$aws = Aws::factory(array(
			'key' => $this->accessKey,
			'secret' => $this->secretKey,
			'region' => $this->region,
		));
		return $aws->get('s3');
	}

	/*
		$file = array(
			'name' => 'hoge',
			'path' => 'path/to/local/file',
		);
	*/
	public function addFile(Model $Model, $bucket, $file = array(), $acl = null) {
		$s3 = $this->factory();
		$localObject = new File($file['path']);
		if (!isset($acl)) {
			$acl = CannedAcl::PUBLIC_READ;
		}
		try {
			$s3->putObject(array(
				'Bucket' => $bucket,
				'Key' => $file['name'],
				'Body' => $localObject->read(),
				'ACL' => $acl,
			));
		} catch(S3Exception $e) {
			//error
		}
	}

	/*
		$files = array(
			'hoge.jpg',
			'huga.jpg',
		);
	*/
	public function deleteFiles(Model $Model, $bucket, $files) {
		$s3 = $this->factory();
		$objects = array();
		foreach ($files as $file) {
			$objects[] = array(
				'Key' => $file,
			);
		}
		try {
			$s3->deleteObjects(array(
				'Bucket' => $bucket,
				'Objects' => $objects,
			));
		} catch(S3Exception $e) {
			//error
		}
	}

}
