<?php

namespace SocialogCodeMirror;

/**
 * Socialog CodeMirror Module
 */
class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $sharedEventManager = $sm->get('SharedEventManager');

        // Inhaken in menue
        $sharedEventManager->attach('render', 'post.edit', function($e) use ($sm) {
            $view = $e->getTarget();
            $cfg = $sm->get('Config');
            if ($cfg['socialog-admin']['text-mode'] == 'markdown') {
                $codeMirror = $view->basePath($cfg['socialog-codemirror']['lib_path']);
                $view->headLink()
                    ->appendStylesheet($codeMirror. '/lib/codemirror.css')
                    ->appendStylesheet($codeMirror. '/../../css/main.css');
                
                $view->headScript()
                    ->appendFile($codeMirror. '/lib/codemirror.js')
                    ->appendFile($codeMirror. '/mode/xml/xml.js')
                    ->appendFile($codeMirror. '/mode/markdown/markdown.js')
                    ->appendFile($codeMirror. '/mode/gfm/gfm.js')
                    ->appendFile($codeMirror. '/mode/javascript/javascript.js')
                    ->appendScript(<<<SCRIPT
 $(function(){
      var editor = CodeMirror.fromTextArea($('textarea[name=content]').get(0), {
        mode: 'gfm',
        lineNumbers: true,
        matchBrackets: true,
        theme: "default"
      });
});
SCRIPT
);
                
            }
        }, 100);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}