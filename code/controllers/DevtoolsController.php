<?php
// {{license}}
class Meanbee_Diy_DevtoolsController extends Mage_Core_Controller_Front_Action {
    public function cacheRefreshAction() {
        Mage::app()->getCache()->clean();
        $this->_redirectReferer();
    }
    
    public function indexRefreshAction() {
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        
        foreach ($processes as $process) {
            $process->reindexEverything();
        }
        
        $this->_redirectReferer();
    }
    
    public function cacheDisableAction() {
        $cache_types = Mage::helper('core')->getCacheTypes();
        $enable = array();
        
        foreach ($cache_types as $type => $label) {
            $enable[$type] = 0;
        }
        
        Mage::app()->saveUseCache($enable);
        
        $this->_redirectReferer();
    }
    
    public function templateToggleAction() {
        $config_paths = array(
            'dev/debug/template_hints',
            'dev/debug/template_hints_blocks'
        );
        
        foreach ($config_paths as $path) {
            $this->_toggleConfig($path);
        }
        
        $this->_redirectReferer();
    }
    
    public function loggingToggleAction() {
        $this->_toggleConfig('dev/log/active');
        $this->_redirectReferer();
    }
    
    public function symlinkToggleAction() {
        $this->_toggleConfig('dev/template/allow_symlink');
        $this->_redirectReferer();
    }
    
    protected function _toggleConfig($path) {
        $old_value = Mage::getStoreConfigFlag($path);
        $new_value = !$old_value;
        
        Mage::getSingleton('core/config')->saveConfig($path, $new_value);
    }
}