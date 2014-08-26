<?php

namespace Line;

use Line\LineCurlClient;
use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TCompactProtocol;
use Line\TalkServiceClient;
use Line\Types;
use Thrift\Exception\TException;

class LineApiException extends \Exception {
	
}

class LineApi {

	protected $socket;
	protected $trasport;
	protected $protocol;
	protected $client;
	protected $auth_query_path = '/api/v4/TalkService.do';
	protected $http_query_path = '/S4';
	protected $wait_for_mobile_path = '/Q';
	protected $host = 'gd2.line.naver.jp';
	protected $port = 443;

	public function __construct() {
		$this->socket = new LineCurlClient($this->host, $this->port, $this->auth_query_path, 'https');
//		$this->socket = new LineCurlClient($host, $port, $http_query_path, 'http');
		$this->transport = new TBufferedTransport($this->socket, 1024, 1024);
		$this->protocol = new TCompactProtocol($this->transport);
		$this->client = new TalkServiceClient($this->protocol);
		$this->transport->open();
		$this->transport->close();
	}

	public function open() {
		$this->trasport->open();
	}

	public function close() {
		$this->trasport->close();
	}

	protected function loginWithIdentityCredentialForCertificate($email, $password) {

		$identityProvider = IdentityProvider::LINE;
		$identifier = $email;
		$password = $password;
		$keepLoggedIn = true;
		$accessLocation = "127.0.0.1";
		$systemName = 'systemName';
		$certificate = '';

		return $this->client->loginWithIdentityCredentialForCertificate(
				$identityProvider
				, $identifier
				, $password
				, $keepLoggedIn
				, $accessLocation
				, $systemName
				, $certificate
		);
	}

	protected function getAuthToken() {
		$secret = json_decode(file_get_contents(__DIR__ . '/../../secret.json'), true);
		if (isset($secret['authToken']) && $secret['authToken'] != '') {
			return $secret['authToken'];
		} else {
			return $this->loginWithIdentityCredentialForCertificate($secret['email'], $secret['password']);
		}
	}

	public function login($email, $password) {

		return $this->getAuthToken();
		//after pincode currect
		//loginWithVerifierForCertificate($verifier);
	}

}
