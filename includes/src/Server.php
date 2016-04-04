<?php
/**
 * \Lobby\Server
 * A Class for communication with Lobby server
 */
namespace Lobby;

class Server {
  
  /**
   * Lobby Store
   */
  public static function store($data) {
    /**
     * Response is in JSON
     */
    $response = \Requests::post(L_SERVER . "/apps", array(), $data)->body;
    if($response == "false"){
      return false;
    }else{
      $arr = json_decode($response, true);
      
      /**
       * Make sure the response was valid.
       */
      if(!is_array($arr)){
        \Lobby::log("Lobby Server Replied : {$response}");
        return false;
      }else{
        return $arr;
      }
    }
  }
  
  /**
   * Download Zip files
   */
  public static function download($type = "app", $id){
    $url = "";
    if($type === "app"){
      $url = L_SERVER . "/app/{$id}/download";
    }elseif($type === "lobby"){
      $url = L_SERVER . "/lobby/download/{$id}";
    }
    return $url;
  }
  
  /**
   * Get updates
   */
  public static function check(){
    $url = L_SERVER . "/lobby/updates";
    $apps = \Lobby\Apps::getApps();
    try {
      $response = \Requests::post($url, array(), array(
        "apps" => implode(",", $apps)
      ))->body;
    }catch (\Requests_Exception $error){
      \Lobby::log("Checkup with server failed ($url) : $error");
      $response = false;
    }
    if($response){
      
      $response = json_decode($response, true);
      if(is_array($response)){
        saveOption("lobby_latest_version", $response['version']);
        saveOption("lobby_latest_version_release", $response['released']);
        saveOption("lobby_latest_version_release_notes", $response['release_notes']);
    
        if(isset($response['apps']) && count($response['apps']) != 0){
          $AppUpdates = array();
          foreach($response['apps'] as $appID => $version){
            $App = new \Lobby\Apps($appID);
            if($App->info['version'] != $version){
              $AppUpdates[$appID] = $version;
            }
          }
          saveOption("app_updates", json_encode($AppUpdates));
        }
      }
    }
  }
  
  /**
   * Get Version of a component
   */
  public static function getDependencyVersion($dependency){
    
    switch($dependency){
      case "lobby":
        return getOption("lobby_version");
        break;
      case "curl":
        return function_exists("curl_version") ? curl_version() : 0;
        break;
      default:
        return 0;
    }
    
  }
  
  /**
   * Check requirements
   */
  public static function checkRequirements($requires, $boolean = false){
    $result = $requires;
    /**
     * How $requiredVersionInfo will look like :
     * array(
     *   ">=",
     *   "5.1"
     * )
     */
    foreach($requires as $dependency => $requiredVersionInfo){
      if(isset(self::$requireInfo[$dependency])){
        $currentVersion = self::getDependencyVersion($dependency);
        
        /**
         * Compare the current version and required version
         */
        if(version_compare($currentVersion, $requiredVersionInfo[1], $requiredVersionInfo[0])){
          $result[$dependency] = true;
        }else{
          $result[$dependency] = false;
        }
      }
    }
    return $boolean ? in_array(false, $result) : $result;
  }
  
}
