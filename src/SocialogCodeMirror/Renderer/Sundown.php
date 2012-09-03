<?php

namespace SocialogCodeMirror\Renderer;

class Sundown extends \Sundown\Render\Html
{
    public function __construct()
    {
        parent::__construct(array(
            'hard_wrap' => TRUE,
            'filter_html' => FALSE,
            'with_toc_data' => TRUE,
        ));
    }

    public function codespan($text)
    {
        return '<code>' . $text . '</code>';
    }

    public function blockCode($code, $mode)
    {
        $mime = $mode;
        $code = trim($code);

        if ($mode == 'php') {
            $mime = 'application/x-httpd-php-open';
            if (substr($code, 0, 5) == '<?php') {
                $code = trim(substr($code, 5));
            }
        }

        return '<div class="code-block" mode="' . $mode . '" mime="' . $mime . '">' . htmlentities($code) . '</div>';
    }
}