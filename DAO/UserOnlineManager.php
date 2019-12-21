<?php 
namespace DAO;

abstract class UserOnlineManager {

    public static function setUserOnline($id_user=null) {
        $value = 0;
        if($id_user!=null) {
            try
            {
                $parameters["id_user"] = $id_user;
                $parameters["ip_address"] = UserOnlineManager::getUserIpAddress();
                $userGeoInfo=UserOnlineManager::ip_info($parameters["ip_address"]);
                $parameters["country"] = $userGeoInfo["country"];
                $parameters["region"] = $userGeoInfo["state"];
                $parameters["city"] = $userGeoInfo["city"];
                $parameters["user_agent"] = UserOnlineManager::get_browser_name($_SERVER["HTTP_USER_AGENT"]);
                $query = "insert into users_online (id_user,ip_address,country,region,city,user_agent) values (:id_user,:ip_address,:country,:region,:city,:user_agent);";

                $connection = Connection::getInstance();
                $value = $connection->executeNonQuery($query,$parameters);
            }
            catch (PDOException $e)
            {
                throw $e;
            }
        }
        return $value;
    }

    public static function setUserOffline($id_user=null) {
        $value = 0;
        if($id_user!=null)
        {
            try
            {
                $parameters["id_user"] = $id_user;
                $query = "delete from users_online where id_user=:id_user;";
                
                $connection = Connection::getInstance();
                $value = $connection->executeNonQuery($query,$parameters);
            }
            catch (PDOException $e)
            {
                throw $e;
            }
        }
        return $value;
    }

    public static function setUserLastTimeOnline($id_user=null) {
        $value = 0;
        if($id_user!=null)
        {
            try
            {
                $parameters["ip_address"] = UserOnlineManager::getUserIpAddress();
                $userGeoInfo=UserOnlineManager::ip_info($parameters["ip_address"]);
                $parameters["country"] = $userGeoInfo["country"];
                $parameters["region"] = $userGeoInfo["state"];
                $parameters["city"] = $userGeoInfo["city"];
                $parameters["user_agent"] = UserOnlineManager::get_browser_name($_SERVER["HTTP_USER_AGENT"]); 
                $parameters["id_user"] = $id_user;
                $query = "update users_online set last_time=now(),ip_address=:ip_address,country=:country,region=:region,city=:city,user_agent=:user_agent where id_user=:id_user;";
                
                $connection = Connection::getInstance();
                $value = $connection->executeNonQuery($query,$parameters);
            }
            catch (PDOException $e)
            {
                throw $e;
            }
        }
        return $value;
    }

    public static function setUsersOfflineForUselessTime() {
        $value = 0;
    
        try
        {
            $query = "delete from users_online where (select timestampdiff(SECOND,last_time,now()))>=600;";
            
            $connection = Connection::getInstance();
            $value = $connection->executeNonQuery($query);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        
        return $value;
    }

    public static function retrieveAllUsersOnline() {
        $usersOnlineAmount = 0;

        try
        {
            $query = "SELECT count(*) FROM users_online;";

            $connection = Connection::getInstance();

            $resultSet = $connection->execute($query);

            $usersOnlineAmount = $resultSet[0][0];
            
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        
        return $usersOnlineAmount;
    }

    private static function getUserIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
        return $_SERVER['REMOTE_ADDR'];
    }

    private static function get_browser_name($user_agent) {
            // Make case insensitive.
            $t = strtolower($user_agent);

            // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
            // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
            //     http://php.net/manual/en/function.strpos.php
            $t = " " . $t;

            // Humans / Regular Users     
            if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
            elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
            elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
            elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
            elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
            elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';

            // Search Engines 
            elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'   ;
            elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'     ;
            elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
            elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot' ;
            elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'       ;
            elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'      ;
            elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'       ;
            elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'      ;
            elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'         ;

            // Common Tools and Bots
            elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'     ;
            elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'       ;
            elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'      ;
            elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
            elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
        
            // Miscellaneous
            elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'     ;
            elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'    ;
        
            // Check for strings commonly used in bot user agents  
            elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
                    strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
                    strpos($t, 'bot'     ) || strpos($t, 'archive') ||
                    strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'   ;
        
            return 'Other (Unknown)';
    } 

    private static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}
?>