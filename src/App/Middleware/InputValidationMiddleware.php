<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 30.10.16
 * Time: 17:47
 */

namespace App\Middleware;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class InputValidationMiddleware
{
    protected $errors = [];
    protected $model;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $model = $response->getReasonPhrase()["model"];
        $input = json_decode(current($request->getParsedBody()), true);
        ///////////////////////////////////

//        print_r($input);
//        die("");

        //////////////////////////////////

        $data = [
            "model" => $this->loadValidations($model, $input)
        ];

        $response = $response->withStatus(200, $data);
        return $next($request, $response);
    }

    protected function loadValidations(&$model, $input)
    {
        foreach ($model as $key => $item) {
            if (is_array($model[$key])) {
                $this->loadValidations($model[$key], $input[$key]);
            }

            if (!isset($input[$key])) {
                unset($model[$key]);
                continue;
            }

            if (!is_array($model[$key])) {
                if (!empty($model[$key])) {
                    $model[$key] = $this->returnValidation($model[$key], $input[$key]);
                    continue;
                }

                if (!$model[$key]) {
                    $model[$key] = $input[$key];
                }

            }
        }

        if (count($this->errors) >= 1) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => $this->errors,
                ]
            );
        }

        return $model;
    }

    protected function returnValidation($validationName, $inputData)
    {
        return $this->$validationName($inputData);
    }

    protected function validateDate($date)
    {
        $dateLen = strlen($date);
        $format = 'H:i';

        if ($dateLen == 10) {
            $format = 'Y-m-d';
        }

        $d = DateTime::createFromFormat($format, $date);

        if ($d && $d->format($format) == $date) {
            return $date;
        }

        $this->errors[] = "Not valid date";

        return false;
    }

    protected function validatePassword($pass)
    {
        $strength = $this->calcPassScore($pass);

    }

    protected function checkPasswordStrength($pass)
    {
        $score = 0;
        if (!$pass)
            return $score;

        // award every unique letter until 5 repetitions
        $letters = [];
        $len = strlen($pass);
        for ($i = 0; $i < $len; $i += 1) {
            $letters[$pass[$i]] = ($letters[$pass[$i]] || 0) + 1;
            $score += 7.0 / $letters[$pass[$i]];
        }

        $validations = [
            "digits" => preg_match("/\d/", $pass),
            "lower" => preg_match("/[a-z]/", $pass),
            "upper" => preg_match("/[A-Z]/", $pass),
            "nonWords" => preg_match("/\W/", $pass),
        ];

        $validationsCount = 0;
        foreach ($validations as $key => $validation) {
            $validationsCount += ($validations[$key] == true) ? 1 : 0;
        }
        $score += ($validationsCount) * 10;
        return $score;
    }

    protected function calcPassScore($pass)
    {
        $score = $this->checkPasswordStrength($pass);
        $returnData = [
            "score" => $score,
            "message" => "Password must be at least good, is very weak. "
        ];

        if ($score >= 30) {
            $returnData["score"] = $score;
            $returnData["message"] = "weak";
        }

        if ($score >= 60) {
            $returnData["score"] = $score;
            $returnData["message"] = "good";
        }

        if ($score >= 80) {
            $returnData["score"] = $score;
            $returnData["message"] = "strong";
        }

        if ($score >= 100) {
            $returnData["score"] = 100;
            $returnData["message"] = "very strong";
        }

        return $returnData;
    }

    protected function checkPasswordMatch($data)
    {

    }

    protected function validateMail($email)
    {
        $isValid = true;

        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex + 1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                $this->errors[] = "local part length exceeded";
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                $this->errors[] = "domain part length exceeded";
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                $this->errors[] = "local part starts or ends with '.'";
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                $this->errors[] = "local part has two consecutive dots";
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                $this->errors[] = "character not valid in domain part";
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                $this->errors[] = "domain part has two consecutive dots";
                $isValid = false;
            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
                $this->errors[] = "character not valid in local part unless local part is quoted";
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                $this->errors[] = "domain not found in DNS";
                $isValid = false;
            }
        }

        if ($isValid) {
            return $email;
        }
        return false;
    }
}