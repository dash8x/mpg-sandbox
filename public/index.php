<?php

/**
 * generates a random string of given length
 * containing uppercase letters and digits
 *
 * @param int
 * @return string
 **/
function rand_num($length = 32)
{
    //get array of all characters
    $chars = array_merge(range('0', '9'));
    $rand_str = ''; //string for output
    for ($i = 0; $i < $length; $i++) {
        $rand_str .= $chars[array_rand($chars)]; //randomly concatenate chars
    }
    return $rand_str;
}

/**
 * generates a random string of given length
 * containing uppercase letters and digits
 *
 * @param int
 * @return string
 **/
function rand_string($length = 32)
{
    //get array of all characters
    $chars = array_merge(range('0', '9'), range('A', 'Z'));
    $rand_str = ''; //string for output
    for ($i = 0; $i < $length; $i++) {
        $rand_str .= $chars[array_rand($chars)]; //randomly concatenate chars
    }
    return $rand_str;
}

$responses = array(
    array('resp_code' => 1, 'reason_code' => 1, 'desc' => 'Transaction is successful'),
    /*array( 'resp_code' => 2, 'reason_code' => 101,	'desc' => 'Invalid field passed to 3D Secure MPI' ),
    array( 'resp_code' => 2, 'reason_code' => 201,	'desc' => 'Invalid ACS response format. Transaction is aborted.' ),
    array( 'resp_code' => 2, 'reason_code' => 202,	'desc' => 'Cardholder failed the 3D authentication, password entered by cardholder is incorrect and transaction is aborted' ),
    array( 'resp_code' => 2, 'reason_code' => 203,	'desc' => '3D PaRes has invalid signature. Transaction is aborted' ),
    array( 'resp_code' => 3, 'reason_code' => 300,	'desc' => 'Transaction not approved' ),
    array( 'resp_code' => 3, 'reason_code' => 301,	'desc' => 'Record not found' ),
    array( 'resp_code' => 3, 'reason_code' => 302,	'desc' => 'Transaction not allowed' ),
    array( 'resp_code' => 3, 'reason_code' => 303,	'desc' => 'Invalid Merchant ID' ),
    array( 'resp_code' => 3, 'reason_code' => 304,	'desc' => 'Transaction blocked by error 901' ),
    array( 'resp_code' => 3, 'reason_code' => 900,	'desc' => '3D Transaction timeout' ),
    array( 'resp_code' => 3, 'reason_code' => 901,	'desc' => 'System Error' ),
    array( 'resp_code' => 3, 'reason_code' => 902,	'desc' => 'Time out' ),*/
);
$response = $responses[array_rand($responses)];


$password = '123456';
$merch_resp_url = isset($_POST['MerRespURL']) ? $_POST['MerRespURL'] : '';
$version = isset($_POST['Version']) ? $_POST['Version'] : '';
$purchase_curr = isset($_POST['PurchaseCurrency']) ? $_POST['PurchaseCurrency'] : '';
$purchase_curr_exp = isset($_POST['PurchaseCurrencyExponent']) ? $_POST['PurchaseCurrencyExponent'] : '';
$purchase_amount = isset($_POST['PurchaseAmt']) ? $_POST['PurchaseAmt'] : '';
$merchant_id = isset($_POST['MerID']) ? $_POST['MerID'] : '';
$acq_id = isset($_POST['AcqID']) ? $_POST['AcqID'] : '';
$order_id = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
$signature = isset($_POST['Signature']) ? $_POST['Signature'] : '';
$generated_sig = base64_encode(sha1($password . $merchant_id . $acq_id . $order_id . $purchase_amount . $purchase_curr, true));
$verified = $signature == $generated_sig;
$response_signature = base64_encode(sha1($password . $merchant_id . $acq_id . $order_id . $response['resp_code'] . $response['reason_code'], true));
$card_number = 'xxxxxxxxxxxx' . rand_num(4);
$auth_code = rand_string(6);
$ref_num = rand_num(8) . rand_string(4);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Maldives Payment Gateway Sandbox">
        <meta name="author" content="Arushad Ahmed (@dash8x)">

        <title>MPG Sandbox</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>

    <body class="bg-light">
        <div class="container">
            <div class="py-5 text-center">
                <h2>MPG Sandbox</h2>
                <p class="lead">
                    This is a demo sandbox for Maldives Payment Gateway (MPG).<br>Click on proceed after entering your desired parameters.
                    <br>Get the code from <a href="https://github.com/dash8x/mpg-sandbox">dash8x/mpg-sandbox</a>.
                </p>
            </div>

            <form method="POST" action="<?php echo $merch_resp_url; ?>">
                <div class="alert alert-<?php echo $verified ? 'success' : 'danger'; ?>">
                    <h4 class="alert-heading"><?php echo $verified ? 'Verified' : 'Failed Sig'; ?></h4>
                    <p class="mb-0">
                        <?php echo $verified ? 'Signature verification successful!' : 'Signature verification failed!'; ?>
                    </p>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ResponseCode">Response Code</label>
                            <input type="text" class="form-control" placeholder="ResponseCode" name="ResponseCode" value="<?php echo $response['resp_code']; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ReasonCode">Reason Code</label>
                            <input type="text" class="form-control" placeholder="ReasonCode" name="ReasonCode" value="<?php echo $response['reason_code']; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ReasonCodeDesc">Reason Code Description</label>
                            <input type="text" class="form-control" placeholder="ReasonCodeDesc" name="ReasonCodeDesc" value="<?php echo $response['desc']; ?>"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ReferenceNo">Reference No.</label>
                            <input type="text" class="form-control" placeholder="ReferenceNo" name="ReferenceNo" value="<?php echo $ref_num; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="PaddedCardNo">Padded Card No.</label>
                            <input type="text" class="form-control" placeholder="PaddedCardNo" name="PaddedCardNo" value="<?php echo $card_number; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="AuthCode">Auth Code</label>
                            <input type="text" class="form-control" placeholder="AuthCode" name="AuthCode" value="<?php echo $auth_code; ?>"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="MerID">Merchant ID</label>
                            <input type="text" class="form-control" placeholder="MerID" name="MerID" value="<?php echo $merchant_id; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="AcqID">Acquirer ID</label>
                            <input type="text" class="form-control" placeholder="AcqID" name="AcqID" value="<?php echo $acq_id; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="OrderID">Order ID</label>
                            <input type="text" class="form-control" placeholder="OrderID" name="OrderID" value="<?php echo $order_id; ?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Signature">Signature</label>
                    <input type="text" class="form-control" placeholder="Signature" name="Signature" value="<?php echo $response_signature; ?>"/>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Proceed</button>
            </form>
        </div>
    </body>
</html>