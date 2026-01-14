<?php



class AppController
{

    protected function render(?string $template = null, array $variables = [])
    {

        $templatePathPhp = 'public/views/' . $template . '.php';
        $templatePathHtml = 'public/views/' . $template . '.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";

        // Determine which file to include
        $path = $templatePath404;
        if (file_exists($templatePathPhp)) {
            $path = $templatePathPhp;
        } elseif (file_exists($templatePathHtml)) {
            $path = $templatePathHtml;
        }

        if ($path !== $templatePath404) {
            // Auto-escape variables for XSS protection
            $variables = $this->secureData($variables);

            extract($variables);
            // $message = "Bledne haslo lub email";
            //echo $message; -> klucze staną sie zmiennymi bo tak dziala klasa extract

            ob_start();
            include $path;
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

    private function secureData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->secureData($value);
            }
        } elseif (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}