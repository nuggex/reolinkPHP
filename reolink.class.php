<?php

use GuzzleHttp\Exception\GuzzleException;

require_once "rootdir.php";
require_once ROOT_DIR . "/vendor/autoload.php";

class reoLink
{

    private array $config;
    private string $host;
    private string $url;
    private string $username;
    private string $password;
    private string $token;

    /**
     * @throws GuzzleException
     */
    function __construct()
    {
        $this->config = parse_ini_file(CONFIG_FILE);
        $this->host = $this->config['host'];
        $this->url = "https://" . $this->host . "/cgi-bin/api.cgi";
        $this->username = $this->config['username'];
        $this->password = $this->config['password'];
        $this->token = $this->geToken();
    }

    /**
     * Get session Token TTL is 3600 and if you spam frequently you will hit max-session and will be denied access.
     * @return mixed
     * @throws GuzzleException
     */
    private function geToken(): mixed
    {
        $client = new GuzzleHttp\Client();

        $payLoad = array(array(
            "cmd" => "Login",
            "action" => 0,
            "param" => array(
                "User" => array(
                    "userName" => $this->username,
                    "password" => $this->password
                )
            )));
        $json = json_encode($payLoad);
        $response = $client->request("POST", $this->url . "?cmd=Login", array('verify' => false, 'body' => $json));
        $token = json_decode($response->getBody()->getContents())[0]->value->Token->name;
        return ($token);
    }


    /**
     * Get enabled presets
     * @return array
     * @throws GuzzleException
     */
    public function getPtzPreset(): array
    {
        $client = new GuzzleHttp\Client();

        $payLoad = array(array(
            "cmd" => "GetPtzPreset",
            "action" => 1,
            "param" => array(
                "channel" => 0
            )
        ));

        $json = json_encode($payLoad);
        $response = $client->request("POST", $this->url . "?cmd=GetPtzPreset&token=" . $this->token, array('verify' => false, 'body' => $json));
        $content = json_decode($response->getBody()->getContents());
        $list = $content[0]->initial;
        $presets = array();
        foreach ($list->PtzPreset as $preset) {
            if ($preset->enable == 1) {
                $presets[] = $preset;
            }
        }
        return $presets;
    }

    /**
     *
     * @param int $id Preset id to move to
     * @param int $speed Speed to move at
     * @return int
     * @throws GuzzleException
     */
    public function PtzPresetControl(int $id, int $speed = 32): int
    {
        $client = new GuzzleHttp\Client();

        $payLoad = array(array(
            "cmd" => "PTzCtrl",
            "param" => array(
                "channel" => 0,
                "op" => "ToPos",
                "id" => $id,
                "speed" => $speed
            )
        ));

        $json = json_encode($payLoad);
        $response = $client->request("POST", $this->url . "?cmd=PtzCtrl&token=" . $this->token, array('verify' => false, 'body' => $json));
        return $response->getStatusCode();
    }

    /**
     * Generic command runner
     * @param String $command Command to run
     * @param Array $object with params
     * @param String|null $returnType return type
     * @return string|int|null
     * @throws GuzzleException
     */
    public function RunCommand(string $command, array $object, string|null $returnType = "statusCode"): string|int|null
    {
        $client = new GuzzleHttp\Client();
        $payLoad = array(array(
            "cmd" => $command,
            "param" => array(
                $object
            )
        ));
        $json = json_encode($payLoad);
        $response = $client->request("POST", $this->url . "?cmd=" . $command . "&token=" . $this->token, array('verify' => false, 'body' => $json));
        if ($returnType == "statusCode") {
            return $response->getStatusCode();
        } elseif ($returnType == "body") {
            return $response->getBody()->getContents();
        } else {
            return null;
        }
    }
}