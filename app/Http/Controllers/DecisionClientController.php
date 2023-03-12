<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use DateTime;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use PHPUnit\Logging\Exception;

class DecisionClientController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getClient (Request $request): JsonResponse
    {
        $validated = Clients::ValidateRequest($request->all());

        if (isset($validated->original['errors'])) {
            return response()->json([
                'message' => 'Error in ',
                'data' => $validated
            ]);
        }
        $currentDay = new DateTime();
        $dateCurr = $currentDay->format('Y-m-d');

        $idClient = $request->idClient;
        $dateBirthday = $request->dateBirthday;
        $requestLimit = (float) $request->requestLimit;
        $decision = 'decline';
        $monthSalary = 0;
        $currency = 'UAH';

        $ref = md5($idClient . $dateBirthday);

        $client = new Clients();
        $client->Ref = $ref;

        if (!empty($request->monthSalary)) {
            $monthSalary = (float) $request->monthSalary;
        }

        $currSalary = $request->currSalary;

        if (!empty($request->phone)) {
            $client->phone = $request->phone;
            $k = $this->getK($request->phone);
        }

        if (!empty($request->currSalary) &&  $request->currSalary !== 'UAH') {
            if ($monthSalary !== 0) {
                if ($currSalary !== 'UAH') {
                    $salary = $this->getCurrency($monthSalary, $currSalary);
                    $monthSalary = $salary['monthSalary'];
                    $currency = $salary['currency'];


                }
            }
        }

        $limitItog = $k * $monthSalary;

        $timeLimit = strtotime('-18 years');
        $dateBirthday = strtotime($dateBirthday);

        if ($timeLimit < $dateBirthday) {
            $requestLimit = 0;
        } else {
            if ($limitItog < $requestLimit ) {
                $requestLimit = $limitItog;
            }
        }

        $client->requestLimit = $requestLimit;

        if ($limitItog > 0 ) {
            $decision = 'accept';
        }

        $client->limitItog = $limitItog;
        $client->idClient = $idClient;
        $client->dateCurr = $dateCurr;

        if (!empty($request->mail)) {
            $client->mail = $request->mail;
        }

        if (!empty($request->address)) {
            $client->address = $request->address;
        }
        $client->monthSalary = $monthSalary;
        $client->currSalary = $currency;
        $client->decision = $decision;

        if ($client->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Decision added',
                'Ref' => $ref
            ]);
        } else {
            return response()->json([
                'error' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Converting $monthSalary in to UA
     *
     * @param float $monthSalary
     * @param string $currSalary
     *
     * @return array $result - monthSalary and currency
     */
    private function getCurrency ($monthSalary, $currSalary)
    {
        $url = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $currencys = json_decode($response, true);

        try {
            if (empty($currencys)) {
                throw new Exception('We did not get currency from privatbank');
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $result = [];

        foreach ($currencys as $currency) {
            if ($currency['ccy'] == $currSalary) {
                $monthSalary = $monthSalary * (float) $currency['buy'];

                $result = ['currency' => $currency['base_ccy'], 'monthSalary' => $monthSalary];
            }
        }

        return $result;
    }

    /**
     * Get K for calculate limitItog
     *
     * @param string $phone
     *
     * @return float $k
     */
    private function getK($phone)
    {
        $pattern = '/^(38)?(067|068|096|097|098|050|066|095|099|063|073|093)[0-9]{7}$/';

        if (preg_match($pattern, $phone, $matches)) {
            $operatorCode = substr($matches[0], 2, 3);
            switch ($operatorCode) {
                // For clients with Kyivstar
                case '096':
                case '067':
                case '068':
                    return 0.95;
                // For clients with Vodafone
                case '095':
                case '099':
                case '066':
                    return 0.94;
                // For clients with LifeCell
                case '093':
                case '063':
                case '073':
                    return 0.93;
                // For clients with Other mobile operator
                default:
                    return 0.92;
            }
        }
    }
}
