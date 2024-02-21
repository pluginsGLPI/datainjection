
Datainjection changes WP:
  * cron-schedulable in automatic action, with 2 parameters CSV and Enabled Y/N
  * added diskinjection and vm injection
  * additional code in SoftwareVersionInjection and Iten_softwareversioninjection to make it work

PHP files changed 

Setup: version, register Diskinjection and  VMinjection

Hook: db csvfilename, enable_scheduled_injection
	Register PluginDatainjectionCron
	Disable uninstall with return true;
softwareversionversioninjection: ADD processAfterInsertOrUpdate

model:  add getter function getCSVFilename, getEnableScheduledInjection
	Rawsearchoptions array_merge, cron forms show, WP --   if (!$webservice && !$unique_filename)
	
Item_SoftwareVersionInjection:  added processAfterInsertOrUpdate

New Files:
+++item_diskinjection+++ 
+++cron.class+++
+++computervirtualmachineinjection+++


