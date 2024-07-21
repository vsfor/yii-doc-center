<?php
namespace jext\jrbac\src;

class DocParser
{
    private $params = [];

    public function parse($doc = '')
    {
        if ($doc == '') {
            return $this->params;
        }
        // Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false) {
            return $this->params;
        }
        $comment = '*'. str_replace(PHP_EOL, PHP_EOL.'*', $comment[1]);
        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false) {
            return $this->params;
        }
        $this->parseLines($lines[1]);
        return $this->params;
    }

    private function parseLines($lines)
    {
        $desc = [];
        foreach ($lines as $line) {
            $parsedLine = $this->parseLine($line); // Parse the line
            if ($parsedLine) {
                if (!isset($this->params['description'])) {
                    $this->params['description'] = $parsedLine;
                } else {
                    $desc[] = $parsedLine;
                }
            }
        }
        $desc = implode(PHP_EOL, $desc);
        if (!empty ($desc)) {
            $this->params['long_description'] = $desc;
        }
    }

    private function parseLine($line)
    {
        // trim the whitespace from the line
        $line = ltrim($line, "\* \t\n\r\0\x0B");
        $line = rtrim($line);

        if (empty($line)) {
            return false;
        } // Empty line

        if (strpos($line, '@') === 0) {
            if (strpos($line, ' ') > 0) {
                // Get the parameter name
                $param = substr($line, 1, strpos($line, ' ') - 1);
                $value = substr($line, strlen($param) + 2); // Get the value
            } else {
                $param = substr($line, 1);
                $value = '';
            }
            // Parse the line and return false if the parameter is valid
            if ($this->setParam($param, $value)) {
                return false;
            }
        }

        return $line;
    }

    private function setParam($param, $value)
    {
        if ($param == 'param' || $param == 'return') {
            $value = $this->formatParamOrReturn($value);
        }
        if ($param == 'class') {
            list ($param, $value) = $this->formatClass($value);
        }

        if (empty ($this->params[$param])) {
            $this->params[$param] = $value;
        } else {
            if ($param == 'param') {
                $arr = array(
                    $this->params[$param],
                    $value
                );
                $this->params[$param] = $arr;
            } else {
                $this->params[$param] = $value + $this->params[$param];
            }
        }
        return true;
    }

    private function formatClass($value)
    {
        $r = preg_split("[|]", $value);
        if (is_array($r)) {
            $param = $r[0];
            parse_str($r[1], $value);
            foreach ($value as $key => $val) {
                $val = explode(',', $val);
                if (count($val) > 1) {
                    $value[$key] = $val;
                }
            }
        } else {
            $param = 'Unknown';
        }
        return array(
            $param,
            $value
        );
    }

    private function formatParamOrReturn($string)
    {
        $pos = strpos($string, ' ');

        $type = substr($string, 0, $pos);
        return '(' . $type . ')' . substr($string, $pos + 1);
    }
}