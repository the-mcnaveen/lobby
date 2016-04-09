<?php
if(csrf()){
  $notifications = array();
  
  /**
   * If there is a update available either app or core, add an 
   * "Update Available" icon on the right side of panel
   */
  $AppUpdates = json_decode(getOption("app_updates"), true);
  $lobby_version = \Lobby::$version;
  $latestVersion = getOption("lobby_latest_version");
  
  if((count($AppUpdates) != 0) || ($latestVersion && $lobby_version != $latestVersion)){
    $notifications[] = array(
      "id" => "update",
      "contents" => "New Updates Are Available",
      "icon" => "update"
    );
  }
  
  echo json_encode($notifications);
}
