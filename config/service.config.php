<?php

namespace SocialogCodeMirror;

return array(
    'factories' => array(
        'socialog_codemirror_sundownrenderer' => function($sm) {
            $config = $sm->get('Config');
            $renderer = new Renderer\Sundown($config['socialog-codemirror']['sundown']['render_options']);
            return new \Sundown\Markdown($renderer);
        }
    )
);