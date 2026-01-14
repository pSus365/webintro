<?php

class AppController
{

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/' . $template . '.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";
        if (file_exists($templatePath)) {
            // ["message" => "Bledne haslo lub email"]
            extract($variables);
            // $message = "Bledne haslo lub email";
            //echo $message; -> klucze staną sie zmiennymi bo tak dziala klasa extract

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } else {
            ob_start();
            include $templatePath404;
            $output = ob_get_clean();
        }
        echo $output;
    }

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}