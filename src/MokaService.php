<?php

namespace 4Rival\MokaLaravel;

class MokaService
{
protected string $dealerCode;
protected string $username;
protected string $password;
private $checkKey;

public function __construct()
{
$this->dealerCode = $this->config('services.moka.dealer_code');
$this->username = config('services.moka.username');
$this->password = config('services.moka.password');
$this->checkKey = $this->generateCheckKey();
}

private function generateCheckKey(): string
{
$checkString = $this->dealerCode . 'MK' . $this->username . 'PD' . $this->password;
return hash('sha256', $checkString);
}

public function getCheckKey(): string
{
return $this->checkKey;
}


}
