<?php
if(!class_exists("WooLentorProBase")) {
    class WooLentorProBase {
        public $key = "4DEB1BC6E89224E1";
        private $product_id = "2";
        private $product_base = "woolentor-pro";
        private $server_host = "https://htcode.biz/wp-json/licensor/";
        private $hasCheckUpdate=true;
        private $isEncryptUpdate=false;
        private $pluginFile;
        private static $selfobj=null;
        private $version="";
        private $isTheme=false;
        private $emailAddress = "";
        private static $_onDeleteLicense=[];
        function __construct($plugin_base_file='')
        {
            $this->pluginFile=$plugin_base_file;
            $dir=dirname($plugin_base_file);
            $dir=str_replace('\\','/',$dir);
            if(strpos($dir,'wp-content/themes')!==FALSE){
                $this->isTheme=true;
            }
            $this->version=$this->getCurrentVersion();
            if($this->hasCheckUpdate) {
                if(function_exists("add_action")){
                    add_action( 'admin_post_woolentor-pro_fupc', function(){
                        update_option('_site_transient_update_plugins','');
                        update_option('_site_transient_update_themes','');
                        set_site_transient('update_themes', null);
                        wp_redirect(  admin_url( 'plugins.php' ) );
                        exit;
                    });
                    add_action( 'init', [$this,"initActionHandler"]);

                }
                if(function_exists("add_filter")) {
                    //
                    if($this->isTheme){
                        add_filter('pre_set_site_transient_update_themes', [$this, "PluginUpdate"]);
                        add_filter('themes_api', [$this, 'checkUpdateInfo'], 10, 3);
                    }else{
                        add_filter('pre_set_site_transient_update_plugins', [$this, "PluginUpdate"]);
                        add_filter('plugins_api', [$this, 'checkUpdateInfo'], 10, 3);
                        add_filter( 'plugin_row_meta', function($links, $plugin_file ){
                            if ( $plugin_file == plugin_basename( $this->pluginFile ) ) {
                                $links[] = " <a class='edit coption' href='" . esc_url( admin_url( 'admin-post.php' ) . '?action=woolentor-pro_fupc' ) . "'>Update Check</a>";
                            }
                            return $links;
                        }, 10, 2 );
                        add_action( "in_plugin_update_message-".plugin_basename( $this->pluginFile ), [$this,'updateMessageCB'], 20, 2 );
                    }



                }


            }
        }
        public function setEmailAddress( $emailAddress ) {
            $this->emailAddress = $emailAddress;
        }
        function initActionHandler(){
            $handler=hash("crc32b",$this->product_id.$this->key.$this->getDomain())."_handle";
            if(isset($_GET['action']) && $_GET['action']==$handler){
                $this->handleServerRequest();
                exit;
            }
        }
        function handleServerRequest(){
            $type=isset($_GET['type'])?strtolower($_GET['type']):"";
            switch ($type) {
                case "rl": //remove license
                    $this->cleanUpdateInfo();
                    $this->removeOldWPResponse();
                    $obj          = new stdClass();
                    $obj->product = $this->product_id;
                    $obj->status  = true;
                    echo $this->encryptObj( $obj );
                    
                    return;
                case "rc": //remove license
                    $key  = $this->getKeyName();
                    delete_option( $key );
                    $obj          = new stdClass();
                    $obj->product = $this->product_id;
                    $obj->status  = true;
                    echo $this->encryptObj( $obj );
                    return;
                case "dl": //delete plugins
                    $obj          = new stdClass();
                    $obj->product = $this->product_id;
                    $obj->status  = false;
                    $this->removeOldWPResponse();
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    if ( $this->isTheme ) {
                        $res = delete_theme( $this->pluginFile );
                        if ( ! is_wp_error( $res ) ) {
                            $obj->status = true;
                        }
                        echo $this->encryptObj( $obj );
                    } else {
                        $res = delete_plugins( [ plugin_basename( $this->pluginFile ) ] );
                        if ( ! is_wp_error( $res ) ) {
                            $obj->status = true;
                        }
                        echo $this->encryptObj( $obj );
                    }
                    
                    return;
                default:
                    return;
            }
        }
        /**
         * @param callable $func
         */
        static function addOnDelete( $func){
            self::$_onDeleteLicense[]=$func;
        }
        function getCurrentVersion(){
            if( !function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $data=get_plugin_data($this->pluginFile);
            if(isset($data['Version'])){
                return $data['Version'];
            }
            return 0;
        }
        public function cleanUpdateInfo(){
            update_option('_site_transient_update_plugins','');
            update_option('_site_transient_update_themes','');
        }
        public function updateMessageCB($data, $response){
            if(is_array($data)){
                $data=(object)$data;
            }
            if(isset($data->package) && empty($data->package)) {
                if(empty($data->update_denied_type)) {
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>active product</strong> or  <strong>renew support period</strong> to get latest version</span>";
                }elseif($data->update_denied_type=="L"){
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>active product</strong> to get latest version</span>";
                }elseif($data->update_denied_type=="S"){
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>renew support period</strong> to get latest version</span>";
                }
            }
        }
        function __plugin_updateInfo(){
            if(function_exists("wp_remote_get")) {
	
	            $response = get_transient( $this->product_base."_up" );
	            $oldFound = false;
	            if ( ! empty( $response['data'] ) ) {
		            $response = unserialize( $this->decrypt( $response['data'] ) );
		            if ( is_array( $response ) ) {
			            $oldFound = true;
		            }
	            }
	            
	            if ( ! $oldFound ) {
		            $licenseInfo=self::GetRegisterInfo();
		            $url=$this->server_host . "product/update/" . $this->product_id;
		            if(!empty($licenseInfo->license_key)) {
			            $url.="/".$licenseInfo->license_key."/".$this->version;
		            }
		            $args=[
			            'sslverify' => false,
			            'timeout' => 120,
			            'redirection' => 5,
			            'cookies' => array()
		            ];
		            $response = wp_remote_get( $url,$args);
	            }
	            
                if ( is_array( $response ) ) {
                    $body         = $response['body'];
                    $responseJson = @json_decode( $body );
	                if ( ! $oldFound ) {
		                set_transient( $this->product_base."_up", [ "data" => $this->encrypt( serialize( [ 'body' => $body ] ) ) ], DAY_IN_SECONDS );
	                }
	                
                    if(!(is_object( $responseJson ) && isset($responseJson->status )) && $this->isEncryptUpdate){
                        $body=$this->decrypt($body,$this->key);
                        $responseJson = json_decode( $body );
                    }
	                
                    if ( is_object( $responseJson ) && ! empty( $responseJson->status ) && ! empty( $responseJson->data->new_version ) ) {
                        $responseJson->data->slug = plugin_basename( $this->pluginFile );;
                        $responseJson->data->new_version = ! empty( $responseJson->data->new_version ) ? $responseJson->data->new_version : "";
                        $responseJson->data->url         = ! empty( $responseJson->data->url ) ? $responseJson->data->url : "";
                        $responseJson->data->package     = ! empty( $responseJson->data->download_link ) ? $responseJson->data->download_link : "";
                        $responseJson->data->update_denied_type     = ! empty( $responseJson->data->update_denied_type ) ? $responseJson->data->update_denied_type : "";

                        $responseJson->data->sections    = (array) $responseJson->data->sections;
                        $responseJson->data->plugin      = plugin_basename( $this->pluginFile );
                        $responseJson->data->icons       = (array) $responseJson->data->icons;
                        $responseJson->data->banners     = (array) $responseJson->data->banners;
                        $responseJson->data->banners_rtl = (array) $responseJson->data->banners_rtl;
                        unset( $responseJson->data->IsStoppedUpdate );

                        return $responseJson->data;
                    }
                }
            }

            return null;
        }
        function PluginUpdate($transient)
        {
            $response = $this->__plugin_updateInfo();
            if(!empty($response->plugin)){
                if($this->isTheme){
                    $theme_data = wp_get_theme();
                    $index_name="".$theme_data->get_stylesheet();
                }else{
                    $index_name=$response->plugin;
                }
                if (!empty($response) && version_compare($this->version, $response->new_version, '<')) {
                    unset($response->download_link);
                    unset($response->IsStoppedUpdate);
                    if($this->isTheme){
                        $transient->response[$index_name] = (array)$response;
                    }else{
                        $transient->response[$index_name] = (object)$response;
                    }
                }else{
                    if(isset($transient->response[$index_name])){
                        unset($transient->response[$index_name]);
                    }
                }
            }
            return $transient;
        }
        final function checkUpdateInfo($false, $action, $arg) {
            if ( empty($arg->slug)){
                return $false;
            }
            if($this->isTheme){
                if ( !empty($arg->slug) && $arg->slug === $this->product_base){
                    $response =$this->__plugin_updateInfo();
                    if ( !empty($response)) {
                        return $response;
                    }
                }
            }else{
                if ( !empty($arg->slug) && $arg->slug === plugin_basename($this->pluginFile) ) {
                    $response =$this->__plugin_updateInfo();
                    if ( !empty($response)) {
                        return $response;
                    }
                }
            }

            return $false;
        }

        /**
         * @param $plugin_base_file
         *
         * @return self|null
         */
        static function &getInstance($plugin_base_file=null) {
            if(empty(self::$selfobj)){
                if(!empty($plugin_base_file)) {
                    self::$selfobj = new self( $plugin_base_file );
                }
            }
            return self::$selfobj;
        }
        static function getRenewLink($responseObj,$type="s"){
            if(empty($responseObj->renew_link)){
                return "";
            }
            $isShowButton=false;
            if($type=="s") {
                if ( strtolower( trim( $responseObj->support_end ) ) == "no support" ) {
                    $isShowButton = true;
                } elseif ( strtolower( trim( $responseObj->support_end ) ) != "unlimited" ) {
                    if ( strtotime( 'ADD 30 DAYS', strtotime( $responseObj->support_end ) ) < time() ) {
                        $isShowButton = true;
                    }
                }
                if ( $isShowButton ) {
                    return $responseObj->renew_link.(strpos($responseObj->renew_link,"?")===FALSE?'?type=s&lic='.rawurlencode($responseObj->license_key):'&type=s&lic='.rawurlencode($responseObj->license_key));
                }
                return '';
            }else{
                if ( strtolower( trim( $responseObj->expire_date ) ) != "unlimited" ) {
                    if ( strtotime( 'ADD 30 DAYS', strtotime( $responseObj->expire_date ) ) < time() ) {
                        $isShowButton = true;
                    }
                }
                if ( $isShowButton ) {
                    return $responseObj->renew_link.(strpos($responseObj->renew_link,"?")===FALSE?'?type=l&lic='.rawurlencode($responseObj->license_key):'&type=l&lic='.rawurlencode($responseObj->license_key));
                }
                return '';
            }
        }

        private function encrypt($plainText,$password='') {
            if(empty($password)){
                $password=$this->key;
            }
            $plainText=rand(10,99).$plainText.rand(10,99);
            $method = 'aes-256-cbc';
            $key = substr( hash( 'sha256', $password, true ), 0, 32 );
            $iv = substr(strtoupper(md5($password)),0,16);
            return base64_encode( openssl_encrypt( $plainText, $method, $key, OPENSSL_RAW_DATA, $iv ) );
        }
        private function decrypt($encrypted,$password='') {
            if(empty($password)){
                $password=$this->key;
            }
            $method = 'aes-256-cbc';
            $key = substr( hash( 'sha256', $password, true ), 0, 32 );
            $iv = substr(strtoupper(md5($password)),0,16);
            $plaintext=openssl_decrypt( base64_decode( $encrypted ), $method, $key, OPENSSL_RAW_DATA, $iv );
            return substr($plaintext,2,-2);
        }

        function encryptObj( $obj ) {
            $text = serialize( $obj );

            return $this->encrypt( $text );
        }

        private function decryptObj( $ciphertext ) {
            $text = $this->decrypt( $ciphertext );

            return unserialize( $text );
        }

        private function getDomain() {
            if(function_exists("site_url")){
                return site_url();
            }
            if ( defined( "WPINC" ) && function_exists( "get_bloginfo" ) ) {
                return get_bloginfo( 'url' );
            } else {
                $base_url = ( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == "on" ) ? "https" : "http" );
                $base_url .= "://" . $_SERVER['HTTP_HOST'];
                $base_url .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), "", $_SERVER['SCRIPT_NAME'] );

                return $base_url;
            }
        }

        private function getEmail() {
            return $this->emailAddress;
        }
        private function processs_response($response){
            $resbk="";
            if ( ! empty( $response ) ) {
                if ( ! empty( $this->key ) ) {
                    $resbk=$response;
                    $response = $this->decrypt( $response );
                }
                $response = json_decode( $response );

                if ( is_object( $response ) ) {
                    return $response;
                } else {
                    $response=new stdClass();
                    $response->status = false;
                    $response->msg    = "Response Error, contact with the author or update the plugin or theme";
                    if(!empty($bkjson)){
                        $bkjson=@json_decode($resbk);
                        if(!empty($bkjson->msg)){
                            $response->msg    = $bkjson->msg;
                        }
                    }
                    $response->data = NULL;
                    return $response;

                }
            }
            $response=new stdClass();
            $response->msg    = "unknown response";
            $response->status = false;
            $response->data = NULL;

            return $response;
        }
        private function _request( $relative_url, $data, &$error = '' ) {
            $response         = new stdClass();
            $response->status = false;
            $response->msg    = "Empty Response";
            $response->is_request_error = false;
            $finalData        = json_encode( $data );
            if ( ! empty( $this->key ) ) {
                $finalData = $this->encrypt( $finalData );
            }
            $url = rtrim( $this->server_host, '/' ) . "/" . ltrim( $relative_url, '/' );
            if(function_exists('wp_remote_post')) {
                $serverResponse = wp_remote_post($url, array(
                        'method' => 'POST',
                        'sslverify' => false,
                        'timeout' => 120,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => array(),
                        'body' => $finalData,
                        'cookies' => array()
                    )
                );


                if (is_wp_error($serverResponse)) {
                    $response->msg    = $serverResponse->get_error_message();;
                    $response->status = false;
                    $response->data = NULL;
                    $response->is_request_error = true;
                    return $response;
                } else {
                     if(!empty($serverResponse['body']) && $serverResponse['body']!="GET404"){
                        return $this->processs_response($serverResponse['body']);
                    }
                }

            }
            if(!extension_loaded('curl')){
                $response->msg    = "Curl extension is missing";
                $response->status = false;
                $response->data = NULL;
                $response->is_request_error = true;
                return $response;
            }
            //curl when fall back
            $curl             = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => $finalData,
                CURLOPT_HTTPHEADER     => array(
                    "Content-Type: text/plain",
                    "cache-control: no-cache"
                ),
            ) );
            $serverResponse = curl_exec( $curl );
            //echo $response;
            $error = curl_error( $curl );
            curl_close( $curl );
            if ( ! empty( $serverResponse ) ) {
                return $this->processs_response($serverResponse);
            }
            $response->msg    = "unknown response";
            $response->status = false;
            $response->data = NULL;
            $response->is_request_error = true;
            return $response;
        }

        private function getParam( $purchase_key, $app_version, $admin_email = '' ) {
            $req               = new stdClass();
            $req->license_key  = $purchase_key;
            $req->email        = ! empty( $admin_email ) ? $admin_email : $this->getEmail();
            $req->domain       = $this->getDomain();
            $req->app_version  = $app_version;
            $req->product_id   = $this->product_id;
            $req->product_base = $this->product_base;

            return $req;
        }

        private function getKeyName() {
            return hash( 'crc32b', $this->getDomain() . $this->pluginFile . $this->product_id . $this->product_base . $this->key . "LIC" );
        }

        private function SaveWPResponse( $response ) {
            $key  = $this->getKeyName();
            $data = $this->encrypt( serialize( $response ), $this->getDomain() );
            update_option( $key, $data ) OR add_option( $key, $data );
        }

        private function getOldWPResponse() {
            $key  = $this->getKeyName();
            $response = get_option( $key, NULL );
            if ( empty( $response ) ) {
                return NULL;
            }

            return unserialize( $this->decrypt( $response, $this->getDomain() ) );
        }

        private function removeOldWPResponse() {
            $key  = $this->getKeyName();
            $isDeleted = delete_option( $key );
            foreach ( self::$_onDeleteLicense as $func ) {
                if ( is_callable( $func ) ) {
                    call_user_func( $func );
                }
            }

            return $isDeleted;
        }
        public static function RemoveLicenseKey($plugin_base_file,&$message = "") {
            $obj=self::getInstance($plugin_base_file);
            $obj->cleanUpdateInfo();
            return $obj->_removeWPPluginLicense($message);
        }
        public static function CheckWPPlugin($purchase_key, $email,&$error = "", &$responseObj = null,$plugin_base_file="") {
            $obj=self::getInstance($plugin_base_file);
            $obj->setEmailAddress($email);
            return $obj->_CheckWPPlugin($purchase_key, $error, $responseObj);
        }
        final function _removeWPPluginLicense(&$message=''){
            $oldRespons=$this->getOldWPResponse();
            if(!empty($oldRespons->is_valid)) {
                if ( ! empty( $oldRespons->license_key ) ) {
                    $param    = $this->getParam( $oldRespons->license_key, $this->version );
                    $response = $this->_request( 'product/deactive/'.$this->product_id, $param, $message );
                    if ( empty( $response->code ) ) {
                        if ( ! empty( $response->status ) ) {
                            $message = $response->msg;
                            $this->removeOldWPResponse();
                            return true;
                        }else{
                            $message = $response->msg;
                        }
                    }else{
                        $message=$response->message;
                    }
                }
            }else{
                $this->removeOldWPResponse();
                return true;
            }
            return false;

        }
        public static function GetRegisterInfo() {
            if(!empty(self::$selfobj)){
                return self::$selfobj->getOldWPResponse();
            }
            return null;

        }

        final function _CheckWPPlugin( $purchase_key, &$error = "", &$responseObj = null ) {
            if(empty($purchase_key)){
                $this->removeOldWPResponse();
                $error="";
                return false;
            }
            $oldRespons=$this->getOldWPResponse();
            $isForce=false;
            if(!empty($oldRespons)) {
                if ( ! empty( $oldRespons->expire_date ) && strtolower( $oldRespons->expire_date ) != "no expiry" && strtotime( $oldRespons->expire_date ) < time() ) {
                    $isForce = true;
                }
                if ( ! $isForce && ! empty( $oldRespons->is_valid ) && $oldRespons->next_request > time() && ( ! empty( $oldRespons->license_key ) && $purchase_key == $oldRespons->license_key ) ) {
                    $responseObj = clone $oldRespons;
                    unset( $responseObj->next_request );

                    return true;
                }
            }

            $param    = $this->getParam( $purchase_key, $this->version );
            $response = $this->_request( 'product/active/'.$this->product_id, $param, $error );
            if(empty($response->is_request_error)) {
                if ( empty( $response->code ) ) {
                    if ( ! empty( $response->status ) ) {
                        if ( ! empty( $response->data ) ) {
                            $serialObj = $this->decrypt( $response->data, $param->domain );

                            $licenseObj = unserialize( $serialObj );
                            if ( $licenseObj->is_valid ) {
                                $responseObj           = new stdClass();
                                $responseObj->is_valid = $licenseObj->is_valid;
                                if ( $licenseObj->request_duration > 0 ) {
                                    $responseObj->next_request = strtotime( "+ {$licenseObj->request_duration} hour" );
                                } else {
                                    $responseObj->next_request = time();
                                }
                                $responseObj->expire_date   = $licenseObj->expire_date;
                                $responseObj->support_end   = $licenseObj->support_end;
                                $responseObj->license_title = $licenseObj->license_title;
                                $responseObj->license_key   = $purchase_key;
                                $responseObj->msg           = $response->msg;
                                $responseObj->renew_link           = !empty($licenseObj->renew_link)?$licenseObj->renew_link:"";
                                $responseObj->expire_renew_link           = self::getRenewLink($responseObj,"l");
                                $responseObj->support_renew_link           = self::getRenewLink($responseObj,"s");
                                $this->SaveWPResponse( $responseObj );
                                unset( $responseObj->next_request );

                                return true;
                            } else {
                                if ( $this->__checkoldtied( $oldRespons, $responseObj, $response ) ) {
                                    return true;
                                } else {
                                    $this->removeOldWPResponse();
                                    $error = ! empty( $response->msg ) ? $response->msg : "";
                                }
                            }
                        } else {
                            $error = "Invalid data";
                        }

                    } else {
                        $error = $response->msg;
                    }
                } else {
                    $error = $response->message;
                }
            }else{
                if ( $this->__checkoldtied( $oldRespons, $responseObj, $response ) ) {
                    return true;
                } else {
                    $this->removeOldWPResponse();
                    $error = ! empty( $response->msg ) ? $response->msg : "";
                }
            }
            return $this->__checkoldtied($oldRespons,$responseObj);
        }
        private function __checkoldtied(&$oldRespons,&$responseObj){
            if(!empty($oldRespons) && (empty($oldRespons->tried) || $oldRespons->tried<=2)){
                $oldRespons->next_request = strtotime("+ 1 hour");
                $oldRespons->tried=empty($oldRespons->tried)?1:($oldRespons->tried+1);
                $responseObj = clone $oldRespons;
                unset( $responseObj->next_request );
                if(isset($responseObj->tried)) {
                    unset( $responseObj->tried );
                }
                $this->SaveWPResponse($oldRespons);
                return true;
            }
            return false;
        }
    }
}