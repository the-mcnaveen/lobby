lobby.load(function(){
  $("#select_all_apps").live("click", function(){
    if($(this).is(":checked") === false){
      $("#apps_table input[type=checkbox]").prop("checked", false);
    }else{
      $("#apps_table input[type=checkbox]").prop("checked", true);
      $("#combined_actions").show();
    }
  });
});

