<?php

namespace SocialogCodeMirror;

use Zend\Mvc\MvcEvent;

/**
 * Socialog CodeMirror Module
 */
class Module
{
    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $sharedEventManager = $sm->get('SharedEventManager');
        $cfg = $sm->get('Config');

        if ($cfg['socialog-admin']['text-mode'] !== 'markdown') {
            return;
        }

        $renderer = $sm->get('socialog_codemirror_sundownrenderer');

        // Inhaken in menue
        $sharedEventManager->attach('render', array('post.edit', 'page.edit'), function($e) use ($sm, $cfg) {
                    $view = $e->getTarget();

                    $codeMirror = $view->basePath($cfg['socialog-codemirror']['lib_path']);
                    $view->headLink()
                            ->appendStylesheet($codeMirror . '/lib/codemirror.css')
                            ->appendStylesheet($codeMirror . '/../../css/main.css');

                    $view->headScript()
                            ->appendFile($codeMirror . '/lib/codemirror.js')
                            ->appendFile($codeMirror . '/mode/xml/xml.js')
                            ->appendFile($codeMirror . '/mode/markdown/markdown.js')
                            ->appendFile($codeMirror . '/mode/gfm/gfm.js')
                            ->appendFile($codeMirror . '/mode/javascript/javascript.js')
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
        });

        $sharedEventManager->attach('Socialog\Mapper\PostMapper', 'save', function($e) use ($sm) {
            $post = $e->getParam('post');
            $renderer = $sm->get('socialog_codemirror_sundownrenderer');
            $post->setContentHtml($renderer->render($post->getContent()));
        }, 100);

        $sharedEventManager->attach('Socialog\Mapper\PageMapper', 'save', function($e) use ($sm) {
            $post = $e->getParam('page');
            $renderer = $sm->get('socialog_codemirror_sundownrenderer');
            $post->setContentHtml($renderer->render($post->getContent()));
        }, 100);
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

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Service Configuration
     * 
     * @return array
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

}