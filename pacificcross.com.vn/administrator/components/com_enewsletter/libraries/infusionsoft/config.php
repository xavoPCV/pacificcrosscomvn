<?php
#HT - those params will include from main file
//$infusionsoft_host = 'ku315.infusionsoft.com';
//$infusionsoft_api_key = '5e1a5f654e8231b31f7cde0f58060482';

//To Add Custom Fields, use the addCustomField method like below.
//Infusionsoft_Contact::addCustomField('_LeadScore');

//Below is just some magic...  Unless you are going to be communicating with more than one APP at the SAME TIME.  You can ignore it.
Infusionsoft_AppPool::addApp(new Infusionsoft_App($infusionsoft_host, $infusionsoft_api_key, 443));