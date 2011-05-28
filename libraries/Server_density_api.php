<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

error_reporting(0);

class Server_Density_API {
  
  var $CI;
  
  function Server_Density_API()
  {
    
    $this->CI =& get_instance();
    $this->CI->load->config('server_density_config');
        
    $this->sd_account = $this->CI->config->item('sd_account_name');
    $this->sd_user = $this->CI->config->item('sd_user_name');
    $this->sd_pass = $this->CI->config->item('sd_password');
	$this->api_version = $this->CI->config->item('sd_api_version');

    
    $this->sd_api_url = "http://api.serverdensity.com/".$this->api_version."/?account=".$this->sd_account.".serverdensity.com";
  
  }
  
  //Alerts
  function get_latest_alerts(){
  	  	
  	$alerts = $this->make_api_call($this->sd_api_url."&c=alerts/getlatest");
  	
  	return $alerts;
  		  
  }
  
  //Servers
  function get_servers(){
  	
  	$servers = $this->make_api_call($this->sd_api_url."&c=servers/list");
  	
  	return $servers;
  
  }
  
  function get_server_details($server_id){
  	
  	$server = $this->make_api_call($this->sd_api_url."&c=servers/getbyid&serverId=".$server_id);
  	
  	return $server;
  	
  }
  
  function get_server_stats($server_id){
  	
  	$server_stats = $this->make_api_call($this->sd_api_url."&c=metrics/getlatest&serverId=".$server_id);
  	
  	return $server_stats;
  		
  }

  //Metrics

  function get_metrics_latest($server_id,$metric_name = ''){
	
	$latest_metrics = $this->make_api_call($this->sd_api_url."&c=metrics/getLatest&serverId=".$server_id."&metricName=".$metric_name);

  	return $latest_metrics;
	
  }

  function get_metrics_latest_range($server_id,$metric_name,$start_time,$end_time){

  	$range_load = $this->make_api_call($this->sd_api_url."&c=metrics/getLatest&serverId=".$server_id."&metricName=".$metric_name."&rangeStart=".$start_time."&rangeEnd=".$end_time);

  	return $range_load;

  }

  function get_metrics_list($os){
	
	$list_metrics = $this->make_api_call($this->sd_api_url."&c=metrics/list&os=".$os);

  	return $list_metrics;
}
  
  //Load
  function get_server_load($server_id){
  	  	
  	$server_load = $this->make_api_call($this->sd_api_url."&c=metrics/getloadaverage&serverId=".$server_id);
  	
  	return $server_load;
  	
  }
  
  function get_server_load_range($server_id,$start_time,$end_time){
  	
  	$range_load = $this->make_api_call($this->sd_api_url."&c=metrics/getloadaveragerange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $range_load;
  	
  }
  
  //Memory
  function get_physical_memory($server_id){
  	  	
  	$physical_memory = $this->make_api_call($this->sd_api_url."&c=metrics/getmemphys&serverId=".$server_id);
  	
  	return $physical_memory;
  	
  }
  
  function get_physical_memory_range($server_id,$start_time,$end_time){
  	
  	$range_memory = $this->make_api_call($this->sd_api_url."&c=metrics/getmemphysrange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $range_memory;
  	
  }
  
  //Disk usage
  function get_disk_usage($server_id){
  	  	
  	$disk_usage = $this->make_api_call($this->sd_api_url."&c=metrics/getdiskusage&serverId=".$server_id);
  	
  	return $disk_usage;
  	
  }
  
  function get_disk_usage_range($server_id,$start_time,$end_time){
  	
  	$range_disk = $this->make_api_call($this->sd_api_url."&c=metrics/getdiskusagerange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $range_disk;
  	
  }
  
   //Network traffic
  function get_network_traffic($server_id){
  	  	
  	$network_traffic = $this->make_api_call($this->sd_api_url."&c=metrics/getnetworktraffic&serverId=".$server_id);
  	
  	return $network_traffic;
  	
  }
  
  function get_network_traffic_range($server_id,$start_time,$end_time){
  	
  	$network_traffic_range = $this->make_api_call($this->sd_api_url."&c=metrics/getnetworktrafficrange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $network_traffic_range;
  	
  }
  
  //Apache status
  function get_apache_status($server_id){
  	  	
  	$apache_status = $this->make_api_call($this->sd_api_url."&c=metrics/getapachestatus&serverId=".$server_id);
  	
  	return $apache_status;
  	
  }
  
  function get_apache_status_range($server_id,$start_time,$end_time){
  	
  	$apache_range = $this->make_api_call($this->sd_api_url."&c=metrics/getapachestatusrange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $apache_range;
  	
  }
  
  //Mysql status
  function get_mysql_status($server_id){
  	  	
  	$mysql_status = $this->make_api_call($this->sd_api_url."&c=metrics/getmysqlstatus&serverId=".$server_id);
  	
  	return $mysql_status;
  	
  }
  
  function get_mysql_status_range($server_id,$start_time,$end_time){
  	
  	$mysql_range = $this->make_api_call($this->sd_api_url."&c=metrics/getmysqlstatusrange&serverId=".$server_id."&rangeStart=".$start_time."&rangeEnd=".$end_time);
  	
  	return $mysql_range;
  	
  }

  
  //API call
  function make_api_call($url){
	
		$ch = NULL;
		$response = NULL;
		$results = NULL;
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->sd_user.":".$this->sd_pass);
	
		$results = curl_exec($ch);
		curl_close($ch);
	
		$response = json_decode($results);
		
		if($response->status==1){
			return $response->data;
		}else{
			return $response->error;
		}
		
		
	}
  
  
}

?>