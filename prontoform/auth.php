<?php
    function CallAPI($method, $url, $data)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-length: ' . strlen($data)));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;

        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;

        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "a2141160022:lgb5iqla6jwEZ3/izqSIpclll40/L9MIJ6VFc3+iVb+vYq0BxpMPprRG9M87YSkz");
    //curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    


    $result[0] = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result[1] = $httpcode;
    curl_close($curl);

    return $result;
}
function getStringBetween($str,$from,$to)
                {
                    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
                    return substr($sub,0,strpos($sub,$to));
                }
?>