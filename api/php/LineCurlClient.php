<?php

namespace Line;

use Thrift\Exception\TTransportException;

class LineCurlClient extends \Thrift\Transport\TCurlClient {

	private $curlDebug = true;
	private static $curlHandle;
	private $userAgent = '';
	private $headers = [
		'Accept: application/x-thrift',
		'Content-Type: application/x-thrift',
		//line needles
		"X-Line-Application: DESKTOPWIN\t3.2.1.83\tWINDOWS\t5.1.2600-XP-x64"
	];

	public function addHeader($header) {
		array_push($this->headers, $header);
	}

	/**
	 * @overwrite
	 */
	public function flush() {
		if (!self::$curlHandle) {
			register_shutdown_function(array('Thrift\\Transport\\TCurlClient', 'closeCurlHandle'));
			self::$curlHandle = curl_init();
			curl_setopt(self::$curlHandle, CURLOPT_VERBOSE, $this->curlDebug);
			curl_setopt(self::$curlHandle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt(self::$curlHandle, CURLOPT_BINARYTRANSFER, true);
			curl_setopt(self::$curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt(self::$curlHandle, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt(self::$curlHandle, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt(self::$curlHandle, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt(self::$curlHandle, CURLOPT_MAXREDIRS, 1);
		}
		// God, PHP really has some esoteric ways of doing simple things.
		$host = $this->host_ . ($this->port_ != 80 ? ':' . $this->port_ : '');
		$fullUrl = $this->scheme_ . "://" . $host . $this->uri_;

		$this->addHeader('Content-Length: ' . \Thrift\Factory\TStringFuncFactory::create()->strlen($this->request_));

		curl_setopt(self::$curlHandle, CURLOPT_HTTPHEADER, $this->headers);

		if ($this->timeout_ > 0) {
			curl_setopt(self::$curlHandle, CURLOPT_TIMEOUT, $this->timeout_);
		}
		curl_setopt(self::$curlHandle, CURLOPT_POSTFIELDS, $this->request_);
		$this->request_ = '';

		curl_setopt(self::$curlHandle, CURLOPT_URL, $fullUrl);
		$this->response_ = curl_exec(self::$curlHandle);

		echo 'return: ', $this->response_ . PHP_EOL;
		// Connect failed?
		if (!$this->response_) {
			curl_close(self::$curlHandle);
			self::$curlHandle = null;
			$error = 'TCurlClient: Could not connect to ' . $fullUrl;
			throw new TTransportException($error, TTransportException::NOT_OPEN);
		}
	}

}
