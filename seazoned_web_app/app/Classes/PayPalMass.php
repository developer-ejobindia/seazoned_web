<?php

namespace App\Classes;

use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\PayPalAPI\MassPayReq;
use PayPal\PayPalAPI\MassPayRequestItemType;
use PayPal\PayPalAPI\MassPayRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
use PayPal\Auth\PPSignatureCredential;
use PayPal\Auth\PPTokenAuthorization;

class PayPalMass {

    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function call($Receiver = [], $receiverInfoCode = 'EmailAddress') {
        
        $massPayRequest = new MassPayRequestType();
        $massPayRequest->MassPayItem = array();
        for ($i = 0; $i < count($Receiver); $i++) {
            $masspayItem = new MassPayRequestItemType();
            /*
             *  `Amount` for the payment which contains
             * `Currency Code`
             * `Amount`
             */
            
            $masspayItem->Amount = new BasicAmountType($Receiver[$i]['currencyCode'], $Receiver[$i]['amount']);
            if ($receiverInfoCode == 'EmailAddress') {
                /*
                 *  (Optional) How you identify the recipients of payments in this call to MassPay. It is one of the following values:
                  EmailAddress
                  UserID
                  PhoneNumber
                 */
                $masspayItem->ReceiverEmail = $Receiver[$i]['mail'];
            } elseif ($receiverInfoCode == 'UserID') {
                $masspayItem->ReceiverID = $Receiver[$i]['id'];
            } elseif ($receiverInfoCode == 'PhoneNumber') {
                $masspayItem->ReceiverPhone = $Receiver[$i]['phone'];
            }
            $massPayRequest->MassPayItem[] = $masspayItem;
        }
        
        /*
         *  ## MassPayReq
          Details of each payment.
          `Note:
          A single MassPayRequest can include up to 250 MassPayItems.`
         */
        
        $massPayReq = new MassPayReq();
        $massPayReq->MassPayRequest = $massPayRequest;
        /*
         * 	 ## Creating service wrapper object
          Creating service wrapper object to make API call and loading
          Configuration::getAcctAndConfig() returns array that contains credential and config parameters
         */
        
        $paypalService = new PayPalAPIInterfaceServiceService($this->config);

        try {

                $massPayResponse = $paypalService->MassPay($massPayReq);
                return $massPayResponse->Ack;
//                print_r($massPayResponse);exit;

        } catch (Exception $ex) {
            include_once("../Error.php");
//            echo 'kkkk';
            exit;
        }

    }

}

?>
