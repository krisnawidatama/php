<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Token</title>
    </head>
    <body>
        <div class="container">
            <div class="row mt-3">
                <?php
                error_reporting(0);
                $realm = 'widatama10523209';
                $users = array('admin' => '12345');
                if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
                    header('HTTP/1.1 401 Unauthorized');
                    header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
                    echo "<h4 class='alert alert-danger d-flex align-items-center'>Anda Menekan Tombol Cancel</h4>";
                } else {
                    if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])) {
                        echo "<h4 class='alert alert-danger d-flex align-items-center'>Anda Gagal Logins</h4>";
                    } else {
                        $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
                        $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
                        $valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);
                        if ($data['response'] != $valid_response) {
                            echo "<h4 class='alert alert-danger d-flex align-items-center'>Anda Gagal Login</h4>";
                        } else {
                            echo "<h4 class='alert alert-primary d-flex align-items-center'>Anda Login Sebagai: " . $data['username'] . "</h4><br/> Token Login: " . $valid_response;
                        }
                    }
                }

                function http_digest_parse($txt) {
                    // protect against missing data
                    $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
                    $data = array();
                    $keys = implode('|', array_keys($needed_parts));

                    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

                    foreach ($matches as $m) {
                        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
                        unset($needed_parts[$m[1]]);
                    }

                    return $needed_parts ? false : $data;
                }
                ?>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
