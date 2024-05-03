<?php
namespace App\Utils;

use App\Model\User;

class PhpTemplateEngine
// переместить в utils
{
    private const TEMPLATES_DIR = '../templates/';
    public static function renderHTML(string $template): string
    {
        if (!ob_start())
        {
            throw new \RuntimeException("Failed to render template");
        }
        require self::TEMPLATES_DIR . $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function renderPHP(string $template, User $user): string
    {
        if (!ob_start())
        {
            throw new \RuntimeException("Failed to render template");
        }
        require self::TEMPLATES_DIR . $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
