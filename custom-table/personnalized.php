<?php

require_once ("vendor/autoload.php");
use Sightengine\SightengineClient;
use \ConvertApi\ConvertApi;

class Perso{

    public function __construct(float $sharpnessValue = null)
    {
        $this->sharpness = $sharpnessValue;
        $this->default = 0.6;
    }

    // Checking picture
    public function check ($picture){
        $sharpness =  $this->sharpness;
        $sharpnessValue = is_null($sharpness) || empty($sharpness) ? $this->default : $sharpness; 

        $data = $this->api($picture);
        $status = $data->status;

        // On retourne failure si l'api ne parvient pas à récupérer l'image
        if ($status == "failure") {
            return 'failure';
        }
        $sharpness = $data->sharpness;

        if ($sharpness < $sharpnessValue){
            return 'sharpness';
        }
        // On retourne une réponse positive après vérification
        return 'success';
    }

    // API
    public function api (string $picture): Object {

        $client = new SightengineClient('1081213732', 'Dx9nnUYNAYMcTePfPoU3');
        // if ($local) {
        return $client->check(['properties'])->set_file($picture);
        // }
        // $output = $client->check(['properties'])->set_url($picture);
        // return $output;
    }

    //Upscale
    public function upscale (string $picture, int $level) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://super-image1.p.rapidapi.com/run",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{
            \"upscale\": $level,
            \"image\": \"$picture\"
        }",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: super-image1.p.rapidapi.com",
                "X-RapidAPI-Key: 9855fb13c8msh10e86219feac154p1e5fdbjsnafe000e73997",
                "content-type: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }


    // Converti le format heic ou webp en jpeg 
    public function convert(string $picture, string $saveDir, string $format) {
        ConvertApi::setApiSecret('Pb2usqpMtlo6GnoU');
        $result = ConvertApi::convert('jpg', [
                'File' => $picture,
            ], $format
        );
        $result->saveFiles($saveDir);
    }
 


}