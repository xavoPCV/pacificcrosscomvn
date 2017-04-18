<?php
defined('_JEXEC') or die;




class enewsletterViewpoll extends JViewLegacy {

	public function display($tpl = null) {
            
                 $get = JRequest::get('get');
                 
                 
                 if( $_GET['idpoll'] == '' ){
                      $this->data = base64_decode($get['data']);
                 }else {
                     
                       
                        $config = new JConfig();
                        $extoption['driver']   = $config->dbtype;
                        $extoption['host']     = $config->host;
                        $extoption['user']     = $config->user;    
                        $extoption['password'] = $config->password;   
                        $extoption['database'] = 'centcom';
                        $extoption['prefix']   = 'api_';
                        $Externaldb = & JDatabase::getInstance($extoption );
                       // $database = $config->db;

                       // $EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='".$database."'";   
                       // $Externaldb->setQuery($EXT_query);                      
                       // $id_site  = $Externaldb->loadObject()->id; 
                        
                                 
                        
                                   $query = "SELECT title from #__acepolls_polls where id = '".$get['idpoll']."' "; 
                                   $Externaldb->setQuery($query);  
                                   $name = $Externaldb->loadResult();
                                   
                                   $query = "SELECT id from #__acepolls_options where text='true' and poll_id = '".$get['idpoll']."' "; 
                                   $Externaldb->setQuery($query);  
                                   $idtrue = $Externaldb->loadResult();                                   
                                   
                                   $query = "SELECT id from #__acepolls_options where text='false' and poll_id = '".$get['idpoll']."' "; 
                                   $Externaldb->setQuery($query);  
                                   $idfalse = $Externaldb->loadResult();
                                   
                                   $totrue = 0;
                                   $query = "SELECT count(*) from #__acepolls_votes where option_id = '$idtrue' group by option_id "; 
                                   $Externaldb->setQuery($query);  
                                   $totrue = $Externaldb->loadResult();
                                   
                                   $tofalse = 0;
                                   $query = "SELECT count(*) from #__acepolls_votes where option_id = '$idfalse' group by option_id "; 
                                   $Externaldb->setQuery($query);  
                                   $tofalse = $Externaldb->loadResult();
                                   
                                   $this->data = $name.'|$|'.$totrue.'|$|'.$tofalse ;
                 }
              
                
                 
                 
                 parent::display($tpl);
		
		
	}//func

}
